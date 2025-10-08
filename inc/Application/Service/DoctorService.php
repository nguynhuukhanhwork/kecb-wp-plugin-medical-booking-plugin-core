<?php

namespace MedicalBooking\Application\Service;

use MedicalBooking\Domain\Entity\Doctor;
use MedicalBooking\Domain\Repository\DoctorRepositoryInterface;
use MedicalBooking\Infrastructure\Repository\DoctorRepository;

/**
 * DoctorService - Application Service Layer
 * 
 * Xử lý business logic liên quan đến Doctor
 * Điều phối giữa Presentation layer và Domain/Infrastructure layers
 */
class DoctorService
{
    private DoctorRepositoryInterface $doctorRepository;

    public function __construct(DoctorRepositoryInterface $doctorRepository = null)
    {
        $this->doctorRepository = $doctorRepository ?: new DoctorRepository();
    }

    /**
     * Lấy thông tin bác sĩ theo ID
     * 
     * @param int $doctorId
     * @return Doctor|null
     */
    public function getDoctorById(int $doctorId): ?Doctor
    {
        try {
            return DoctorRepository::findDoctorById($doctorId);
        } catch (\Exception $e) {
            // Log error
            error_log("Error getting doctor by ID {$doctorId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy tất cả bác sĩ
     * 
     * @return Doctor[]
     */
    public function getAllDoctors(): array
    {
        try {
            return DoctorRepository::findAllDoctors();
        } catch (\Exception $e) {
            error_log("Error getting all doctors: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Tìm bác sĩ theo khoa/phòng ban
     * 
     * @param string $department
     * @return Doctor[]
     */
    public function getDoctorsByDepartment(string $department): array
    {
        try {
            return DoctorRepository::findDoctorsByDepartment($department);
        } catch (\Exception $e) {
            error_log("Error getting doctors by department {$department}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Tìm kiếm bác sĩ theo tên hoặc email
     * 
     * @param string $searchTerm
     * @return Doctor[]
     */
    public function searchDoctors(string $searchTerm): array
    {
        try {
            return DoctorRepository::searchDoctors($searchTerm);
        } catch (\Exception $e) {
            error_log("Error searching doctors with term '{$searchTerm}': " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách bác sĩ có phân trang
     * 
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function getDoctorsWithPagination(int $page = 1, int $perPage = 10): array
    {
        try {
            return DoctorRepository::findDoctorsWithPagination($page, $perPage);
        } catch (\Exception $e) {
            error_log("Error getting doctors with pagination: " . $e->getMessage());
            return [
                'doctors' => [],
                'total' => 0,
                'pages' => 0
            ];
        }
    }

    /**
     * Kiểm tra bác sĩ có tồn tại không
     * 
     * @param int $doctorId
     * @return bool
     */
    public function doctorExists(int $doctorId): bool
    {
        try {
            $doctor = $this->getDoctorById($doctorId);
            return $doctor !== null;
        } catch (\Exception $e) {
            error_log("Error checking doctor existence for ID {$doctorId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách các khoa/phòng ban có bác sĩ
     * 
     * @return array
     */
    public function getAvailableDepartments(): array
    {
        try {
            $doctors = $this->getAllDoctors();
            $departments = [];
            
            foreach ($doctors as $doctor) {
                $department = $doctor->getDepartment();
                if (!empty($department) && !in_array($department, $departments)) {
                    $departments[] = $department;
                }
            }
            
            sort($departments);
            return $departments;
        } catch (\Exception $e) {
            error_log("Error getting available departments: " . $e->getMessage());
            return [];
        }
    }
}
