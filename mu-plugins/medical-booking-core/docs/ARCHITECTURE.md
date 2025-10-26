# Kiến trúc hệ thống Medical Booking

## 🏗️ Tổng quan kiến trúc

Hệ thống Medical Booking được thiết kế theo **Layer Architecture** (Layered Architecture), tách biệt các tầng xử lý để đảm bảo tính mở rộng, bảo trì và kiểm thử.

## 📊 Sơ đồ kiến trúc

```
┌─────────────────────────────────────────────────────────┐
│                 PRESENTATION LAYER                      │
│  ┌─────────────────┐  ┌─────────────────┐              │
│  │   Theme Files   │  │   Presenters    │              │
│  │ single-doctor.php│  │ DoctorPresenter │              │
│  │ doctor-helpers.php│ │                 │              │
│  └─────────────────┘  └─────────────────┘              │
└─────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────┐
│                 APPLICATION LAYER                       │
│  ┌─────────────────┐  ┌─────────────────┐              │
│  │  DoctorService  │  │ PostSearchService│              │
│  │                 │  │                 │              │
│  │ Business Logic  │  │ Search Logic    │              │
│  └─────────────────┘  └─────────────────┘              │
└─────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────┐
│                   DOMAIN LAYER                          │
│  ┌─────────────────┐  ┌─────────────────┐              │
│  │    Entities     │  │ Value Objects   │              │
│  │   Doctor.php    │  │DoctorContactInfo│              │
│  │   Patient.php   │  │DoctorProfessional│             │
│  │   Service.php   │  │DoctorProfile    │              │
│  │   Booking.php   │  │PatientContactInfo│             │
│  └─────────────────┘  └─────────────────┘              │
│                                                         │
│  ┌─────────────────┐                                    │
│  │  Repository     │                                    │
│  │   Interfaces    │                                    │
│  │DoctorRepository │                                    │
│  │    Interface    │                                    │
│  └─────────────────┘                                    │
└─────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────┐
│                INFRASTRUCTURE LAYER                     │
│  ┌─────────────────┐  ┌─────────────────┐              │
│  │   Repository    │  │   Database      │              │
│  │DoctorRepository │  │   Classes       │              │
│  │PatientRepository│  │   BookingDb     │              │
│  │                 │  │   InstallDb     │              │
│  │ WordPress/ACF   │  │                 │              │
│  └─────────────────┘  └─────────────────┘              │
└─────────────────────────────────────────────────────────┘
```

## 🧩 Chi tiết từng Layer

### 1. Presentation Layer (Tầng Hiển thị)

**Vị trí**: `/wp-content/themes/astra-child/`

**Trách nhiệm**:
- Hiển thị dữ liệu cho người dùng cuối
- Xử lý input từ người dùng
- Định dạng dữ liệu cho giao diện
- SEO và meta tags

**Files chính**:
```
astra-child/
├── single-doctor.php      # Template hiển thị bác sĩ
├── doctor-helpers.php     # Helper functions
├── functions.php          # Theme functions
└── debug-doctor-data.php  # Debug helper
```

**Đặc điểm**:
- ❌ Không chứa business logic
- ✅ Chỉ gọi Application Services
- ✅ Sử dụng Presenters để format dữ liệu
- ✅ Responsive design

### 2. Application Layer (Tầng Ứng dụng)

**Vị trí**: `/inc/Application/Service/`

**Trách nhiệm**:
- Điều phối giữa các layer
- Xử lý business logic
- Quản lý transactions
- Orchestration và workflow

**Files**:
```
Application/Service/
├── DoctorService.php      # Business logic cho Doctor
└── PostSearchService.php  # Logic tìm kiếm
```

**Đặc điểm**:
- ❌ Không chứa logic hiển thị
- ❌ Không trực tiếp truy cập database
- ✅ Sử dụng Repository Interfaces
- ✅ Error handling và validation

### 3. Domain Layer (Tầng Miền)

**Vị trí**: `/inc/Domain/`

**Trách nhiệm**:
- Định nghĩa business entities
- Định nghĩa business rules
- Định nghĩa interfaces và contracts
- Core business logic

**Cấu trúc**:
```
Domain/
├── Entity/
│   ├── Doctor.php         # Doctor entity
│   ├── Patient.php        # Patient entity
│   ├── Service.php        # Service entity
│   └── Booking.php        # Booking entity
├── ValueObject/
│   ├── Doctor/
│   │   ├── DoctorContactInfo.php
│   │   ├── DoctorProfessionalInfo.php
│   │   └── DoctorProfile.php
│   ├── Patient/
│   │   ├── PatientContactInfo.php
│   │   ├── PatientPersonalInfo.php
│   │   └── PatientMedicalProfile.php
│   └── Booking/
│       ├── BookingContactInfo.php
│       └── BookingStatus.php
└── Repository/
    ├── DoctorRepositoryInterface.php
    ├── PatientRepositoryInterface.php
    ├── ServiceRepositoryInterface.php
    └── BookingRepositoryInterface.php
```

**Đặc điểm**:
- ❌ Không phụ thuộc vào framework
- ✅ Chứa business logic cốt lõi
- ✅ Định nghĩa contracts (interfaces)
- ✅ Immutable Value Objects

### 4. Infrastructure Layer (Tầng Cơ sở hạ tầng)

**Vị trí**: `/inc/Infrastructure/`

**Trách nhiệm**:
- Triển khai các interfaces
- Truy cập database
- Tích hợp với WordPress/ACF
- Caching và performance

**Cấu trúc**:
```
Infrastructure/
├── Repository/
│   ├── DoctorRepository.php
│   ├── PatientRepository.php
│   ├── ServiceRepository.php
│   └── BookingRepository.php
├── DB/
│   ├── BaseDB.php
│   ├── BookingDb.php
│   ├── InstallDb.php
│   └── ConfigDb.php
└── Config/
    ├── acf-json/          # ACF field configurations
    ├── cpt-json/          # Custom Post Type configs
    └── tax-json/          # Taxonomy configs
```

## 🔄 Luồng dữ liệu

### Luồng hiển thị bác sĩ (single-doctor.php)

```
1. single-doctor.php (Presentation)
   ↓ gọi get_current_doctor_data()
   
2. DoctorService (Application)
   ↓ gọi getDoctorById()
   
3. DoctorRepository (Infrastructure)
   ↓ truy vấn WordPress/ACF
   
4. Tạo Doctor Entity (Domain)
   ↓ với các Value Objects
   
5. DoctorPresenter (Presentation)
   ↓ format dữ liệu
   
6. single-doctor.php (Presentation)
   ↓ hiển thị kết quả
```

### Luồng tạo booking

```
1. Booking Form (Presentation)
   ↓ submit form
   
2. BookingService (Application)
   ↓ validate business rules
   
3. BookingRepository (Infrastructure)
   ↓ save to database
   
4. Booking Entity (Domain)
   ↓ với Value Objects
   
5. Response (Presentation)
   ↓ hiển thị kết quả
```

## 🏛️ Entities và Value Objects

### Doctor Entity

```php
final class Doctor
{
    public function __construct(
        private int                     $id,
        private DoctorContactInfo       $contactInfo,
        private DoctorProfessionalInfo  $professionalInfo,
        private DoctorProfile           $profile,
    ){}
}
```

**Value Objects**:
- `DoctorContactInfo`: Tên, email, phone
- `DoctorProfessionalInfo`: Học vị, kinh nghiệm, chức vụ, khoa
- `DoctorProfile`: Lịch làm việc, tiểu sử, hình ảnh

### Patient Entity

```php
final class Patient
{
    public function __construct(
        private int                     $id,
        private PatientContactInfo      $contactInfo,
        private PatientPersonalInfo     $personalInfo,
        private PatientMedicalProfile   $medicalProfile,
    ){}
}
```

### Service Entity

```php
final class Service
{
    public function __construct(
        private int                     $id,
        private string                  $name,
        private string                  $description,
        private float                   $price,
        private string                  $duration,
    ){}
}
```

### Booking Entity

```php
final class Booking
{
    public function __construct(
        private int                     $id,
        private int                     $doctorId,
        private int                     $patientId,
        private int                     $serviceId,
        private DateTime                $appointmentDate,
        private BookingStatus           $status,
        private BookingContactInfo      $contactInfo,
    ){}
}
```

## 🔧 Repository Pattern

### Interface Definition

```php
interface DoctorRepositoryInterface
{
    public function getById(int $doctor_id): DoctorTag;
    public function getAllId(): array;
    public function searchByName(string $doctor_name): array;
}
```

### Implementation
```php
class DoctorRepository implements DoctorRepositoryInterface
{
    // WordPress/ACF specific implementation
    // Caching strategies
    // Optimized queries
}
```

## ⚡ Performance & Caching

### Caching Strategy
- **WordPress Transients**: Cache kết quả queries
- **Object Cache**: Cache entities
- **Batch Loading**: Tránh N+1 queries

### Optimization Techniques
```php
// Batch loading meta data
private static function getBatchPostMeta(array $post_ids): array
{
    // Single query for all meta data
    // Reduces database calls
}
```

## 🧪 Testing Strategy

### Unit Tests
- Domain entities
- Value Objects
- Application Services
- Repository implementations

### Integration Tests
- Layer interactions
- Database operations
- WordPress integration

## 🔒 Security & Validation

### Input Validation
- Type checking trong Value Objects
- Sanitization trong Repository
- WordPress nonces

### Data Integrity
- Immutable Value Objects
- Business rule validation
- Database constraints

## 📈 Scalability

### Horizontal Scaling
- Stateless services
- Database sharding ready
- Cache layer separation

### Vertical Scaling
- Optimized queries
- Efficient caching
- Memory management

## 🔮 Future Enhancements

### Microservices Ready
- Clear layer boundaries
- Interface-based design
- Event-driven architecture potential

### API Layer
- REST API endpoints
- GraphQL support
- Mobile app ready

---

*Kiến trúc này đảm bảo tính mở rộng, bảo trì và kiểm thử cho hệ thống Medical Booking.*
