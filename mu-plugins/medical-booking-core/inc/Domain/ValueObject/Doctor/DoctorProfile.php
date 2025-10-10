<?php

namespace MedicalBooking\Domain\ValueObject\Doctor;

class DoctorProfile
{
    public function __construct(
        private ?array $schedule = null,
        private ?string $bio = null,
        private ?string $featuredImageUrl = null
    ){

    }

    /**
     * Static factory method to create DoctorProfile from WordPress data
     */
    public static function fromWordPressData(int $post_id): self
    {
        $schedule_raw = get_field('doctor_schedule', $post_id);
        
        // Ensure schedule is always an array
        if (is_string($schedule_raw)) {
            // If it's a string, try to decode it or convert to array
            $schedule = !empty($schedule_raw) ? [$schedule_raw] : [];
        } elseif (is_array($schedule_raw)) {
            $schedule = $schedule_raw;
        } else {
            $schedule = [];
        }
        
        $bio = get_field('doctor_bio', $post_id) ?: '';
        $featured_image_url = get_the_post_thumbnail_url($post_id, 'full') ?: null;

        return new self($schedule, $bio, $featured_image_url);
    }

    public function getSchedule(): array {return $this->schedule ?: [];}
    public function getBio(): ?string {return $this->bio;}
    public function getFeaturedImageUrl(): ?string {return $this->featuredImageUrl;}

}