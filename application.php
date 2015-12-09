<?php
/**
 * File: src/application.php
 * Author: Robin Rijkeboer <rmrijkeboer@gmail.com>
 */

/**
 * Make sure dependencies have been installed, and load the autoloader.
 */
$file = $file = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($file)) {
    require $file;
} else if (!class_exists(beagon/OTPGen/OTPGen, false)) {
    throw new \Exception('Shit, it looks like dependencies have not yet been installed with Composer.');
}

/**
 * Ensure the timezone is set; otherwise you'll get a shit ton (that's a technical term) of errors.
 */
if (version_compare(phpversion(), '5.3.0') >= 0) {
    $tz = @ini_get('date.timezone');
    if (empty($tz)) {
        date_default_timezone_set(@date_default_timezone_get());
    }
}

/**
 * Specify the working directory, if it hasn't been set yet.
 */
if (!defined('OTPGEN_WORKING_DIR')) {
    $cwd = getcwd() . DIRECTORY_SEPARATOR;
    $cwd = str_replace('\\', '/', $cwd);
    define('OTPGEN_WORKING_DIR', $cwd);
}

/**
 * Load all the commands and create the instance
 */

use beagon\OTPGen\Command\CodeCommand;
use beagon\OTPGen\Command\AddCommand;
use beagon\OTPGen\Command\LibraryCommand;
use beagon\OTPGen\Command\DeleteCommand;
use beagon\OTPGen\OTPGen;

$composerData = file_get_contents(__DIR__ . '/composer.json');
$composerData = json_decode($composerData, true);
$version = $composerData['version'];

$application = new OTPGen('OTPGen', $version);
$application->add(new CodeCommand);
$application->add(new AddCommand);
$application->add(new LibraryCommand);
$application->add(new DeleteCommand);

/**
 * We return it so the CLI controller in /OTPGen can run it.
 */
return $application;
