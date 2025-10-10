<?php

namespace MedicalBooking\Domain\ValueObject\Patient;

final class PatientContactInfo
{
    public function __construct(
        string $phone,
        string $email,
        string $address
    ){
        // Check phone max length
        if ( strlen($phone) <= 0 || strlen($phone) > 25) {
            throw new \InvalidArgumentException("Phone must have a length between 0 and 25");
        }

        // Cho phép dạng: "0123456789" hoặc "+84123456789"
        if (!preg_match('/^\+?[0-9]+$/', $phone)) {
            throw new \InvalidArgumentException("Phone must contain only numbers and optionally start with +");
        }

        // Check Email
        if (!is_email($email)) {
            throw new \InvalidArgumentException("Email must have a valid email");
        }

    }
}