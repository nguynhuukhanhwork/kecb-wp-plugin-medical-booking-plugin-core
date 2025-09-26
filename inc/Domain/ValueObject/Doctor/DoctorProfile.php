<?php

namespace MedicalBooking\Domain\ValueObject\Doctor;

class DoctorProfile
{
    private function __construct(
        private ?array $schedule = null,
        private ?string $bio = null,
        private ?string $featuredImageUrl = null
    ){

    }

    public function getSchedule(): array {return $this->schedule;}
    public function getBio(): ?string {return $this->bio;}
    public function getFeaturedImageUrl(): ?string {return $this->featuredImageUrl;}

}