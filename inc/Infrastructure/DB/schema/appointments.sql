-- BOOKINGS TABLE - Core booking functionality
CREATE TABLE IF NOT EXISTS {{appointment_table}}
(
    id
    BIGINT
    UNSIGNED
    AUTO_INCREMENT
    PRIMARY
    KEY,
    booking_code
    VARCHAR
(
    30
) UNIQUE NOT NULL,

    -- WordPress Post References
    doctor_post_id BIGINT UNSIGNED NOT NULL, -- Reference to wp_posts
    service_post_id BIGINT UNSIGNED NOT NULL, -- Reference to wp_posts

-- Custom Table References
    customer_id BIGINT UNSIGNED NOT NULL,

    -- Booking Details
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    session ENUM
(
    'morning',
    'afternoon',
    'evening'
) NOT NULL,
    estimated_duration INT DEFAULT 30,

    -- Snapshot data (denormalized for performance)
    doctor_name VARCHAR
(
    255
) NOT NULL,
    doctor_title VARCHAR
(
    50
),
    service_name VARCHAR
(
    255
) NOT NULL,
    customer_name VARCHAR
(
    255
) NOT NULL,
    customer_phone VARCHAR
(
    20
) NOT NULL,
    customer_email VARCHAR
(
    100
),
    clinic_name VARCHAR
(
    255
),
    speciality_name VARCHAR
(
    255
),

    -- Status Management
    status ENUM
(
    'pending',
    'confirmed',
    'completed',
    'cancelled',
    'no_show'
) DEFAULT 'pending',
    priority ENUM
(
    'normal',
    'urgent',
    'vip'
) DEFAULT 'normal',

    -- Medical Information
    symptoms TEXT,
    notes TEXT,
    special_requirements TEXT,

    -- Financial
    consultation_fee DECIMAL
(
    10,
    2
) DEFAULT 0,
    service_fee DECIMAL
(
    10,
    2
) DEFAULT 0,
    total_fee DECIMAL
(
    10,
    2
) GENERATED ALWAYS AS
(
    consultation_fee
    +
    service_fee
) STORED,
    payment_method ENUM
(
    'cash',
    'card',
    'transfer',
    'insurance'
) DEFAULT 'cash',
    payment_status ENUM
(
    'unpaid',
    'paid',
    'refunded'
) DEFAULT 'unpaid',

    -- WordPress Integration
    created_by_user BIGINT UNSIGNED, -- WP User who created

-- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    confirmed_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,

    -- Foreign Keys
--     FOREIGN KEY (customer_id) REFERENCES booking_customers(id),
--     FOREIGN KEY (doctor_post_id) REFERENCES wp_posts(ID),
--     FOREIGN KEY (service_post_id) REFERENCES wp_posts(ID),

    -- Performance Indexes
    INDEX idx_appointment_date_time
(
    appointment_date,
    appointment_time
),
    INDEX idx_doctor_date
(
    doctor_post_id,
    appointment_date
),
    INDEX idx_customer_bookings
(
    customer_id,
    appointment_date
),
    INDEX idx_status_date
(
    status,
    appointment_date
),
    INDEX idx_booking_code
(
    booking_code
),
    INDEX idx_created_at
(
    created_at
),

    -- Composite indexes for common queries
    INDEX idx_doctor_date_status
(
    doctor_post_id,
    appointment_date,
    status
),
    INDEX idx_customer_phone
(
    customer_phone
)
    ) {{charset_collate}};