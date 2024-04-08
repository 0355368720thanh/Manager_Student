<?php
// localhost/management-student/index.php?c=login&m=index
// c = ten cua controller nam trong thu muc controller
$c = trim($_GET['c'] ?? 'login'); // controller mac dinh la login
$c = ucfirst($c); // viet hoa chu cai dau

switch ($c) {
    case 'Login':
        require "controller/LoginController.php";
        break;
    case 'Dashboard':
        require "controller/DashboardController.php";
        break;
    case 'Department':
        require "controller/DepartmentController.php";
        break;
    case 'Courses':
        require "controller/CoursesController.php";
        break;
    case 'Account':
        require "controller/AccountsController.php";
        break;
    case 'Users':
        require "controller/UsersController.php";
        break;
    default:
        require "controller/LoginController.php";
        break;
}
