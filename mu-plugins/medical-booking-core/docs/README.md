# Medical Booking System - WordPress Plugin

## 🎯 **Ý tưởng (Concept)**

### **Tổng quan dự án**
Hệ thống đặt lịch khám bệnh y tế trên nền tảng WordPress, cho phép bệnh viện/phòng khám quản lý bác sĩ, dịch vụ và đặt lịch khám một cách hiệu quả.

### **Mục tiêu chính**
- **Digitalization**: Số hóa quy trình đặt lịch khám truyền thống
- **User Experience**: Cung cấp trải nghiệm đặt lịch đơn giản, nhanh chóng
- **Management**: Hệ thống quản lý tập trung cho admin
- **SEO Optimization**: Tối ưu hóa SEO cho các trang bác sĩ, dịch vụ

### **Đối tượng sử dụng**
- **Bệnh viện/Phòng khám**: Quản lý bác sĩ, dịch vụ, lịch khám
- **Bệnh nhân**: Đặt lịch khám, tư vấn online
- **Bác sĩ**: Quản lý lịch trình, thông tin cá nhân

---

## 🚨 **Problem (Vấn đề)**

### **1. Vấn đề từ phía bệnh nhân**
- **Khó khăn trong việc đặt lịch**: Phải gọi điện, chờ đợi, không biết lịch trống
- **Thiếu thông tin**: Không biết bác sĩ nào phù hợp, dịch vụ nào cần thiết
- **Thời gian**: Phải đến trực tiếp để đặt lịch, mất thời gian di chuyển
- **Giao tiếp**: Khó liên hệ, không có lịch sử khám bệnh

### **2. Vấn đề từ phía quản lý**
- **Quản lý thủ công**: Sử dụng sổ sách, Excel để quản lý lịch khám
- **Thiếu thống kê**: Không có báo cáo chi tiết về tình hình khám bệnh
- **Lãng phí tài nguyên**: Không tối ưu được lịch trình bác sĩ
- **Khó mở rộng**: Không có hệ thống để mở rộng quy mô

### **3. Vấn đề kỹ thuật**
- **Performance**: WordPress mặc định không tối ưu cho booking system
- **Scalability**: Cần hệ thống có thể mở rộng khi tăng số lượng user
- **Data Management**: Quản lý dữ liệu phức tạp (bác sĩ, dịch vụ, lịch khám)
- **Security**: Bảo mật thông tin bệnh nhân nhạy cảm

---

## 💡 **Cách giải quyết (Solution)**

### **1. Kiến trúc hệ thống**

#### **A. Multi-Layer Architecture**
```
┌─────────────────────────────────────────┐
│              Service Layer              │  ← Business Logic
├─────────────────────────────────────────┤
│            Repository Layer             │  ← Data Access Logic
├─────────────────────────────────────────┤
│              Database Layer             │  ← Table Management
└─────────────────────────────────────────┘
```

#### **B. Technology Stack**
- **Backend**: WordPress + PHP OOP
- **Database**: MySQL với custom tables
- **Frontend**: Elementor + Custom Forms
- **Plugins**: ACF (Advanced Custom Fields), Contact Form 7
- **Architecture**: Multi-Layer với Repository Pattern

### **2. Giải pháp cụ thể**

#### **A. Data Management**
- **Custom Post Types**: Doctor, Service cho quản lý nội dung
- **Custom Tables**: Booking data cho performance cao
- **Taxonomies**: Chuyên khoa, học vị, chức vụ cho phân loại
- **JSON Configuration**: Cấu hình CPT, ACF, Taxonomies

#### **B. User Interface**
- **Contact Form 7**: Tạo form đặt lịch linh hoạt
- **Custom Tags**: Hiển thị danh sách bác sĩ, dịch vụ trong form
- **Elementor**: Xây dựng giao diện responsive
- **AJAX**: Xử lý form không reload trang

#### **C. Performance Optimization**
- **Custom Tables**: Booking data riêng biệt để tăng tốc query
- **Caching**: Cache dữ liệu thường xuyên truy cập
- **Database Indexing**: Tối ưu index cho các query phức tạp
- **Lazy Loading**: Load dữ liệu khi cần thiết

#### **D. Security & Privacy**
- **Data Encryption**: Mã hóa thông tin nhạy cảm
- **Access Control**: Phân quyền rõ ràng cho admin/user
- **Input Validation**: Validate tất cả input từ user
- **SQL Injection Protection**: Sử dụng prepared statements

---

## 🏗️ **Kiến trúc hệ thống**

### **1. High-Level Architecture**

```
┌─────────────────────────────────────────────────────────────┐
│                    WordPress Core                           │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────────────┐  ┌─────────────────┐  ┌──────────────┐ │
│  │   mu-plugins    │  │    plugins      │  │    theme     │ │
│  │ (Core System)   │  │ (Extensions)    │  │ (UI Layer)   │ │
│  └─────────────────┘  └─────────────────┘  └──────────────┘ │
├─────────────────────────────────────────────────────────────┤
│              Database Layer (MySQL)                         │
└─────────────────────────────────────────────────────────────┘
```

### **2. Detailed Architecture**

#### **A. mu-plugins (Core System)**
```
medical-booking-core/
├── inc/
│   ├── Domain/                 # Business Logic
│   │   ├── Entities/          # Doctor, Service, Booking
│   │   └── Services/          # Business Services
│   ├── Application/           # Application Layer
│   │   ├── DTOs/             # Data Transfer Objects
│   │   └── Services/         # Application Services
│   └── Infrastructure/       # Infrastructure Layer
│       ├── DB/               # Database Layer
│       │   ├── BookingDb.php
│       │   ├── ConfigDb.php
│       │   └── InstallDb.php
│       ├── Repository/       # Repository Layer
│       │   └── BookingRepository.php
│       └── Config/           # Configuration
│           ├── acf-json/     # ACF Field Groups
│           └── tax-json/     # Taxonomy Definitions
```

#### **B. plugins (Extensions)**
```
plugins/
├── medical-booking-forms/     # Form Management
├── medical-booking-admin/     # Admin Interface
├── medical-booking-reports/   # Reporting System
└── medical-booking-import/    # Data Import/Export
```

#### **C. theme (UI Layer)**
```
theme/
├── templates/                 # Custom Templates
├── assets/                   # CSS, JS, Images
└── functions.php             # Theme Functions
```

### **3. Database Architecture**

#### **A. WordPress Tables (Existing)**
- `wp_posts`: Doctor, Service CPTs
- `wp_postmeta`: ACF metadata
- `wp_term_*`: Taxonomies (chuyên khoa, học vị)

#### **B. Custom Tables**
```sql
-- Bookings Table
CREATE TABLE wp_mb_bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_type VARCHAR(20),
    doctor_id INT,
    user_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(100) NOT NULL,
    user_phone VARCHAR(100) NOT NULL,
    booking_data TEXT NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### **4. Entity Relationships**

```
Doctor (CPT)
├── ACF Fields: phone, email, qualification, experience
├── Taxonomies: speciality, degree, position
└── Relationships: → Booking (1:many)

Service (CPT)
├── ACF Fields: description, price, duration
├── Taxonomies: speciality, service_type
└── Relationships: → Booking (1:many)

Booking (Custom Table)
├── Fields: doctor_id, user_name, user_email, status
├── Relationships: → Doctor (many:1), → Service (many:1)
└── Workflow: pending → confirmed → completed
```

### **5. Data Flow**

```
User Input (Form)
    ↓
Contact Form 7 (Validation)
    ↓
Hook Handler (Processing)
    ↓
Repository Layer (Data Access)
    ↓
Database Layer (Storage)
    ↓
WordPress Database
```

---

## 📊 **Features & Functionality**

### **1. Core Features**

#### **A. Doctor Management**
- ✅ Custom Post Type với ACF fields
- ✅ Taxonomy cho chuyên khoa, học vị, chức vụ
- ✅ Avatar và thông tin liên hệ
- ✅ Kinh nghiệm và nơi làm việc

#### **B. Service Management**
- ✅ Custom Post Type cho dịch vụ
- ✅ Pricing và duration information
- ✅ Taxonomy classification
- ✅ Status management (active/inactive)

#### **C. Booking System**
- ✅ Custom table cho performance
- ✅ Status workflow (pending → confirmed → completed)
- ✅ Email notifications
- ✅ Admin management interface

### **2. Advanced Features**

#### **A. Search & Filter**
- 🔄 Doctor search by speciality
- 🔄 Service search by category
- 🔄 Combined search functionality
- 🔄 SEO-optimized URLs

#### **B. Reporting & Analytics**
- 📊 Booking statistics
- 📊 Doctor performance metrics
- 📊 Revenue tracking
- 📊 Export functionality

#### **C. Integration**
- 📧 Email notifications
- 📱 SMS integration (future)
- 🔗 Calendar sync (future)
- 💳 Payment gateway (future)

---

## 🚀 **Implementation Status**

### **✅ Completed**
- [x] Database architecture design
- [x] Layer architecture implementation
- [x] Repository pattern implementation
- [x] Booking system core functionality
- [x] ACF field configuration
- [x] Taxonomy system
- [x] BaseDB abstract class

### **🔄 In Progress**
- [ ] Service layer implementation
- [ ] Frontend form integration
- [ ] Admin interface development
- [ ] Testing framework setup

### **📋 Planned**
- [ ] API layer development
- [ ] Caching implementation
- [ ] Performance optimization
- [ ] Security enhancements
- [ ] Documentation completion

---

## 🎯 **Benefits & Impact**

### **1. For Patients**
- **Convenience**: Đặt lịch 24/7, không cần gọi điện
- **Information**: Biết trước thông tin bác sĩ, dịch vụ
- **Time Saving**: Không cần chờ đợi, di chuyển
- **History**: Lưu trữ lịch sử khám bệnh

### **2. For Healthcare Providers**
- **Efficiency**: Tối ưu lịch trình bác sĩ
- **Analytics**: Thống kê chi tiết về hoạt động
- **Cost Reduction**: Giảm chi phí quản lý thủ công
- **Scalability**: Dễ dàng mở rộng quy mô

### **3. For System**
- **Performance**: Custom tables cho tốc độ cao
- **Maintainability**: Layer architecture dễ maintain
- **Scalability**: Có thể mở rộng theo nhu cầu
- **Security**: Bảo mật thông tin nhạy cảm

---

## 📚 **Documentation**

- [System Description](System-Description.MD) - Tổng quan hệ thống
- [Entity Description](Entity-Desciption.MD) - Mô tả các entities
- [Layer Architecture](Layer-Architecture.md) - Kiến trúc layers
- [Database Architecture](DB-Architecture.md) - Kiến trúc database
- [System Methods](System-Method-AI.MD) - Phương pháp hệ thống

---

## 👨‍💻 **Development Team**

**Author**: KhanhECB  
**Architecture**: Multi-Layer with Repository Pattern  
**Technology**: WordPress + PHP OOP  
**Version**: 1.0.0

---

*Hệ thống Medical Booking được thiết kế để giải quyết các vấn đề thực tế trong việc quản lý lịch khám bệnh, với kiến trúc hiện đại và khả năng mở rộng cao.*
