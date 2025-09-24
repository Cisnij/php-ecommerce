<?php
session_start();

require_once __DIR__ . "/model/Database.php";
require_once __DIR__ . "/model/User.php";
require_once __DIR__ . "/model/Product.php";
require_once __DIR__ . "/controller/site/AuthController.php";
require_once __DIR__ . "/controller/site/HomeController.php";
$pdo = Database::getConnection(); //khởi động database
$userModel = new User($pdo); //truyền kết nối database với pdo vào user
$authController = new AuthController($userModel); //truyền model user vào để xử lý 
$action = $_GET['action'] ?? 'home';
$productsModel =new Product($pdo);
$homeController = new HomeController($productsModel);

switch ($action) {
    case 'login':
        $authController->login();
        break;
    case 'home':
        $homeController->home();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'register':
        $authController->register();
        break;
    default:
        header("Location: /php-pj/index.php?action=home");
        exit;
}