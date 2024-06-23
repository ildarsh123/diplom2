<?php

namespace App;

use App\UserController;
use League\Plates\Engine;
use App\StorageController;
use \Tamtamchik\SimpleFlash\Flash;


class HomeController
{

    private $templates;

    private $user;

    private $flash;
    private $file;
    public function __construct(Engine $engine, UserController $user, Flash $flash, StorageController $file)
    {
        $this->templates = $engine;
        $this->user = $user;
        $this->flash = $flash;
        $this->file = $file;
    }

    public function index(){

        if ($this->user->isNotLoggedIn()) {

            header("Location: /welcome/marlindev/diplom2/public/login");
            die();
        }

        $users = $this->user->getAllUsers();
        $currentUserId = $this->user->getUserId();
        $admin = $this->user->isUserAdmin();

        echo $this->templates->render('users', ['users' => $users, 'admin' => $admin, 'currentUserId' => $currentUserId]);


    }

    public function login(){
        $page = include __DIR__.'/../views/page_login.php';


    }


    public function page_register(){
        $page = include __DIR__.'/../views/page_register.php';


    }

    public function page_profile($id){

        if ($this->user->isNotLoggedIn()) {
            header("Location: /welcome/marlindev/diplom2/public/login");
            die();
        }

        $currentUserId = $this->user->getUserId();


        $user = $this->user->getUserDataById($id['id']);
        echo $this->templates->render('page_profile', [ 'user' => $user]);


    }



    public function edit($id){

        if ($this->user->isNotLoggedIn()) {
            header("Location: /welcome/marlindev/diplom2/public/login");
            die();
        }

        $currentUserId = $this->user->getUserId();

        if((!$this->user->isUserAdmin())) {
            if(!($id['id'] == $currentUserId)) {
                Flash::message('Только админ может посещать данную странмцу', $type = 'error');
                header("Location: /welcome/marlindev/diplom2/public/");
                die();
            }
        }

        $id = $id['id'];
        $user = $this->user->getUserDataById($id);
        echo $this->templates->render('edit', [ 'user' => $user, 'id'=>$id]);


    }


    public function create_user(){

        if ($this->user->isNotLoggedIn()) {
            header("Location: /welcome/marlindev/diplom2/public/login");
            die();
        }


        if((!$this->user->isUserAdmin())) {
                Flash::message('Только админ может посещать данную странмцу', $type = 'error');
                header("Location: /welcome/marlindev/diplom2/public/");
                die();
        }



        echo $this->templates->render('create_user');


    }

    public function createNewUser() {
        if ($this->user->isNotLoggedIn()) {
            header("Location: /welcome/marlindev/diplom2/public/login");
            die();
        }


        if((!$this->user->isUserAdmin())) {
            Flash::message('Только админ может посещать данную странмцу', $type = 'error');
            header("Location: /welcome/marlindev/diplom2/public/");
            die();
        }

        $created_user_id = $this->user->createNewUser($_POST['email'], $_POST['password']);

        $data = [                     // insert these columns and bind these values
            'user_id' => $created_user_id,
            'name' => $_POST['name'],
            'status' => $_POST['status'],
            'profession' => $_POST['profession'],
            'tel' => $_POST['tel'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
            'tags' => $_POST['name'],
        ];
        $this->user->addNewUserInfo($data);

        if(!empty($_FILES['image'])) {
            $this->file->setImage($created_user_id);
        }

        Flash::message('Пользователь успешно добавлен', $type = 'success');
        header('Location:/welcome/marlindev/diplom2/public/');
        die();

    }


    public function security($id) {

        if ($this->user->isNotLoggedIn()) {
            header("Location: /welcome/marlindev/diplom2/public/login");
            die();
        }

        $currentUserId = $this->user->getUserId();

        if((!$this->user->isUserAdmin())) {
            if(!($id['id'] == $currentUserId)) {
                Flash::message('Только админ может посещать данную странмцу', $type = 'error');
                header("Location: /welcome/marlindev/diplom2/public/");
                die();
            }
        }

        $id = $id['id'];
        $user = $this->user->getUserDataById($id);
        echo $this->templates->render('security', [ 'user' => $user, 'id'=>$id]);

    }





    public function status($id) {

        if ($this->user->isNotLoggedIn()) {
            header("Location: /welcome/marlindev/diplom2/public/login");
            die();
        }

        $currentUserId = $this->user->getUserId();

        if((!$this->user->isUserAdmin())) {
            if(!($id['id'] == $currentUserId)) {
                Flash::message('Только админ может посещать данную странмцу', $type = 'error');
                header("Location: /welcome/marlindev/diplom2/public/");
                die();
            }
        }

        $id = $id['id'];
        $user = $this->user->getUserDataById($id);
        echo $this->templates->render('status', [ 'user' => $user, 'id'=>$id]);

    }


    public function media($id) {

        if ($this->user->isNotLoggedIn()) {
            header("Location: /welcome/marlindev/diplom2/public/login");
            die();
        }

        $currentUserId = $this->user->getUserId();

        if((!$this->user->isUserAdmin())) {
            if(!($id['id'] == $currentUserId)) {
                Flash::message('Только админ может посещать данную странмцу', $type = 'error');
                header("Location: /welcome/marlindev/diplom2/public/");
                die();
            }
        }

        $id = $id['id'];
        $user = $this->user->getUserDataById($id);
        echo $this->templates->render('media', [ 'user' => $user, 'id'=>$id]);

    }

    public function changeMedia($user_id) {

        if ($this->user->isNotLoggedIn()) {
            header("Location: /welcome/marlindev/diplom2/public/login");
            die();
        }

        $currentUserId = $this->user->getUserId();

        if((!$this->user->isUserAdmin())) {
            if(!($user_id['id'] == $currentUserId)) {
                Flash::message('Только админ может посещать данную странмцу', $type = 'error');
                header("Location: /welcome/marlindev/diplom2/public/");
                die();
            }
        }


        $user_id = $user_id['id'];
        $this->file->deleteImageFile($user_id);
        $this->file->setImage($user_id);


        Flash::message('Рисунок пользователя изменен', $type = 'success');
        header('Location:/welcome/marlindev/diplom2/public');

    }







}