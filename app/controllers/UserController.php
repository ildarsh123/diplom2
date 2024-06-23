<?php
namespace App;
use Delight\Auth\Role;
use PDO;
use Delight\Auth\Auth;
use \Tamtamchik\SimpleFlash\Flash;

class UserController
{
    private $pdo;
    private $auth;
    private $qb;
    private $flash;

    public function __construct(PDO $pdo, QueryBuilder $qb, Flash $flash)
    {
        $this->pdo = $pdo;
        $this->qb = $qb;
        $this->auth = new Auth($this->pdo);
        $this->flash= $flash;
    }

    public function login_user(){
        try {
            $this->auth->login($_POST['email'], $_POST['password']);
            header('Location:/welcome/marlindev/diplom2/public/');
            die();
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            Flash::message('Wrong email or password address', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/login');
            die();

        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            Flash::message('Wrong email or password address', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/login');
            die();
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            Flash::message('Wrong email or password address', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/login');
            die();
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            Flash::message('Wrong email or password address', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/login');
            die();
        }



    }

    public function logoutUser()
    {
        $this->auth->logOut();

        header('Location:/welcome/marlindev/diplom2/public/login');
        die();


    }

    public function register_user(){
        try {$this->auth->register($_POST['email'], $_POST['password']);
            Flash::message('Регистрация успешна', $type = 'success');
            $this->login_user();

        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            Flash::message('Invalid email address', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/register');
            die();
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            Flash::message('Invalid password', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/register');
            die();
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            Flash::message('User already exists', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/register');
            die();
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            Flash::message('Too many requests', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/register');
            die();
        }
    }


    public function isLoggedIn(){
        return $this->auth->isLoggedIn();
    }

    public function isNotLoggedIn(){
        return !$this->auth->isLoggedIn();
    }


    public function getAllUsers()
    {
       return $this->qb->getAllUsers();

    }

    public function setUserAsAdmin() {
        try {
            $this->auth->admin()->addRoleForUserById('1', Role::ADMIN);
            echo 'Done';
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            die('Unknown user ID');
        }
    }

    public function getUserId() {
        return $this->auth->getUserId();
    }

    public function isUserAdmin(){
        return $this->auth->hasRole(Role::ADMIN);
    }


    public function getUserDataById($id)
    {
        return $this->qb->getUserDataById($id);

    }

    public function createNewUser($email, $password) {

        try {
            $userId = $this->auth->admin()->createUser($email, $password);

            return  $userId;


        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            die('Invalid email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('User already exists');
        }

    }


    public function addNewUserInfo($data) {

        $this->qb->insertUserInfo('users_data', $data);

    }

    public function edit_user ($user_id) {
        $user_id = $user_id['id'];
        $data = [                     // insert these columns and bind these values

            'name' => $_POST['name'],
            'profession' => $_POST['profession'],
            'tel' => $_POST['tel'],
            'address' => $_POST['address'],

        ];

        $this->qb->editUserInfo('users_data', $user_id,  $data);

        Flash::message('Данные пользователя изменены', $type = 'success');
        header('Location:/welcome/marlindev/diplom2/public/');
        die();

    }

    public function deleteUser($id) {
        if ($this->isNotLoggedIn()) {
            header("Location: /welcome/marlindev/diplom2/public/login");
            die();
        }


        $user_id = $id['id'];
        if ($user_id == $this->getUserId()) {

            $this->qb->deleteUserInfo('users_data',$user_id);
            $this->qb->deleteUser('users',$user_id);


            $this->logoutUser();
            header("Location: /welcome/marlindev/diplom2/public/login");
            die();

        }


        if(($this->isUserAdmin())) {
            try {
                $this->qb->deleteUserInfo('users_data',$user_id);
                $this->auth->admin()->deleteUserById($user_id);
                Flash::message('Пользователь удален', $type = 'success');
                header("Location: /welcome/marlindev/diplom2/public/");
                die();
            }
            catch (\Delight\Auth\UnknownIdException $e) {
                Flash::message('Не найден id', $type = 'error');
                header("Location: /welcome/marlindev/diplom2/public/");
                die();
            }



        }


        header("Location: /welcome/marlindev/diplom2/public/");
        die();


    }

    public function changeStatus($user_id)
    {

        if ($this->isNotLoggedIn()) {
            header("Location: /welcome/marlindev/diplom2/public/login");
            die();
        }

        $currentUserId = $this->getUserId();

        if((!$this->isUserAdmin())) {
            if(!($user_id['id'] == $currentUserId)) {
                Flash::message('Только админ может посещать данную странмцу', $type = 'error');
                header("Location: /welcome/marlindev/diplom2/public/");
                die();
            }
        }


        $user_id = $user_id['id'];
        $user = $this->qb->getUserDataById($user_id);
        $status_old = $user['status'];

        if($_POST['status'] == $status_old) {
            Flash::message('Статус пользователя изменен', $type = 'success');
            header('Location:/welcome/marlindev/diplom2/public');
        } else {
            $data = [                     // insert these columns and bind these values

                'status' => $_POST['status'],
            ];
            $this->qb->editUserInfo('users_data', $user_id,  $data);
            Flash::message('Статус пользователя изменен', $type = 'success');
            header('Location:/welcome/marlindev/diplom2/public');
        }



    }

    private function changeUsersEmail($email) {
        try {
            $this->auth->changeEmail($email, function ($selector, $token) {
                //echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email to the *new* address)';
                echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
                echo '  For SMS, consider using a third-party service and a compatible SDK';
            }) ;
            Flash::message('Почта пользователя изменена', $type = 'success');
            header('Location:/welcome/marlindev/diplom2/public/');
            die();

        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            Flash::message('Invalid email address', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/');
            die();

        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            Flash::message('Email address already exists', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/');
            die();

        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            Flash::message('Account not verified', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/');
            die();

        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            Flash::message('Not logged in', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/');
            die();

        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            Flash::message('Too many requests', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/');
            die();

        }
    }

    private function changeUsersPassword() {
        try {
            $this->auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);
            Flash::message('Password has been changed', $type = 'success');
            header('Location:/welcome/marlindev/diplom2/public/');
            die();

        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            Flash::message('Not logged in', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/');
            die();

        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            Flash::message('Invalid password(s)', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/');
            die();

        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            Flash::message('Too many requests', $type = 'error');
            header('Location:/welcome/marlindev/diplom2/public/');
            die();

        }
    }

    public function changeSecurity($user_id) {

        if ($this->isNotLoggedIn()) {
            header("Location: /welcome/marlindev/diplom2/public/login");
            die();
        }

        $currentUserId = $this->getUserId();

        if((!$this->isUserAdmin())) {
            if(!($user_id['id'] == $currentUserId)) {
                Flash::message('Только админ может посещать данную странмцу', $type = 'error');
                header("Location: /welcome/marlindev/diplom2/public/");
                die();
            }
        }
        $user_id=$user_id['id'];
        $user = $this->getUserDataById($user_id);

        if(!empty($_POST['newEmail'])) {
            if($user['email'] != $_POST['newEmail']) {
                $this->changeUsersEmail($_POST['newEmail']);
            }
        }

        if(!empty($_POST['newPassword']) && !empty($_POST['newPassword2']) && !empty($_POST['oldPassword'])) {
            if($_POST['newPassword'] == $_POST['newPassword2']) {
                $this->changeUsersPassword();
            }

        } else {

            Flash::message('Попробуйте еще раз', $type = 'error');
            header("Location: /welcome/marlindev/diplom2/public/");
            die();

        }








    }







}