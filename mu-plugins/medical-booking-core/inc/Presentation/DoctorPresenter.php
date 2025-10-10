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
     * Format danh sách bác sĩ cho hiển thị
     * 
     * @param Doctor[] $doctors
     * @return array
     */
    public static function formatDoctorsForDisplay(array $doctors): array
    {
        return array_map([self::class, 'formatDoctorForDisplay'], $doctors);
    }

    /**
     * Format thông tin bác sĩ cho JSON API
     * 
     * @param Doctor $doctor
     * @return array
     */
    public static function formatDoctorForApi(Doctor $doctor): array
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
        ];
    }

    /**
     * Format lịch trình bác sĩ cho hiển thị
     * 
     * @param array $schedule
     * @return array
     */
    public static function formatScheduleForDisplay(array $schedule): array
    {
        if (empty($schedule)) {
            return [];
        }

        $formatted_schedule = [];
        $days_of_week = [
            'monday' => 'Thứ 2',
            'tuesday' => 'Thứ 3',
            'wednesday' => 'Thứ 4',
            'thursday' => 'Thứ 5',
            'friday' => 'Thứ 6',
            'saturday' => 'Thứ 7',
            'sunday' => 'Chủ nhật'
        ];

        // Handle different schedule formats
        foreach ($schedule as $day => $times) {
            // If $times is a string (simple format), use it directly
            if (is_string($times) && !empty($times)) {
                if (isset($days_of_week[$day])) {
                    $formatted_schedule[$days_of_week[$day]] = $times;
                } else {
                    // If day is not in our mapping, use the day as is
                    $formatted_schedule[$day] = $times;
                }
            } elseif (is_array($times) && !empty($times)) {
                // If $times is an array, join them
                if (isset($days_of_week[$day])) {
                    $formatted_schedule[$days_of_week[$day]] = implode(', ', $times);
                } else {
                    $formatted_schedule[$day] = implode(', ', $times);
                }
            }
        }

        // If schedule is a simple array of strings (not keyed by days)
        if (empty($formatted_schedule) && is_array($schedule)) {
            $first_item = reset($schedule);
            if (is_string($first_item) && !isset($days_of_week[key($schedule)])) {
                // This is likely a simple array of schedule strings
                foreach ($schedule as $schedule_item) {
                    if (is_string($schedule_item) && !empty($schedule_item)) {
                        $formatted_schedule[] = $schedule_item;
                    }
                }
            }
        }

        return $formatted_schedule;
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
