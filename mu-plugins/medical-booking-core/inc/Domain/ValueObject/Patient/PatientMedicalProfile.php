<?php

namespace MedicalBooking\Domain\ValueObject\Patient;

final class PatientMedicalProfile
{
    public function __construct(
        private string $medical_history,
        private string $insurance,
        private string $note
    ) {}

    public function getMedicalHistory(): string { return $this->medical_history; }
    public function getInsurance(): string { return $this->insurance; }
    public function getNote(): string { return $this->note; }
}