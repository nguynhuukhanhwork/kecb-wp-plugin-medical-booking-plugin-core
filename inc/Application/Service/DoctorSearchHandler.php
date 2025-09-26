<?php

namespace MedicalBooking\Application\Service;

use MedicalBooking\Application\Dto\Doctor\SearchDoctorRequest;
use MedicalBooking\Application\Dto\Doctor\SearchDoctorResponse;
use MedicalBooking\Domain\Repository\DoctorRepositoryInterface;

class DoctorSearchHandler
{
    public function __construct(
        private DoctorRepositoryInterface $doctorRepository
    ) {}

    public function searchDoctor(SearchDoctorRequest $request): SearchDoctorResponse
    {
        $doctors = $this->doctorRepository->search(
            $request->getName(),
            $request->getSpeciality(),
            $request->getLocation(),
            $request->getPage(),
            $request->getLimit()
        );

        $total = 1;

        return new SearchDoctorResponse(
            $doctors,
            $total,
            $request->getPage(),
            $request->getLimit()
        );
    }
}