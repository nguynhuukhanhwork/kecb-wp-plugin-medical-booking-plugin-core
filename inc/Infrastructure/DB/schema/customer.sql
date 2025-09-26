-- CUSTOMERS TABLE - Thay thế WP Users cho performance
CREATE TABLE IF NOT EXISTS {{customer_table}} (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_code VARCHAR(20) UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    address TEXT,
    emergency_contact VARCHAR(255),
    medical_history TEXT,
    allergies TEXT,

    -- WordPress Integration
    wp_user_id BIGINT UNSIGNED NULL, -- Link to WP Users if registered

    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Indexes for performance
    INDEX idx_customer_code (customer_code),
    INDEX idx_phone (phone),
    INDEX idx_email (email),
    INDEX idx_wp_user_id (wp_user_id),
    UNIQUE KEY unique_phone_email (phone, email)
) {{charset_collate}};