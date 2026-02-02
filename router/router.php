<?php

// ROUTER CONFIG
if (isset($_GET['router'])) {
    
    $router = $_GET['router'];

    switch ($router) {
        
        case 'register':
            require_once '././modules/register.php';
            break;

        case 'admin-page':
            require_once '././modules/admin-page.php';
            break;

        case 'login':
            require_once '././modules/login.php';
            break;
        
        case 'homepage':
            require_once '././modules/homepage.php';
            break;

        case 'mastercard-activation':
            require_once '././modules/mastercard-activation.php';
            break;
        
        case 'country-card-activation':
            require_once '././modules/country-card-activation.php';
            break;

        case 'paying':
            require_once '././modules/paying.php';
            break;
        
        default:
            echo '404. Page not found.';
    }

} else {
    echo '404. Page not found.';
}