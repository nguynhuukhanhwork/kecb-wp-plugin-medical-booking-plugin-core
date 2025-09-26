<?php

namespace MedicalBooking\Application\Dto\Doctor;

use MedicalBooking\Domain\Entity\Doctor;

final class SearchDoctorResponse
{
    /** @var Doctor[] */
    private array $doctors;
    private int $total;
    private int $page;
    private int $limit;

    /**
     * @param Doctor[] $doctors
     */
    public function __construct(array $doctors, int $total, int $page, int $limit)
    {
        $this->doctors = $doctors;
        $this->total   = $total;
        $this->page    = $page;
        $this->limit   = $limit;
    }

    /** @return Doctor[] */
    public function getDoctors(): array { return $this->doctors; }
    public function getTotal(): int { return $this->total; }
    public function getPage(): int { return $this->page; }
    public function getLimit(): int { return $this->limit; }
}
