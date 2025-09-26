<?php

namespace MedicalBooking\Application\Dto\Doctor;

final class SearchDoctorRequest
{
    public function __construct(
        private readonly ?string $name,
        private readonly ?string $speciality,
        private readonly ?string $location,
        private readonly int $page = 1,
        private readonly int $limit = 10
    ) {
        if ($page < 1) {
            throw new \InvalidArgumentException("Page must be >= 1");
        }
        if ($limit < 1 || $limit > 100) {
            throw new \InvalidArgumentException("Limit must be between 1 and 100");
        }
    }

    public function getName(): ?string { return $this->name; }
    public function getSpeciality(): ?string { return $this->speciality; }
    public function getLocation(): ?string { return $this->location; }
    public function getPage(): int { return $this->page; }
    public function getLimit(): int { return $this->limit; }
}
