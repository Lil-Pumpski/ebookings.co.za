<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// $route['default_controller'] = "welcome";  // or "auth", but only ONE
$route['default_controller'] = "auth";  // or "welcome", but only ONE
// $route['404_override'] = '';
$route['invoice/(:num)'] = 'invoice/index/$1';
$route['settings/translation/(:num)'] = 'settings/translation/index/$1';

$module_permission = array();
$modules_path = $this->config->item('module_location');
$modules = scandir($modules_path);

foreach($modules as $module)
{
    if($module === '.' || $module === '..') continue;

    if(is_dir($modules_path . '/' . $module))
    {
        $routes_path = $modules_path . $module . '/config/route.php';
        if(file_exists($routes_path))
        {
            require($routes_path);
            foreach($extension_route as $key => $extension_route_item) {
                $route[$key] = $module . '/' . $extension_route_item;
                $module_permission[$key] = $module . '/' . $extension_route_item;
            }
        }
    }
}

$route['extensions/change_extension_status'] = 'extensions/change_extension_status';
