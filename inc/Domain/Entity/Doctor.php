<?php

namespace MedicalBooking\Domain\Entity;

use MedicalBooking\Domain\ValueObject\Doctor\DoctorContactInfo;
use MedicalBooking\Domain\ValueObject\Doctor\DoctorProfessionalInfo;
use MedicalBooking\Domain\ValueObject\Doctor\DoctorProfile;

final class Doctor
{
    public function __construct(
        private int                     $id,
        private DoctorContactInfo       $contactInfo,
        private DoctorProfessionalInfo  $professionalInfo,
        private DoctorProfile           $profile,
    ){}

    // Getter methods
    public function getId(): int 
    {
        return $this->id;
    }

    public function getContactInfo(): DoctorContactInfo 
    {
        return $this->contactInfo;
    }

    public function getProfessionalInfo(): DoctorProfessionalInfo 
    {
        return $this->professionalInfo;
    }

    public function getProfile(): DoctorProfile 
    {
        return $this->profile;
    }

    /**
     * Get doctor name
     */
    public function getName(): string
    {
        return $this->contactInfo->getName();
    }

    /**
     * Get doctor email
     */
    public function getEmail(): string
    {
        return $this->contactInfo->getEmail();
    }

    /**
     * Get doctor phone
     */
    public function getPhone(): string
    {
        return $this->contactInfo->getPhone();
    }

    /**
     * Get doctor qualification
     */
    public function getQualification(): string
    {
        return $this->professionalInfo->getQualification();
    }

    /**
     * Get years of experience
     */
    public function getYearsOfExperience(): int
    {
        return $this->professionalInfo->getYearsOfExperience();
    }

    /**
     * Get current position
     */
    public function getCurrentPosition(): string
    {
        return $this->professionalInfo->getCurrentPosition();
    }

    /**
     * Get department
     */
    public function getDepartment(): string
    {
        return $this->professionalInfo->getDepartment();
    }

    /**
     * Get bio
     */
    public function getBio(): ?string
    {
        return $this->profile->getBio();
    }

    /**
     * Get schedule
     */
    public function getSchedule(): array
    {
        return $this->profile->getSchedule();
    }

    /**
     * Get featured image URL
     */
    public function getFeaturedImageUrl(): ?string
    {
        return $this->profile->getFeaturedImageUrl();
    }
}