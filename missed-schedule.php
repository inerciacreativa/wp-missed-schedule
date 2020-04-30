<?php
/**
 * Plugin Name: ic Missed Schedule
 * Plugin URI:  https://github.com/inerciacreativa/wp-missed-schedule
 * Version:     1.0.0
 * Text Domain: ic-missed-schedule
 * Domain Path: /languages
 * Description: Soluciona problemas de "programación perdida".
 * Author:      Jose Cuesta
 * Author URI:  https://inerciacreativa.com/
 * License:     MIT
 * License URI: https://opensource.org/licenses/MIT
 */

use ic\Framework\Framework;
use ic\Plugin\MissedSchedule\MissedSchedule;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists(Framework::class)) {
	throw new RuntimeException(sprintf('Could not find %s class.', Framework::class));
}

if (!class_exists(MissedSchedule::class)) {
	$autoload = __DIR__ . '/vendor/autoload.php';

	if (file_exists($autoload)) {
		/** @noinspection PhpIncludeInspection */
		include_once $autoload;
	} else {
		throw new RuntimeException(sprintf('Could not load %s class.', MissedSchedule::class));
	}
}

MissedSchedule::create(__FILE__);
