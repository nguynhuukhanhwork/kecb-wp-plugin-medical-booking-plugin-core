<?php
/**
 * Medical Booking Core - Constants definition
 *
 * This file defines global constants for paths, URLs, and versions
 * used across the Medical Booking plugin. Organized by layers (DDD):
 * - Presentation
 * - Application
 * - Domain
 * - Infrastructure
 *
 * @package   MedicalBooking
 * @category  Config
 * @author    KhanhECB
 * @version   1.0.0
 * @since     1.0.0
 */
if (!defined('ABSPATH')) exit;

// Static constant (compile-time)
const MB_VERSION = '1.0.0';

// Dynamic constants (runtime)
define('MB_CORE_PATH', plugin_dir_path(__FILE__));
define('MB_CORE_URL', plugin_dir_url(__FILE__));

// Presentation Layer
define('MB_PRESENTATION_PATH', MB_CORE_PATH . 'inc/Presentation/');
define('MB_PRESENTATION_URL', MB_CORE_URL . 'inc/Presentation/');

// Application Layer
define('MB_APPLICATION_PATH', MB_CORE_PATH . 'inc/Application/');
define('MB_APPLICATION_URL', MB_CORE_URL . 'inc/Application/');

// Domain Layer
define('MB_DOMAIN_PATH', MB_CORE_PATH . 'inc/Domain/');
define('MB_DOMAIN_URL', MB_CORE_URL . 'inc/Domain/');

// Infrastructure Layer
define('MB_INFRASTRUCTURE_PATH', MB_CORE_PATH . 'inc/Infrastructure/');
define('MB_INFRASTRUCTURE_URL', MB_CORE_URL . 'inc/Infrastructure/');
