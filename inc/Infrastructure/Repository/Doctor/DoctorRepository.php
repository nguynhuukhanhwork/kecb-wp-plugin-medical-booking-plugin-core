<?php
/**
 * Repository quản lý dữ liệu Doctor.
 *
 * - Register Custom Post Type `doctor`
 * - Register ACF field group cho Doctor
 * - Query & map data from WordPress/ACF -> Entity Doctor
 *
 * @package MedicalBooking\Infrastructure\DB
 */


namespace MedicalBooking\Infrastructure\Repository\Doctor;
use MedicalBooking\Domain\Entity\Doctor;
use MedicalBooking\Domain\Repository\DoctorRepositoryInterface;
use function MedicalBooking\Helpers\kecb_register_acf_field_json;
use function MedicalBooking\Helpers\kecb_register_post_type_json;

class DoctorRepository implements DoctorRepositoryInterface
{
    /** @var string post_type Custom Post Type cho bác sĩ */
    public const post_type = 'doctor';

    // Config Const for Doctor Field (key ACF)
    public const DOCTOR_PHONE                 = 'doctor_phone';
    public const DOCTOR_EMAIL                 = 'doctor_email';
    public const DOCTOR_QUALIFICATION         = 'doctor_qualification';
    public const DOCTOR_YEARS_OF_EXPERIENCE   = 'doctor_years_of_experience';
    public const DOCTOR_CURRENT_POSITION      = 'doctor_current_position';
    public const DOCTOR_DEPARTMENT            = 'doctor_department';
    public const DOCTOR_SCHEDULE              = 'doctor_schedule';
    public const DOCTOR_BIO                   = 'doctor_bio';

    /** @var string Cache group */
    private const CACHE_GROUP = 'medical_booking_doctors';

    /** @var int Cache expiration time */
    private const CACHE_EXPIRATION = 6 * HOUR_IN_SECONDS;

    public ?string $acfJsonFilePath;
    public ?string $cptJsonFilePath;

    public function __construct()
    {
        $this->acfJsonFilePath = MBS_CORE_INFRASTRUCTURE_PATH . 'Config/acf-json/doctor-fields.json';
        $this->cptJsonFilePath = MBS_CORE_INFRASTRUCTURE_PATH . 'Config/cpt-json/doctor-cpt.json';
        add_action('init', [$this, 'registerCpt']);
        add_action('init', [$this, 'registerAcfFields']);
    }

    /**
     * Register Post Type 'doctor' with JSON config
     *
     * @return void
     */
    public function registerCpt(): void {
        kecb_register_post_type_json($this->cptJsonFilePath);
    }

    /**
     * Đăng ký field group ACF cho CPT Doctor
     *
     * @return void
     */
    /**
     * Đăng ký ACF field group từ JSON
     */
    public function registerAcfFields(): void
    {
        kecb_register_acf_field_json($this->acfJsonFilePath);
    }

    /**
     * Lấy tất cả Doctor IDs
     *
     * @return array
     */
    public static function getAllDoctorIds(): array
    {
        $cache_key = 'all_doctor_ids';
        $ids = get_transient($cache_key);

        if ($ids !== false) {
            return $ids;
        }

        $args = [
            'post_type'      => self::post_type,
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'posts_per_page' => -1,
        ];

        $ids = get_posts($args);

        // Cache for 1 hour
        set_transient($cache_key, $ids, HOUR_IN_SECONDS);

        return $ids;
    }

    /**
     * Lấy một Doctor theo ID
     *
     * @param int $post_id
     * @return Doctor|null
     */
    public static function findDoctorById(int $post_id): ?Doctor
    {
        $cache_key = "doctor_{$post_id}";
        $doctor = get_transient($cache_key);

        if ($doctor !== false) {
            return $doctor;
        }

        // Check post exists and is published
        if (get_post_status($post_id) !== 'publish') {
            return null;
        }

        $doctor = self::createDoctorFromPost($post_id);

        if ($doctor) {
            set_transient($cache_key, $doctor, self::CACHE_EXPIRATION);
        }

        return $doctor;
    }

    /**
     * Lấy tất cả Doctors - OPTIMIZED VERSION
     *
     * @return Doctor[]
     */
    public static function findAllDoctors(): array
    {
        $cache_key = 'all_doctors_optimized';
        $doctors = get_transient($cache_key);

        if ($doctors !== false) {
            return $doctors;
        }

        // Single optimized query
        $args = [
            'post_type'      => self::post_type,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC'
        ];

        $posts = get_posts($args);

        if (empty($posts)) {
            return [];
        }

        // Batch get all meta data to avoid N+1 queries
        $post_ids = wp_list_pluck($posts, 'ID');
        $all_meta = self::getBatchPostMeta($post_ids);

        $doctors = [];
        foreach ($posts as $post) {
            $doctor = self::createDoctorFromPostData(
                $post,
                $all_meta[$post->ID] ?? []
            );

            if ($doctor) {
                $doctors[] = $doctor;
            }
        }

        // Cache result
        set_transient($cache_key, $doctors, self::CACHE_EXPIRATION);

        return $doctors;
    }

    /**
     * Lấy doctors theo department
     *
     * @param string $department
     * @return Doctor[]
     */
    public static function findDoctorsByDepartment(string $department): array
    {
        $cache_key = "doctors_dept_" . sanitize_key($department);
        $doctors = get_transient($cache_key);

        if ($doctors !== false) {
            return $doctors;
        }

        $args = [
            'post_type'      => self::post_type,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => [
                [
                    'key'     => self::DOCTOR_DEPARTMENT,
                    'value'   => $department,
                    'compare' => '='
                ]
            ]
        ];

        $posts = get_posts($args);

        if (empty($posts)) {
            return [];
        }

        $post_ids = wp_list_pluck($posts, 'ID');
        $all_meta = self::getBatchPostMeta($post_ids);

        $doctors = [];
        foreach ($posts as $post) {
            $doctor = self::createDoctorFromPostData($post, $all_meta[$post->ID] ?? []);
            if ($doctor) {
                $doctors[] = $doctor;
            }
        }

        set_transient($cache_key, $doctors, self::CACHE_EXPIRATION);

        return $doctors;
    }



    /**
     * Tạo Doctor object từ post ID - helper method
     *
     * @param int $post_id
     * @return Doctor|null
     */
    private static function createDoctorFromPost(int $post_id): ?Doctor
    {
        $meta = get_post_meta($post_id);
        return self::createDoctorFromPostData(get_post($post_id), $meta);
    }

    /**
     * Tạo Doctor object từ post data và meta - DRY principle
     *
     * @param \WP_Post $post
     * @param array $meta
     * @return Doctor|null
     */
    private static function createDoctorFromPostData(\WP_Post $post, array $meta): ?Doctor
    {
        if (!$post || $post->post_status !== 'publish') {
            return null;
        }

        return new Doctor(
            id: $post->ID,
            name: $post->post_title,
            phone: $meta[self::DOCTOR_PHONE][0] ?? '',
            email: $meta[self::DOCTOR_EMAIL][0] ?? '',
            qualification: $meta[self::DOCTOR_QUALIFICATION][0] ?? '',
            yearsOfExperience: (int) ($meta[self::DOCTOR_YEARS_OF_EXPERIENCE][0] ?? 0),
            currentPosition: $meta[self::DOCTOR_CURRENT_POSITION][0] ?? '',
            department: $meta[self::DOCTOR_DEPARTMENT][0] ?? '',
            schedule: $meta[self::DOCTOR_SCHEDULE][0] ?? '',
            bio: $meta[self::DOCTOR_BIO][0] ?? '',
            featuredImageUrl: get_the_post_thumbnail_url($post->ID, 'thumbnail'),
        );
    }


    /**
     * Clear all department-related caches
     */
    private function clearDepartmentCaches(): void
    {
        global $wpdb;

        // Get all transients có pattern 'doctors_dept_*'
        $wpdb->query("
            DELETE FROM {$wpdb->options} 
            WHERE option_name LIKE '_transient_doctors_dept_%' 
            OR option_name LIKE '_transient_timeout_doctors_dept_%'
        ");
    }

    /**
     * Lấy doctors với pagination
     *
     * @param int $page
     * @param int $per_page
     * @return array ['doctors' => Doctor[], 'total' => int, 'pages' => int]
     */
    public static function findDoctorsWithPagination(int $page = 1, int $per_page = 10): array
    {
        $cache_key = "doctors_page_{$page}_{$per_page}";
        $result = get_transient($cache_key);

        if ($result !== false) {
            return $result;
        }

        $args = [
            'post_type'      => self::post_type,
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'paged'          => $page,
        ];

        $query = new \WP_Query($args);
        $posts = $query->posts;

        $doctors = [];
        if (!empty($posts)) {
            $post_ids = wp_list_pluck($posts, 'ID');
            $all_meta = self::getBatchPostMeta($post_ids);

            foreach ($posts as $post) {
                $doctor = self::createDoctorFromPostData($post, $all_meta[$post->ID] ?? []);
                if ($doctor) {
                    $doctors[] = $doctor;
                }
            }
        }

        $result = [
            'doctors' => $doctors,
            'total'   => $query->found_posts,
            'pages'   => $query->max_num_pages
        ];

        set_transient($cache_key, $result, self::CACHE_EXPIRATION);

        return $result;
    }

    /**
     * Search doctors by name or email
     *
     * @param string $search_term
     * @return Doctor[]
     */
    public static function searchDoctors(string $search_term): array
    {
        $cache_key = 'doctor_search_' . md5($search_term);
        $doctors = get_transient($cache_key);

        if ($doctors !== false) {
            return $doctors;
        }

        $args = [
            'post_type'      => self::post_type,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            's'              => $search_term, // Search in title and content
            'meta_query'     => [
                'relation' => 'OR',
                [
                    'key'     => self::DOCTOR_EMAIL,
                    'value'   => $search_term,
                    'compare' => 'LIKE'
                ]
            ]
        ];

        $posts = get_posts($args);

        $doctors = [];
        if (!empty($posts)) {
            $post_ids = wp_list_pluck($posts, 'ID');
            $all_meta = self::getBatchPostMeta($post_ids);

            foreach ($posts as $post) {
                $doctor = self::createDoctorFromPostData($post, $all_meta[$post->ID] ?? []);
                if ($doctor) {
                    $doctors[] = $doctor;
                }
            }
        }

        // Cache search results for shorter time
        set_transient($cache_key, $doctors, 30 * MINUTE_IN_SECONDS);

        return $doctors;
    }

    public function getById(int $doctor_id): Doctor {

    }

    public function getAllId(): array {
        $args = [
            'post_type' => self::post_type,
            'fields'   => 'ids',
            'post_status' => 'publish',
            'posts_per_page' => -1,

        ];

        $ids = get_posts($args);

        return $ids;
    }

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    /**
     * Tìm bác sĩ theo tên
     *
     * @param string $doctor_name
     * @return Doctor[]
     */
    public function search(string $doctor_name): array {
        $args = [
            'post_type'      => self::post_type,
            'post_status'    => 'publish',
            's'              => $doctor_name, // tìm trong title + content
            'posts_per_page' => 10,
        ];

        $query = new \WP_Query($args);


        return $query->posts;
    }
}