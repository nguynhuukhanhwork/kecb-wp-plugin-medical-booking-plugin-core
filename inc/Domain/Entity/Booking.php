<?php
namespace MedicalBooking\Domain\Entity;

use MedicalBooking\Domain\ValueObject\Booking\BookingStatus;

final class Booking
{
    private int $booking_id;
    private \DateTimeImmutable $booking_date;
    private int $doctor_id;
    private int $service_id;
    private string $user_name;
    private string $user_email;
    private string $user_phone;
    private BookingStatus $status;

    public function __construct(
        int $booking_id,
        \DateTimeImmutable $booking_date,
        int $doctor_id,
        int $service_id,
        string $user_name,
        string $user_email,
        string $user_phone
    ) {
        if ($booking_date < new \DateTimeImmutable()) {
            throw new \InvalidArgumentException("Ngày đặt lịch phải ở tương lai");
        }
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Email không hợp lệ");
        }

        $this->booking_id = $booking_id;
        $this->booking_date = $booking_date;
        $this->doctor_id = $doctor_id;
        $this->service_id = $service_id;
        $this->user_name = $user_name;
        $this->user_email = $user_email;
        $this->user_phone = $user_phone;
        $this->status = BookingStatus::Pending();
    }

    public function confirm(): void
    {
        if ($this->status !== BookingStatus::Pending()) {
            throw new \DomainException("Chỉ booking ở trạng thái Pending mới có thể confirm");
        }
        $this->status = BookingStatus::Confirmed();
    }

    public function cancel(): void
    {
        if ($this->status === BookingStatus::Confirmed()) {
            throw new \DomainException("Không thể hủy booking đã hoàn tất");
        }
        $this->status = BookingStatus::Cancelled();
    }

    public function complete(): void
    {
        if ($this->status !== BookingStatus::Confirmed()) {
            throw new \DomainException("Chỉ booking Confirmed mới có thể hoàn tất");
        }
        $this->status = BookingStatus::Completed();
    }

    // Getter
    public function id(): int { return $this->booking_id; }
    public function status(): BookingStatus { return $this->status; }
    public function date(): \DateTimeImmutable { return $this->booking_date; }
}
