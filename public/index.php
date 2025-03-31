<?php
/*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 */
$system_path = 'system';

/*
 *---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 *---------------------------------------------------------------
 */
$application_folder = 'application';

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 */
$env = getenv('CI_ENV') ?: 'development';
define('ENVIRONMENT', $env);

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 */
switch (ENVIRONMENT) {
    case 'development':
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        break;
    case 'testing':
    case 'production':
        error_reporting(0);
        ini_set('display_errors', 0);
        break;
    default:
        exit('The application environment is not set correctly.');
}

/*
 *---------------------------------------------------------------
 * Resolve system path
 *---------------------------------------------------------------
 */
if (realpath($system_path) !== FALSE) {
    $system_path = realpath($system_path).'/';
}

$system_path = rtrim($system_path, '/').'/';

if (!is_dir($system_path)) {
    exit("Your system folder path does not appear to be set correctly.");
}

/*
 *---------------------------------------------------------------
 * Define core path constants
 *---------------------------------------------------------------
 */
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('EXT', '.php'); // Deprecated but kept for legacy compatibility
define('BASEPATH', str_replace("\\", "/", $system_path));
define('FCPATH', str_replace(SELF, '', __FILE__));
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

if (is_dir($application_folder)) {
    define('APPPATH', $application_folder.'/');
} else {
    if (!is_dir(BASEPATH.$application_folder.'/')) {
        exit("Your application folder path does not appear to be set correctly.");
    }
    define('APPPATH', BASEPATH.$application_folder.'/');
}

/*
 *---------------------------------------------------------------
 * SAFETY CHECK — NOW THAT BASEPATH IS DEFINED
 *---------------------------------------------------------------
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *---------------------------------------------------------------
 * DEBUG — REMOVE THIS IN PRODUCTION
 *---------------------------------------------------------------
 */
// echo "<pre>";
// echo "BASEPATH: " . (defined('BASEPATH') ? BASEPATH : 'NOT DEFINED') . "\n";
// echo "APPPATH: " . (defined('APPPATH') ? APPPATH : 'NOT DEFINED') . "\n";
// echo "SELF: " . (defined('SELF') ? SELF : 'NOT DEFINED') . "\n";
// echo "FCPATH: " . (defined('FCPATH') ? FCPATH : 'NOT DEFINED') . "\n";
// exit;

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 */
require_once BASEPATH.'core/CodeIgniter.php';

/* End of file index.php */
