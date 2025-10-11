<?php

namespace MedicalBooking\Presentation;

use MedicalBooking\Domain\Entity\Doctor;

/**
 * DoctorPresenter - Presentation Layer
 * 
 * Chịu trách nhiệm format dữ liệu Doctor để hiển thị trên giao diện
 * Tách biệt logic presentation khỏi business logic
 */
class DoctorPresenter
{
    /**
     * Format thông tin bác sĩ cho hiển thị
     * 
     * @param Doctor $doctor
     * @return array
     */
    public static function formatDoctorForDisplay(Doctor $doctor): array
    {
        return [
            'id' => $doctor->getId(),
            'name' => $doctor->getName(),
            'email' => $doctor->getEmail(),
            'phone' => $doctor->getPhone(),
            'qualification' => $doctor->getQualification(),
            'years_of_experience' => $doctor->getYearsOfExperience(),
            'current_position' => $doctor->getCurrentPosition(),
            'department' => $doctor->getDepartment(),
            'bio' => $doctor->getBio(),
            'schedule' => $doctor->getSchedule(),
            'featured_image_url' => $doctor->getFeaturedImageUrl(),
            'permalink' => get_permalink($doctor->getId()),
            'edit_link' => get_edit_post_link($doctor->getId()),
        ];
    }

    /**
     * Format số năm kinh nghiệm cho hiển thị
     * 
     * @param int $years
     * @return string
     */
    public static function formatExperienceForDisplay(int $years): string
    {
        if ($years === 0) {
            return 'Mới tốt nghiệp';
        } elseif ($years === 1) {
            return '1 năm kinh nghiệm';
        } else {
            return "{$years} năm kinh nghiệm";
        }
    }

    /**
     * Format số điện thoại cho hiển thị
     * 
     * @param string $phone
     * @return string
     */
    public static function formatPhoneForDisplay(string $phone): string
    {
        // Format phone number for display (Vietnamese format)
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        if (strpos($phone, '+84') === 0) {
            return substr($phone, 0, 3) . ' ' . substr($phone, 3, 4) . ' ' . substr($phone, 7);
        } elseif (strpos($phone, '0') === 0) {
            return substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7);
        }
        
        return $phone;
    }

    /**
     * Tạo breadcrumb cho trang bác sĩ
     * 
     * @param Doctor $doctor
     * @return array
     */
    public static function createBreadcrumb(Doctor $doctor): array
    {
        return [
            [
                'title' => 'Trang chủ',
                'url' => home_url('/')
            ],
            [
                'title' => 'Bác sĩ',
                'url' => home_url('/doctors/')
            ],
            [
                'title' => $doctor->getName(),
                'url' => get_permalink($doctor->getId()),
                'current' => true
            ]
        ];
    }

    /**
     * Tạo meta tags cho SEO
     * 
     * @param Doctor $doctor
     * @return array
     */
    public static function createMetaTags(Doctor $doctor): array
    {
        $bio = $doctor->getBio();
        $description = !empty($bio) ? wp_trim_words($bio, 20) : 
            "Bác sĩ {$doctor->getName()} - {$doctor->getCurrentPosition()} tại {$doctor->getDepartment()}";

        return [
            'title' => "Bác sĩ {$doctor->getName()} - {$doctor->getCurrentPosition()}",
            'description' => $description,
            'keywords' => implode(', ', [
                $doctor->getName(),
                $doctor->getCurrentPosition(),
                $doctor->getDepartment(),
                $doctor->getQualification()
            ]),
            'og:title' => "Bác sĩ {$doctor->getName()}",
            'og:description' => $description,
            'og:image' => $doctor->getFeaturedImageUrl() ?: '',
            'og:type' => 'profile'
        ];
    }
}
