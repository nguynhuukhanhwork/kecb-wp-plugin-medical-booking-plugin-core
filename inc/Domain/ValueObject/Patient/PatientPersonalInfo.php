<?php

namespace MedicalBooking\Domain\ValueObject\Patient;

class PatientPersonalInfo
{
    private const ALLOWED_GENDERS = ['nam', 'nu'];
    public function __construct(
        private string $name,
        private string $gender,
        private int $age
    ){
        // Check age in [0:120]
        if ($age <= 0 || $age > 120) {
            throw new \InvalidArgumentException("Age must be between 0 and 120");
        }

        // Check gender is 'nam' or 'nu'
        if (!in_array(strtolower($this->gender), self::ALLOWED_GENDERS)) {
            throw new \InvalidArgumentException("Invalid gender");
        }

        // Check name is character and space
        if (!preg_match('/^[a-zA-Z\s]+$/u', $name)) {
            throw new \InvalidArgumentException("Name must only contain letters and spaces");
        }

    }

    public function getName(): string { return $this->name; }
    public function getGender(): string { return $this->gender; }
    public function getAge(): int { return $this->age; }
}