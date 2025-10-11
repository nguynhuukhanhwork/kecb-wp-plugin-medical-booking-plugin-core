<?php
/**
 * Template for displaying single doctor posts
 *
 * Tuân theo kiến trúc Layer:
 * - Presentation Layer: Theme template
 * - Application Layer: DoctorService
 * - Domain Layer: Doctor Entity + Value Objects
 * - Infrastructure Layer: DoctorRepository
 */

// Lấy dữ liệu bác sĩ hiện tại thông qua Application Service
$doctor_data = get_current_doctor_data();

// Nếu không tìm thấy bác sĩ, hiển thị 404
if (!$doctor_data) {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part('404');
    exit;
}

// Render meta tags cho SEO
render_doctor_meta_tags($doctor_data);

get_header();
?>

<div class="single-doctor-page">
    <div class="container">
        <?php
        // Render breadcrumb
        render_doctor_breadcrumb($doctor_data);
        ?>

        <article class="doctor-profile">
            <header class="doctor-header">
                <div class="doctor-main-info">
                    <?php
                    // Render hình ảnh bác sĩ
                    render_doctor_image($doctor_data, 'large');
                    ?>

                    <div class="doctor-basic-info">
                        <h1 class="doctor-name"><?php echo esc_html($doctor_data['name']); ?></h1>

                        <?php
                        // Render thông tin chuyên môn
                        render_doctor_professional_info($doctor_data);
                        ?>

                        <?php
                        // Render thông tin liên hệ
                        render_doctor_contact_info($doctor_data);
                        ?>
                    </div>
                </div>
            </header>

            <div class="doctor-content">
                <?php
                // Render tiểu sử bác sĩ
                render_doctor_bio($doctor_data);
                ?>

                <!-- Các thông tin bổ sung có thể thêm vào đây -->
                <div class="doctor-actions">
                    <a href="<?php echo esc_url(home_url('/booking/?doctor=' . $doctor_data['id'])); ?>"
                       class="btn btn-primary">
                        Đặt lịch hẹn
                    </a>

                    <a href="<?php echo esc_url(home_url('/doctors/')); ?>"
                       class="btn btn-secondary">
                        Xem danh sách bác sĩ
                    </a>
                </div>
            </div>
        </article>

        <!-- Related doctors hoặc thông tin liên quan -->
        <aside class="doctor-sidebar">
            <div class="related-doctors">
                <h3>Bác sĩ cùng khoa</h3>
                <?php
                $doctor_service = new \MedicalBooking\Application\Service\DoctorService();
                $related_doctors = $doctor_service->getDoctorsByDepartment($doctor_data['department']);

                // Loại bỏ bác sĩ hiện tại khỏi danh sách
                $related_doctors = array_filter($related_doctors, function($doctor) use ($doctor_data) {
                    return $doctor->getId() !== $doctor_data['id'];
                });

                if (!empty($related_doctors)) {
                    echo '<ul class="related-doctors-list">';
                    foreach (array_slice($related_doctors, 0, 3) as $doctor) {
                        $related_data = \MedicalBooking\Presentation\DoctorPresenter::formatDoctorForDisplay($doctor);
                        echo '<li>';
                        echo '<a href="' . esc_url($related_data['permalink']) . '">';
                        echo esc_html($related_data['name']);
                        echo '</a>';
                        echo '<span class="position">' . esc_html($related_data['current_position']) . '</span>';
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>Không có bác sĩ nào khác trong cùng khoa.</p>';
                }
                ?>
            </div>
        </aside>
    </div>
</div>

<style>
/* CSS cho trang single doctor */
.single-doctor-page {
    padding: 20px 0;
}

.doctor-profile {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.doctor-header {
    padding: 30px;
    border-bottom: 1px solid #eee;
}

.doctor-main-info {
    display: flex;
    gap: 30px;
    align-items: flex-start;
}

.doctor-image {
    flex-shrink: 0;
}

.doctor-image img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #f0f0f0;
}

.doctor-basic-info {
    flex: 1;
}

.doctor-name {
    font-size: 2.5em;
    color: #333;
    margin: 0 0 20px 0;
    font-weight: 600;
}

.doctor-professional-info,
.doctor-contact-info {
    margin: 20px 0;
}

.info-item,
.contact-item {
    margin: 10px 0;
    padding: 8px 0;
    border-bottom: 1px solid #f5f5f5;
}

.contact-item i {
    width: 20px;
    margin-right: 10px;
    color: #007cba;
}

.doctor-content {
    padding: 30px;
}

.doctor-actions {
    margin-top: 30px;
    text-align: center;
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    margin: 0 10px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #007cba;
    color: white;
}

.btn-primary:hover {
    background: #005a87;
    color: white;
}

.btn-secondary {
    background: #f0f0f0;
    color: #333;
}

.btn-secondary:hover {
    background: #e0e0e0;
    color: #333;
}

.doctor-sidebar {
    margin-top: 40px;
}

.related-doctors {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
}

.related-doctors-list {
    list-style: none;
    padding: 0;
}

.related-doctors-list li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.related-doctors-list li:last-child {
    border-bottom: none;
}

.related-doctors-list a {
    font-weight: 500;
    color: #007cba;
    text-decoration: none;
}

.related-doctors-list .position {
    display: block;
    font-size: 0.9em;
    color: #666;
    margin-top: 2px;
}

.breadcrumb {
    margin-bottom: 30px;
}

.breadcrumb-list {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.breadcrumb-item {
    margin-right: 10px;
}

.breadcrumb-item:not(:last-child)::after {
    content: '›';
    margin-left: 10px;
    color: #666;
}

.breadcrumb-item a {
    color: #007cba;
    text-decoration: none;
}

.breadcrumb-item .current {
    color: #333;
    font-weight: 500;
}

@media (max-width: 768px) {
    .doctor-main-info {
        flex-direction: column;
        text-align: center;
    }

    .doctor-image img {
        width: 150px;
        height: 150px;
    }

    .doctor-name {
        font-size: 2em;
    }
}
</style>

<?php
get_footer();
