<?php
/**
 * Doctor Helper Functions for Theme
 * 
 * Các hàm helper để render dữ liệu bác sĩ trong theme
 * Tuân theo kiến trúc Layer - chỉ xử lý presentation logic
 */

use MedicalBooking\Application\Service\DoctorService;
use MedicalBooking\Presentation\DoctorPresenter;

/**
 * Lấy thông tin bác sĩ hiện tại (trong single-doctor.php)
 * 
 * @return array|null
 */
function get_current_doctor_data(): ?array
{
    global $post;
    
    if (!$post || $post->post_type !== 'doctor') {
        return null;
    }

    $doctorService = new DoctorService();
    $doctor = $doctorService->getDoctorById($post->ID);
    
    if (!$doctor) {
        return null;
    }

    return DoctorPresenter::formatDoctorForDisplay($doctor);
}

/**
 * Render thông tin liên hệ của bác sĩ
 * 
 * @param array $doctor_data
 * @param bool $echo
 * @return string
 */
function render_doctor_contact_info(array $doctor_data, bool $echo = true): string
{
    $output = '<div class="doctor-contact-info">';
    
    if (!empty($doctor_data['phone'])) {
        $formatted_phone = DoctorPresenter::formatPhoneForDisplay($doctor_data['phone']);
        $output .= sprintf(
            '<div class="contact-item phone"><i class="fas fa-phone"></i> <a href="tel:%s">%s</a></div>',
            esc_attr($doctor_data['phone']),
            esc_html($formatted_phone)
        );
    }
    
    if (!empty($doctor_data['email'])) {
        $output .= sprintf(
            '<div class="contact-item email"><i class="fas fa-envelope"></i> <a href="mailto:%s">%s</a></div>',
            esc_attr($doctor_data['email']),
            esc_html($doctor_data['email'])
        );
    }
    
    $output .= '</div>';
    
    if ($echo) {
        echo $output;
    }
    
    return $output;
}

/**
 * Render thông tin chuyên môn của bác sĩ
 * 
 * @param array $doctor_data
 * @param bool $echo
 * @return string
 */
function render_doctor_professional_info(array $doctor_data, bool $echo = true): string
{
    $output = '<div class="doctor-professional-info">';
    
    if (!empty($doctor_data['qualification'])) {
        $output .= sprintf(
            '<div class="info-item qualification"><strong>Học vị:</strong> %s</div>',
            esc_html($doctor_data['qualification'])
        );
    }
    
    if (!empty($doctor_data['years_of_experience'])) {
        $experience = DoctorPresenter::formatExperienceForDisplay($doctor_data['years_of_experience']);
        $output .= sprintf(
            '<div class="info-item experience"><strong>Kinh nghiệm:</strong> %s</div>',
            esc_html($experience)
        );
    }
    
    if (!empty($doctor_data['current_position'])) {
        $output .= sprintf(
            '<div class="info-item position"><strong>Chức vụ:</strong> %s</div>',
            esc_html($doctor_data['current_position'])
        );
    }
    
    if (!empty($doctor_data['department'])) {
        $output .= sprintf(
            '<div class="info-item department"><strong>Khoa:</strong> %s</div>',
            esc_html($doctor_data['department'])
        );
    }
    
    $output .= '</div>';
    
    if ($echo) {
        echo $output;
    }
    
    return $output;
}

/**
 * Render lịch trình làm việc của bác sĩ
 * 
 * @param array $doctor_data
 * @param bool $echo
 * @return string
 */
function render_doctor_schedule(array $doctor_data, bool $echo = true): string
{
    $schedule = DoctorPresenter::formatScheduleForDisplay($doctor_data['schedule'] ?? []);
    
    if (empty($schedule)) {
        return '';
    }
    
    $output = '<div class="doctor-schedule">';
    $output .= '<h3>Lịch làm việc</h3>';
    $output .= '<div class="schedule-list">';
    
    foreach ($schedule as $day => $times) {
        $output .= '<div class="schedule-day">';
        
        // Check if this is a simple array (numeric keys)
        if (is_numeric($day)) {
            // Simple array format - just display the schedule item
            $output .= esc_html($times);
        } else {
            // Keyed array format - display day and times
            $output .= sprintf('<strong>%s:</strong> ', esc_html($day));
            
            if (is_array($times)) {
                $output .= implode(', ', array_map('esc_html', $times));
            } else {
                $output .= esc_html($times);
            }
        }
        
        $output .= '</div>';
    }
    
    $output .= '</div></div>';
    
    if ($echo) {
        echo $output;
    }
    
    return $output;
}

/**
 * Render tiểu sử bác sĩ
 * 
 * @param array $doctor_data
 * @param bool $echo
 * @return string
 */
function render_doctor_bio(array $doctor_data, bool $echo = true): string
{
    if (empty($doctor_data['bio'])) {
        return '';
    }
    
    $output = '<div class="doctor-bio">';
    $output .= '<h3>Tiểu sử</h3>';
    $output .= '<div class="bio-content">' . wp_kses_post($doctor_data['bio']) . '</div>';
    $output .= '</div>';
    
    if ($echo) {
        echo $output;
    }
    
    return $output;
}

/**
 * Render hình ảnh bác sĩ
 * 
 * @param array $doctor_data
 * @param string $size
 * @param bool $echo
 * @return string
 */
function render_doctor_image(array $doctor_data, string $size = 'medium', bool $echo = true): string
{
    if (empty($doctor_data['featured_image_url'])) {
        return '';
    }
    
    $output = sprintf(
        '<div class="doctor-image"><img src="%s" alt="%s" class="doctor-photo" /></div>',
        esc_url($doctor_data['featured_image_url']),
        esc_attr($doctor_data['name'])
    );
    
    if ($echo) {
        echo $output;
    }
    
    return $output;
}

/**
 * Render breadcrumb cho trang bác sĩ
 * 
 * @param array $doctor_data
 * @param bool $echo
 * @return string
 */
function render_doctor_breadcrumb(array $doctor_data, bool $echo = true): string
{
    $doctor = new \MedicalBooking\Domain\Entity\Doctor(
        $doctor_data['id'],
        new \MedicalBooking\Domain\ValueObject\Doctor\DoctorContactInfo(
            $doctor_data['name'],
            $doctor_data['email'],
            $doctor_data['phone']
        ),
        new \MedicalBooking\Domain\ValueObject\Doctor\DoctorProfessionalInfo(
            $doctor_data['qualification'],
            $doctor_data['years_of_experience'],
            $doctor_data['current_position'],
            $doctor_data['department']
        ),
        new \MedicalBooking\Domain\ValueObject\Doctor\DoctorProfile(
            $doctor_data['schedule'],
            $doctor_data['bio'],
            $doctor_data['featured_image_url']
        )
    );
    
    $breadcrumb = DoctorPresenter::createBreadcrumb($doctor);
    
    $output = '<nav class="breadcrumb">';
    $output .= '<ol class="breadcrumb-list">';
    
    foreach ($breadcrumb as $item) {
        $output .= '<li class="breadcrumb-item">';
        
        if (isset($item['current'])) {
            $output .= '<span class="current">' . esc_html($item['title']) . '</span>';
        } else {
            $output .= sprintf('<a href="%s">%s</a>', esc_url($item['url']), esc_html($item['title']));
        }
        
        $output .= '</li>';
    }
    
    $output .= '</ol></nav>';
    
    if ($echo) {
        echo $output;
    }
    
    return $output;
}

/**
 * Render meta tags cho SEO
 * 
 * @param array $doctor_data
 * @return void
 */
function render_doctor_meta_tags(array $doctor_data): void
{
    $doctor = new \MedicalBooking\Domain\Entity\Doctor(
        $doctor_data['id'],
        new \MedicalBooking\Domain\ValueObject\Doctor\DoctorContactInfo(
            $doctor_data['name'],
            $doctor_data['email'],
            $doctor_data['phone']
        ),
        new \MedicalBooking\Domain\ValueObject\Doctor\DoctorProfessionalInfo(
            $doctor_data['qualification'],
            $doctor_data['years_of_experience'],
            $doctor_data['current_position'],
            $doctor_data['department']
        ),
        new \MedicalBooking\Domain\ValueObject\Doctor\DoctorProfile(
            $doctor_data['schedule'],
            $doctor_data['bio'],
            $doctor_data['featured_image_url']
        )
    );
    
    $meta_tags = DoctorPresenter::createMetaTags($doctor);
    
    echo '<meta name="description" content="' . esc_attr($meta_tags['description']) . '">';
    echo '<meta name="keywords" content="' . esc_attr($meta_tags['keywords']) . '">';
    echo '<meta property="og:title" content="' . esc_attr($meta_tags['og:title']) . '">';
    echo '<meta property="og:description" content="' . esc_attr($meta_tags['og:description']) . '">';
    echo '<meta property="og:image" content="' . esc_attr($meta_tags['og:image']) . '">';
    echo '<meta property="og:type" content="' . esc_attr($meta_tags['og:type']) . '">';
}
