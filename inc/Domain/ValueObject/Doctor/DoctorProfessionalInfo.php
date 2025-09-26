<?php

namespace MedicalBooking\Domain\ValueObject\Doctor;

final class DoctorProfessionalInfo
{
    public function __construct(
      private string $qualification,
      private int $yearsOfExperience,
      private string $currentPosition,
      private string $department,
    ){
        if ($this->yearsOfExperience < 0 || $this->yearsOfExperience > 50) {
            throw new \ArgumentCountError('Kinh nghiệm làm việc [1:50]');
        }
    }

    /**
     * Getter
     */
    public function getQualification(): string {return $this->qualification;}
    public function getYearsOfExperience(): int {return $this->yearsOfExperience;}
    public function getCurrentPosition(): string {return $this->currentPosition;}
    public function getDepartment(): int {return $this->department;}
}