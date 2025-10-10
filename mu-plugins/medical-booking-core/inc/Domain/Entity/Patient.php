<?php

namespace MedicalBooking\Domain\Entity;

use MedicalBooking\Domain\ValueObject\Patient\PatientContactInfo;
use MedicalBooking\Domain\ValueObject\Patient\PatientMedicalProfile;
use MedicalBooking\Domain\ValueObject\Patient\PatientPersonalInfo;

/**
 * Entity Patient
 *
 * - Gồm 3 Value Object:
 *   - PatientPersonalInfo
 *   - PatientContactInfo
 *   - PatientMedicalInfo
 */
final class Patient
{
    private ?int $id = null;
    private PatientPersonalInfo $personal_info;
    private PatientContactInfo $contact_info;
    private PatientMedicalProfile $medical_profile;

    private function __construct(
        ?int $id,
        PatientPersonalInfo $personal_info,
        PatientContactInfo $contact_info,
        PatientMedicalProfile $medical_profile
    ){}

    // Getter
    public function getId(): ?int { return $this->id;}
    public function getPersonalInfo(): PatientPersonalInfo { return $this->personal_info; }
    public function getContactInfo(): PatientContactInfo { return $this->contact_info; }
    public function getMedicalProfile(): PatientMedicalProfile { return $this->medical_profile; }

    /**
     * Create New Patient
     * @param int|null $id
     * @param PatientPersonalInfo $personal_info
     * @param PatientContactInfo $contact_info
     * @param PatientMedicalProfile $medical_profile
     * @return self
     */

    public static function create(
        ?int $id,
        PatientPersonalInfo $personal_info,
        PatientContactInfo $contact_info,
        PatientMedicalProfile $medical_profile
    ): self {
        return new self($id, $personal_info, $contact_info, $medical_profile);
    }

    public static function delete(int $id): void {

    }
}