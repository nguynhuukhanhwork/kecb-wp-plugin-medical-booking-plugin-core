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
     * Static factory method to create DoctorProfessionalInfo from WordPress data
     */
    public static function fromWordPressData(int $post_id): self
    {
        $qualification = get_field('doctor_qualification', $post_id) ?: '';
        $years_of_experience = (int) get_field('doctor_years_of_experience', $post_id) ?: 0;
        $current_position = get_field('doctor_current_position', $post_id) ?: '';
        $department = get_field('doctor_department', $post_id) ?: '';

        return new self($qualification, $years_of_experience, $current_position, $department);
    }

    /**
     * Getter
     */
    public function getQualification(): string {return $this->qualification;}
    public function getYearsOfExperience(): int {return $this->yearsOfExperience;}
    public function getCurrentPosition(): string {return $this->currentPosition;}
    public function getDepartment(): string {return $this->department;}
}