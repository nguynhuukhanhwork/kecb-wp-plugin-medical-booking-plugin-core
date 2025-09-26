CREATE TABLE IF NOT EXISTS {{booking_table}} (
    id              BIGINT UNSIGNED INCREMENT PRIMARY KEY
    -- Type of Form (Contact, Consult, Patient,...)
    booking_type    VARCHAR(20)
    -- CPT Doctor ID
    doctor_id       INT FOREIGN KEY
    -- User contact
    user_name       VARCHAR(100) NOT NULL,
    user_email      VARCHAR(100) NOT NULL,
    user_phone      VARCHAR(100) NOT NULL,
    -- Data 
    booking_data    VARCHAR(500) NOT NULL
)