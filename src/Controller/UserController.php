<?php

namespace Controller;

use Exception;
use Model\User;
use Utils\Str;

class UserController
{

    public static function getFormRegister()
    {
        include 'src/View/Parts/header.php';
        include 'src/View/register.php';
        include 'src/View/Parts/footer.php';
    }

    public static function saveUser()
    {
        include 'src/View/Parts/header.php';
        if (isset($_POST['saveUser'])) {
            $formError = [];
            $user = new User;
            if (!empty($_POST['mail'])) {
                if (filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
                    $user->setMail(htmlspecialchars($_POST['mail']));
                } else {
                    $formError['mail'] = 'L\'adresse mail n\'est pas valide.';
                }
            } else {
                $formError['mail'] = 'L\'adresse mail ne doit pas être vide.';
            }

            if (!empty($_POST['password'])) {
                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $user->setPassword($passwordHash);
            } else {
                $formError['password'] = 'Le mot de passe ne doit pas être vide.';
            }

            if (count($formError) == 0) {
                $user->setTokenregister(Str::getRandom());
                if ($user->saveUser()) {
                    echo '<a href="http://livep2/user/register/' . $user->getTokenregister() . '">Merci de cliquer ici pour valider votre inscription</a>';
                    $to = $user->getMail();
                    $subject = 'LiveP2 : Confirmation de l\'inscription';
                    $message = '<html>
                    <head><title>LiveP2 : Confirmation de l\'inscription</title></head>
                    <body><h1>Bienvenue chez nous</h1>
                    <p>Il ne reste plus qu\'une étape pour nous rejoindre et vivre de grandes aventures</p>
                    <a href="http://livep2/user/register/' . $user->getTokenregister() . '">Clique ici</a></body>
                    </html>';
                    // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
                    $headers[] = 'MIME-Version: 1.0';
                    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                    $headers[] = 'From: LiveP2 <mcikael@adif-info.fr>';
                    mail($to, $subject, $message, implode("\r\n", $headers));
                }
            }
        } else {
            echo 'je ne gère pas ce formulaire';
        }

        include 'src/View/Parts/footer.php';
    }

    public static function accountValidation($token)
    {
        try {
            $user = new User;
            $user->setTokenregister($token);
            if ($user->checkTokenRegister()) {
                $user->deleteToken();
            } else {
                //j'informe l'user que son token est pas bon
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public static function getFormLogin()
    {
        include 'src/View/Parts/header.php';
        include 'src/View/login.php';
        include 'src/View/Parts/footer.php';
    }

    public static function loginUser()
    {
        include 'src/View/Parts/header.php';
        if (isset($_POST['loginUser'])) {
            $formError = [];
            $user = new User;
            if (!empty($_POST['mail'])) {
                if (filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
                    $user->setMail($_POST['mail']);
                } else {
                    $formError['mail'] = 'L\'adresse mail n\'est pas valide.';
                }
            } else {
                $formError['mail'] = 'L\'adresse mail ne doit pas être vide.';
            }

            if (!empty($_POST['password'])) {
                $password = $_POST['password'];
            } else {
                $formError['password'] = 'Le mot de passe ne doit pas être vide.';
            }

            if (count($formError) == 0) {
                $loginInfo = $user->getLoginInfo();
                if(password_verify($password,$loginInfo->password)){
                    if($loginInfo->tokenregister == null){
                        //Ici je suis connecté
                        $_SESSION['user']['mail'] = $_POST['mail'];
                        header('Location: /user/profile');
                        exit;
                    }else{
                        echo 'Merci de valider votre mail';
                        include 'src/View/login.php';
                    }
                }else{
                    echo 'les identifiants ne sont pas bon';
                }

            } else {
                echo 'je ne gère pas ce formulaire';
            }

            include 'src/View/Parts/footer.php';
        }
    }

    public static function getProfile(){
        include 'src/View/Parts/header.php';
        include 'src/View/Parts/footer.php';
    }
}
