<?php

namespace MedicalBooking\Domain\ValueObject\Doctor;

final class DoctorContactInfo
{
    public function __construct(
        private string $name,
        private string $email,
        private string $phone
    ) {
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

    /**
     * Static factory method to create DoctorContactInfo from WordPress data
     */
    public static function fromWordPressData(int $post_id): self
    {
        $name = get_the_title($post_id) ?: '';
        $email = get_field('doctor_email', $post_id) ?: '';
        $phone = get_field('doctor_phone', $post_id) ?: '';

        return new self($name, $email, $phone);
    }

    public function getName(): string { return $this->name; }
    public function getPhone(): string { return $this->phone; }
    public function getEmail(): string { return $this->email; }
}