<?php

namespace App;

class StorageController
{
    private $qb;
    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }

    public function deleteImageFile($id) {


        $user = $this->qb->getUserDataById($id);
        $path = __DIR__.'/../views/img/demo/avatars/';
        if (file_exists($path . $user['image'])) {
            unlink($path . $user['image']);
        }





    }

    public function setImage($id) {

        $filetype = pathinfo($_FILES['image']['name']);
        //d($filetype);die;
        $filename = uniqid() .'.'. $filetype['extension'];
        $path = __DIR__.'/../views/img/demo/avatars/';

        move_uploaded_file($_FILES['image']['tmp_name'], $path . $filename);
        $data = [                     // insert these columns and bind these values
            'image' => $filename,
        ];
        $this->qb->editUserInfo('users_data', $id, $data);

    }


}