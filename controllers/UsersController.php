<?php

require __DIR__ . '/../models/UsersManager.php';
class UsersController {

public function login() {
    require 'views/login.php';
}

public function loginValid() {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $usersManager = new UsersManager();

        if (!$email || !$password) {
            header('Location: index.php?page=login&error=fields');
      
        } else {
            $user = $usersManager->getUser($email);
            if(!$user) {
                header('Location: index.php?page=login&error=credentials');
            } else if (!password_verify($password, $user->getPassword())) 
            {header('Location: index.php?page=login&error=credentials');
               } else {
                   session_start();
                   $_SESSION['user_id'] = $user->getId();
                   $_SESSION['user_name'] = $user->getName();
                   header('Location: index.php?page=home');
               }

        }
}

public function logout(){
    session_destroy();
    header('location: index.php?page=home');
}


public function register() {
    require 'views/register.php';
}

public function registerValid() {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $usersManager = new UsersManager();

    if (!$name || !$email || !$password) {
        header('Location: index.php?page=register&error=fields');
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: index.php?page=register&error=email');
    } else if ($usersManager->emailAlreadyExists($email)) {
        header('Location: index.php?page=register&error=used');
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $usersManager->register($name, $email, $password);
        header('Location: index.php?page=login');
        
    
       
    
 }
}
}
