<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function my_autoloader($class)
{

    if (substr($class, 0, 9) == "MY_Addon_") {
   
       if (file_exists($file = APPPATH . 'core/' . $class . '.php')) {
            include $file;
        }
    }
}
spl_autoload_register('my_autoloader');

$route['default_controller'] = 'welcome/index';
$route['user/resetpassword/([a-z]+)/(:any)'] = 'site/resetpassword/$1/$2';
$route['admin/resetpassword/(:any)'] = 'site/admin_resetpassword/$1';
$route['admin/unauthorized'] = 'admin/admin/unauthorized';
$route['parent/unauthorized'] = 'parent/parents/unauthorized';
$route['student/unauthorized'] = 'user/user/unauthorized';
$route['teacher/unauthorized'] = 'teacher/teacher/unauthorized';
$route['accountant/unauthorized'] = 'accountant/accountant/unauthorized';
$route['librarian/unauthorized'] = 'librarian/librarian/unauthorized';

// $route['404_override'] = '';
$route['404_override'] = 'welcome/show_404';

$route['translate_uri_dashes'] = FALSE;
$route['cron/(:any)'] = 'cron/index/$1';

//======= front url rewriting==========
$route['page/(:any)'] = 'welcome/page/$1';
$route['read/(:any)'] = 'welcome/read/$1';
$route['online_admission'] = 'welcome/admission';
$route['examresult'] = 'welcome/examresult';
$route['frontend'] = 'welcome';
$route['cbseexam'] = 'welcome/cbseexam';
$route['online_course'] = 'course';
$route['annual_calendar'] = 'welcome/annual_calendar';

// Partner Registration Routes
$route['partner_registration'] = 'partner_registration/index';
$route['partner_registration/individual'] = 'partner_registration/individual';
$route['partner_registration/organization'] = 'partner_registration/organization';
$route['partner_registration/process_individual'] = 'partner_registration/process_individual';
$route['partner_registration/process_organization'] = 'partner_registration/process_organization';
$route['partner_registration/success'] = 'partner_registration/success';

// Partner Portal Routes
$route['partnerportal'] = 'partnerportal/login';
$route['partnerportal/login'] = 'partnerportal/login';
$route['partnerportal/register'] = 'partnerportal/register';
$route['partnerportal/logout'] = 'partnerportal/logout';
$route['partnerportal/forgot-password'] = 'partnerportal/forgot_password';
$route['partnerportal/reset-password/(:any)'] = 'partnerportal/reset_password/$1';
$route['partnerportal/verify-email/(:any)'] = 'partnerportal/verify_email/$1';

// User Partner Registration Routes
$route['user/partner_registration'] = 'user/partner_registration/index';
$route['user/partner_registration/student_register'] = 'user/partner_registration/student_register';
$route['user/partner_registration/staff_register'] = 'user/partner_registration/staff_register';
$route['user/partner_registration/process_student'] = 'user/partner_registration/process_student';
$route['user/partner_registration/process_staff'] = 'user/partner_registration/process_staff';

// User Partner Management Routes
$route['user/partner_management'] = 'user/partner_management/index';
$route['user/partner_management/add'] = 'user/partner_management/add';
$route['user/partner_management/process_add'] = 'user/partner_management/process_add';
$route['user/partner_management/edit/(:num)'] = 'user/partner_management/edit/$1';
$route['user/partner_management/process_edit/(:num)'] = 'user/partner_management/process_edit/$1';
$route['user/partner_management/delete/(:num)'] = 'user/partner_management/delete/$1';

// Partner Dashboard Routes
$route['partnerdashboard'] = 'partnerdashboard/index';
$route['partnerdashboard/profile'] = 'partnerdashboard/profile';
$route['partnerdashboard/contributions'] = 'partnerdashboard/contributions';
$route['partnerdashboard/giving-settings'] = 'partnerdashboard/giving_settings';
$route['partnerdashboard/change-password'] = 'partnerdashboard/change_password';
$route['partnerdashboard/receipt/(:any)'] = 'partnerdashboard/receipt/$1';
$route['partnerdashboard/update-profile'] = 'partnerdashboard/update_profile';
$route['partnerdashboard/update-giving-settings'] = 'partnerdashboard/update_giving_settings';
$route['partnerdashboard/update-password'] = 'partnerdashboard/update_password';
$route['partnerdashboard/add-contribution'] = 'partnerdashboard/add_contribution'; 
