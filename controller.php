<?php

require __DIR__ . '/vendor/autoload.php';

/**
 * http://livep2/user/profil => URL
 * user/profil => URI
 * GET => affichage du profil
 */

$router = new AltoRouter();
$router->map( 'GET', '/user/register', 'UserController#saveUser' );

$match = $router->match();

if ($match === false) {
    echo 'Aucune route ne correspond';
} else {
    list( $controller, $action ) = explode( '#', $match['target'] );
    // $targetArray = explode( '#', $match['target'] );
    // $controller = $targetArray[0];
    // $action = $targetArray[1];
    
    
    if ( is_callable(array('\Controller\\' . $controller, $action)) ) {
        call_user_func_array(array('\Controller\\' .$controller,$action), array($match['params']));
    } else {
        // here your routes are wrong.
        // Throw an exception in debug, send a  500 error in production
    }
}


