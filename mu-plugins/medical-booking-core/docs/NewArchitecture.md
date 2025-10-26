inc/
├── Core/                          # Business Logic
│   ├── Booking/
│   │   ├── BookingService.php    # Main business logic
│   │   ├── BookingValidator.php
│   │   └── BookingRepository.php # Chỉ cho custom table
│   ├── Doctor/
│   │   └── DoctorService.php     # Business logic nếu cần
│   └── Patient/
│       └── PatientService.php
│
├── Database/                      # Custom tables only
│   ├── BookingTable.php
│   └── migrations/
│
├── WordPress/                     # WP Integration
│   ├── PostTypes/
│   │   ├── DoctorPostType.php
│   │   └── ServicePostType.php
│   ├── Taxonomies/
│   │   └── register-taxonomies.php
│   ├── ACF/
│   │   └── register-fields.php
│   └── CF7/
│       └── BookingFormHandler.php
│
├── API/                          # Optional: REST/AJAX
│   └── BookingEndpoint.php
│
├── Admin/                        # Admin UI
│   └── BookingListPage.php
│
└── Helpers/
└── functions.php