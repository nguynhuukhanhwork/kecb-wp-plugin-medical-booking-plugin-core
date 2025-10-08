<?php
namespace MedicalBooking\Presentation\admin;

use Action_Scheduler\Migration\Config;
use MedicalBooking\Infrastructure\DB\ConfigDb;
use MedicalBooking\Infrastructure\Repository\FormSubmissionRepository;
use function MedicalBooking\Helpers\kecb_admin_show_data;
use function MedicalBooking\Helpers\kecb_get_form_submission_cf7;

/**
 * Admin page for Medical Booking plugin
 */
final class AdminPage
{
    private string $menu_slug = 'medical_booking_manager';
    private ConfigDb $config;

    /**
     * Constructor to initialize repository
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'registerAdminMenu']);
        $this->config = ConfigDb::getInstance();
    }

    /**
     * Register admin menu and submenus
     */
    public function registerAdminMenu(): void
    {
        // Parent Menu
        add_menu_page(
            'Medical Booking',
            'Medical Booking',
            'manage_options',
            $this->menu_slug,
            [$this, 'renderAdminPage'],
            'dashicons-calendar-alt',
            6
        );

        // Sub Menus: Custom Post Types
        add_submenu_page(
            $this->menu_slug,
            'Bác sĩ',
            'Bác sĩ',
            'manage_options',
            'edit.php?post_type=doctor'
        );

        add_submenu_page(
            $this->menu_slug,
            'Bệnh nhân',
            'Bệnh nhân',
            'manage_options',
            'edit.php?post_type=patient'
        );

        add_submenu_page(
            $this->menu_slug,
            'Dịch vụ',
            'Dịch vụ',
            'manage_options',
            'edit.php?post_type=service'
        );

        // Sub Menu: Documents
        add_submenu_page(
            $this->menu_slug,
            'Tài liệu',
            'Tài liệu',
            'manage_options',
            $this->menu_slug . '_documents',
            [$this, 'renderAdminPageDocs'],
            1
        );
    }

    /**
     * Render main admin page with form submissions
     */
    public function renderAdminPage(): void
    {
        $form_id = $this->config->getIdFormBooking();
        $data = kecb_get_form_submission_cf7($form_id, 30);

        if (empty($data)) {
            error_log('No form submissions found for form_post_id = ' . $form_id);
        }

        $header = ['Status', 'Tên', 'Email', 'Số điện thoại', 'Ngày đăng ký', 'Ghi chú', 'Ngày Submit form'];
        kecb_admin_show_data($header, $data);
    }


    /**
     * Render documents admin page
     */
    public function renderAdminPageDocs()
    {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Medical Booking Documents', 'medical-booking'); ?></h1>
        </div>
        <?php
        $docs_file = plugin_dir_path(__FILE__) . './document.php';
        if (file_exists($docs_file)) {
            require_once $docs_file;
        } else {
            error_log('Document file not found: ' . $docs_file);
            echo '<div class="notice notice-error"><p>' . esc_html__('File tài liệu không tồn tại.', 'medical-booking') . '</p></div>';
        }
    }
}
?>