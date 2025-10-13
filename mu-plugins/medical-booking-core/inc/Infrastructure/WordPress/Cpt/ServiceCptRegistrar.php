<?php

namespace MedicalBooking\Infrastructure\WordPress\Cpt;

final class ServiceCptRegistrar extends BaseCptRegistrar
{
    private static ?self $instance = null;

    public static function getInstance(string $path): self
    {
        if (self::$instance === null) {
            self::$instance = new self($path);
        }
        return self::$instance;
    }
}