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
 * @package   TravelBooking
 * @category  Config
 * @author    KhanhECB
 * @version   1.0.0
 * @since     1.0.0
 */
if (!defined('ABSPATH')) exit;

// Static constant (compile-time)
const MB_VERSION = '1.0.0';

// Dynamic constants (runtime)
define('TB_CORE_PATH', plugin_dir_path(__FILE__));
define('TB_CORE_URL', plugin_dir_url(__FILE__));


// Config folder
define('TB_CONFIG_PATH', TB_CORE_PATH . 'inc/Config/');


// Layer Path and URL constant
define('TB_PRESENTATION_LAYER_PATH', TB_CORE_PATH . 'inc/Presentation/');
define('TB_PRESENTATION_LAYER_URL', TB_CORE_URL . 'inc/Presentation/');















