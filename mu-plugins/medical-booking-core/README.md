# Medical Booking System

```sql
DROP TABLE wp_medicalbooking_patient, wp_medical_booking_patient, wp_medical_bookingpatient, wp_mb_patient
```

## 📋 Tổng quan

**Medical Booking System** là một hệ thống đặt lịch khám bệnh được xây dựng trên WordPress, sử dụng kiến trúc Layer Architecture để đảm bảo tính mở rộng, bảo trì và kiểm thử.

## 🎯 Mục tiêu

- Tạo hệ thống đặt lịch khám bệnh hiệu quả
- Quản lý thông tin bác sĩ, bệnh nhân và dịch vụ
- Cung cấp giao diện thân thiện cho người dùng
- Đảm bảo tính mở rộng và bảo trì dài hạn

## 🏗️ Kiến trúc

Hệ thống được xây dựng theo **Layer Architecture** với 4 tầng chính:

```
┌─────────────────────────────────────────┐
│          PRESENTATION LAYER             │ ← Theme, Templates, UI
├─────────────────────────────────────────┤
│          APPLICATION LAYER              │ ← Business Logic, Services
├─────────────────────────────────────────┤
│            DOMAIN LAYER                 │ ← Entities, Value Objects
├─────────────────────────────────────────┤
│        INFRASTRUCTURE LAYER             │ ← Database, WordPress/ACF
└─────────────────────────────────────────┘
```

## 📁 Cấu trúc thư mục

```
medical-booking-core/
├── docs/                    # Documentation
│   ├── README.md           # Tổng quan (file này)
│   ├── ARCHITECTURE.md     # Chi tiết kiến trúc
│   ├── CHANGELOG.md        # Lịch sử thay đổi
│   └── PROJECT_IDEA.md     # Ý tưởng dự án
├── inc/                    # Source code
│   ├── Application/        # Business logic
│   ├── Domain/            # Core entities
│   ├── Infrastructure/    # Database & external
│   └── Presentation/      # UI components
├── helpers.php            # Utility functions
├── loader.php            # Plugin loader
└── constant.php          # Constants
```

## 🚀 Cài đặt

### Yêu cầu hệ thống
- WordPress 5.0+
- PHP 8.0+
- MySQL 5.7+
- Advanced Custom Fields (ACF) Pro

### Cài đặt
1. Copy plugin vào `wp-content/mu-plugins/`
2. Kích hoạt ACF Pro
3. Import các ACF field groups từ `inc/Infrastructure/Config/acf-json/`
4. Cấu hình Custom Post Types

## 📖 Sử dụng

### Lấy thông tin bác sĩ
```php
$doctor_service = new \MedicalBooking\Application\Service\DoctorService();
$doctor = $doctor_service->getDoctorById(123);
```

### Hiển thị trong theme
```php
$doctor_data = get_current_doctor_data();
if ($doctor_data) {
    echo $doctor_data['name'];
    render_doctor_contact_info($doctor_data);
}
```

## 🔧 Tính năng chính

- ✅ **Quản lý bác sĩ**: Thông tin chi tiết, lịch làm việc, chuyên môn
- ✅ **Quản lý bệnh nhân**: Hồ sơ bệnh nhân, lịch sử khám
- ✅ **Quản lý dịch vụ**: Các loại dịch vụ y tế
- ✅ **Đặt lịch hẹn**: Hệ thống booking linh hoạt
- ✅ **Tìm kiếm**: Tìm bác sĩ theo chuyên khoa, tên
- ✅ **Responsive**: Giao diện thân thiện mobile

## 📊 Entities chính

| Entity | Mô tả |
|--------|-------|
| **Doctor** | Thông tin bác sĩ, lịch làm việc |
| **Patient** | Hồ sơ bệnh nhân, thông tin cá nhân |
| **Service** | Các dịch vụ y tế |
| **Booking** | Lịch hẹn khám bệnh |

## 🎨 Theme Integration

Hệ thống tích hợp với Astra Child Theme:
- `single-doctor.php` - Trang chi tiết bác sĩ
- `doctor-helpers.php` - Helper functions
- Responsive design
- SEO optimized

## 🔍 Debug & Development

### Debug mode
```php
// Trong wp-config.php
define('WP_DEBUG', true);

// Debug doctor data
yoursite.com/single-doctor/?debug_doctor=1&post_id=123
```

## 📚 Documentation

- [ARCHITECTURE.md](docs/ARCHITECTURE.md) - Chi tiết kiến trúc hệ thống
- [CHANGELOG.md](docs/CHANGELOG.md) - Lịch sử thay đổi
- [PROJECT_IDEA.md](docs/PROJECT_IDEA.md) - Ý tưởng và roadmap

## 🤝 Đóng góp

1. Fork repository
2. Tạo feature branch
3. Commit changes
4. Push và tạo Pull Request

## 📄 License

GPL v2 hoặc mới hơn

## 👥 Tác giả

- **KhanhECB** - Developer chính
- **Medical Booking Team** - Contributors

---

*Hệ thống được phát triển với mục tiêu tạo ra giải pháp đặt lịch khám bệnh hiệu quả và dễ sử dụng.*
