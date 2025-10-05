-- EMAIL QUEUE - Async email processing
CREATE TABLE IF NOT EXISTS {{queue_table}}
(
    id
    BIGINT
    UNSIGNED
    AUTO_INCREMENT
    PRIMARY
    KEY,
    booking_id
    BIGINT
    UNSIGNED,

    -- Email Details
    recipient_email
    VARCHAR
(
    255
) NOT NULL,
    recipient_name VARCHAR
(
    255
),
    subject VARCHAR
(
    500
) NOT NULL,
    body TEXT NOT NULL,
    email_type ENUM
(
    'confirmation',
    'reminder',
    'cancellation',
    'rescheduling'
) NOT NULL,

    -- Queue Management
    priority TINYINT DEFAULT 5, -- 1=highest, 10=lowest
    status ENUM
(
    'pending',
    'sending',
    'sent',
    'failed',
    'cancelled'
) DEFAULT 'pending',
    max_attempts TINYINT DEFAULT 3,
    attempts TINYINT DEFAULT 0,

    -- Scheduling
    scheduled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sent_at TIMESTAMP NULL,
    error_message TEXT,

    -- WordPress Integration
    created_by_user BIGINT UNSIGNED,

    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Foreign Keys
    FOREIGN KEY
(
    booking_id
) REFERENCES {{appointment_table}}
(
    id
) ON DELETE SET NULL,

    -- Indexes
    INDEX idx_status_scheduled
(
    status,
    scheduled_at
),
    INDEX idx_booking_id
(
    booking_id
),
    INDEX idx_email_type
(
    email_type
),
    INDEX idx_priority_status
(
    priority,
    status
)
    ) {{charset_collate}};