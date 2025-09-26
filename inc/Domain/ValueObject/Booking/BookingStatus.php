<?php
namespace MedicalBooking\Domain\ValueObject\Booking;

final class BookingStatus
{
    private string $value;

    private const ALLOWED = [
        'pending',
        'confirmed',
        'cancelled',
        'completed',
    ];

    private function __construct(string $value)
    {
        if (!in_array($value, self::ALLOWED, true)) {
            throw new \InvalidArgumentException("Invalid booking status: $value");
        }
        $this->value = $value;
    }

    public static function Pending(): self { return new self('pending'); }
    public static function Confirmed(): self { return new self('confirmed'); }
    public static function Cancelled(): self { return new self('cancelled'); }
    public static function Completed(): self { return new self('completed'); }

    public function value(): string { return $this->value; }

    public function equals(BookingStatus $other): bool
    {
        return $this->value === $other->value;
    }
}
