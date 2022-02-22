<?php

require __DIR__ . '/vendor/autoload.php';

/**
 * http://livep2/user/profil => URL
 * user/profil => URI
 * GET => affichage du profil
 */

$router = new AltoRouter();
//Cette route permet de valider l'inscription
$router->map('GET', '/user/register/[*:token]', 'UserController#accountValidation');
//cette route permet d'afficher le formulaire de création de compte
$router->map('GET', '/user/register', 'UserController#getFormRegister');
//route pour valider le formulaire
$router->map('POST', '/user/register', 'UserController#saveUser');
$router->map('GET', '/user/login', 'UserController#getFormLogin');
$router->map('POST', '/user/login', 'UserController#loginUser');
$router->map('GET', '/user/profile', 'UserController#getProfile');

$match = $router->match();

if ($match === false) {
    echo 'Aucune route ne correspond';
} else {
    list($controller, $action) = explode('#', $match['target']);
    // $targetArray = explode( '#', $match['target'] );
    // $controller = $targetArray[0];
    // $action = $targetArray[1];


    if (is_callable(array('\Controller\\' . $controller, $action))) {
        call_user_func_array(array('\Controller\\' . $controller, $action), $match['params']);
    } else {
        // here your routes are wrong.
        // Throw an exception in debug, send a  500 error in production
    }
}
