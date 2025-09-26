<?php

namespace MedicalBooking\Domain\Entity;

use MedicalBooking\Domain\ValueObject\Doctor\DoctorContactInfo;
use MedicalBooking\Domain\ValueObject\Doctor\DoctorProfessionalInfo;
use MedicalBooking\Domain\ValueObject\Doctor\DoctorProfile;

final class Doctor
{
    private ?int $id;

    public function __construct(
        int                     $id,
        DoctorContactInfo       $contactInfo,
        DoctorProfessionalInfo  $professionalInfo,
        DoctorProfile           $profile,
    ){}

    // Getter
    public function getId(): int {return $this->id;}
}