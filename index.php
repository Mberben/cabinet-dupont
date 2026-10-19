<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__ . '/controllers/PostsController.php';
require __DIR__ . '/controllers/AdminController.php';
require __DIR__ . '/controllers/UsersController.php';
require_once __DIR__ . '/helpers/Auth.php';
require __DIR__ . '/controllers/AppointmentsController.php';

$postsController = new PostsController();
$adminController = new AdminController();
$usersController = new UsersController();
$appointmentsController = new AppointmentsController();
$page =$_GET['page'] ?? 'home';

if($page === 'home'){
 $postsController->home();
}
else if($page === 'admin') {
    $adminController->admin();
}
else if($page=== 'register') {
    $usersController->register();
}
else if($page === 'register-valid') {
    $usersController->registerValid();

}
else if($page === 'login') {
    $usersController->login();

}
else if($page === 'login-valid') {
    $usersController->loginValid();
}
else if($page === 'logout') {
    $usersController->logout();
}
 else if ($page === 'services') {
    $postsController->services();
} else if ($page === 'admin-services') {
    $adminController->adminServices();
}
 else if ($page === 'delete-service') {
    $adminController->deleteService();
}
 else if ($page === 'create-service') {
    $adminController->createService();
} else if ($page === 'create-service-valid') {
    $adminController->createServiceValid();
}
 else if ($page === 'news') {
    $postsController->newsList();
} else if ($page === 'news-detail') {
    $postsController->newsDetail();
} else if ($page === 'admin-news') {
    $adminController->adminNews();
} else if ($page === 'create-news') {
    $adminController->createNews();
} else if ($page === 'create-news-valid') {
    $adminController->createNewsValid();
} else if ($page === 'update-news') {
    $adminController->updateNews();
} else if ($page === 'update-news-valid') {
    $adminController->updateNewsValid();
} else if ($page === 'delete-news') {
    $adminController->deleteNews();
}
 else if ($page === 'admin-hours') {
    $adminController->adminHours();
} else if ($page === 'update-hours-valid') {
    $adminController->updateHoursValid();
} else if ($page === 'create-hour-valid') {
    $adminController->createHourValid();
} else if ($page === 'delete-hour') {
    $adminController->deleteHour();
} else if ($page === 'admin-patients') {
    $adminController->adminPatients();
} else if ($page === 'create-patient') {
    $adminController->createPatient();
} else if ($page === 'create-patient-valid') {
    $adminController->createPatientValid();
} else if ($page === 'update-patient') {
    $adminController->updatePatient();
} else if ($page === 'update-patient-valid') {
    $adminController->updatePatientValid();
} else if ($page === 'delete-patient') {
    $adminController->deletePatient();
} else if ($page === 'appointment') {
    $appointmentsController->appointment();
} else if ($page === 'appointment-valid') {
    $appointmentsController->appointmentValid();
} else if ($page === 'admin-appointments') {
    $adminController->adminAppointments();
} else if ($page === 'confirm-appointment') {
    $adminController->confirmAppointment();
} else if ($page === 'cancel-appointment') {
    $adminController->cancelAppointment();
} else if ($page === 'update-appointment') {
    $adminController->updateAppointment();
} else if ($page === 'update-appointment-valid') {
    $adminController->updateAppointmentValid();
} else if ($page === 'my-appointments') {
    $appointmentsController->myAppointments();
} else {
    http_response_code(404);
    echo "Page introuvable";
}





