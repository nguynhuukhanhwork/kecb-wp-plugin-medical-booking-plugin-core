<?php

namespace MedicalBooking\Presentation\admin;

final class AdminPage
{
    private string $menu_slug = 'medical_booking_manager';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'registerAdminMenu']);
    }

    public function registerAdminMenu()
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
            $this->menu_slug.'_documents',
            [$this, 'renderAdminPageDocs'],
            1
        );
    }

    public function renderAdminPage()
    {
        // Admin page title
        echo '<div class="wrap"><h1>Medical Booking Settings</h1></div>';
    }

    public function renderAdminPageDocs()
    {
        // Admin page Doc
        echo '<div class="wrap"><h1>Medical Booking Documents</h1></div>';
        $docs_file = plugin_dir_path(__FILE__).'./document.php';
        require_once $docs_file;
    }
}
