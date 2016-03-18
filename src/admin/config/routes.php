<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
/* 
| Note for myself:
| Here is our scenario, I have a URL of http://example.com/account/manage/4123245/jakub
| 
| So if we analyze our URI, we have:
|	account – folder
|	manage – controller (with only an index class)
|	4123245 – a numeric value (input)
|	jakub – a string slug value (just extra input)
|
| From that we need to make sure that codeigniter understands our URI 
| (and it will not by default!).
| so we edit our /application/config/routes.php
|     $route['account/manage/(:num)/(:any)']  = "account/manage/|index/$1/$2";
|
| Index is the key here, as we need to route to the class (which is the index), 
| the values $1, $2 are just input (1st, 2nd respectively) to show it goes to the index class.
| 
*/

$route['default_controller'] = "common/login";
$route['login/(:any)'] = "common/login/$1";
$route['common/(:any)'] = "common/$1";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */
