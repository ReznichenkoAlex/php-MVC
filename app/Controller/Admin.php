<?php

namespace App\Controller;

use App\Model\UserEloquent as User;
use Base\AbstractController;
use Intervention\Image\ImageManagerStatic as Image;

class Admin extends AbstractController
{
    public function indexAction()
    {
        if ($this->isAdmin()) {
            $users = User::all()->toArray();
            return $this->view->render(SAMPLES['views']['admin']['index'], ['users' => $users]);
        } else {
            $this->redirect(SAMPLES['endpoints']['blog']['index']);
        }
    }

    public function profileAction()
    {
        if ($this->isAdmin()) {
            $user_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
            if ($user_id) {
                $user = User::query()->where('id', $user_id)->first()->toArray();
                return $this->view->render(SAMPLES['views']['admin']['profile'], ['user' => $user]);
            }
        } else {
            $this->redirect(SAMPLES['endpoints']['blog']['index']);
        }
    }

    public function updateProfileAction()
    {
        if ($this->isAdmin()) {
            $success = true;
            $user = User::query()->where('id', $_POST['user_id'])->first();
            $name = htmlspecialchars(trim($_POST['name']));
            $email = htmlspecialchars(trim($_POST['email']));
            $password = htmlspecialchars(trim($_POST['password']));
            $passwordAgain = htmlspecialchars(trim($_POST['passwordAgain']));
            if(!$name){
                $this->view->assign('error', SAMPLES['errors']['admin']['name']);
                $success = false;
            }
            if(!$email){
                $this->view->assign('error', SAMPLES['errors']['admin']['email']);
                $success = false;
            }
            if(!$password){
                $this->view->assign('error', SAMPLES['errors']['admin']['password']);
                $success = false;
            }
            if(!$passwordAgain){
                $this->view->assign('error', SAMPLES['errors']['admin']['passwordAgain']);
                $success = false;
            }
            if($password !== $passwordAgain){
                $this->view->assign('error' ,SAMPLES['errors']['admin']['passwordsDontMatch']);
                $success = false;
            }
            if(User::getPasswordHash($password) === $user->password){
                $this->view->assign('error' ,SAMPLES['errors']['admin']['oldAndNewPasswordsAreSame']);
                $success = false;
            }
            $img = $_FILES;
            if($img['userfile']['size']){
                $numbrer = mt_rand(1, 1000);
                $image = Image::make($img['userfile']['tmp_name'])->resize(50, 50);
                $image->save(getcwd() . '/images' . '/avatars/' . $user->name . '_avatarSmall_' . $numbrer . '.png');
                $user->avatar_image = $user->name . '_avatarSmall_' . $numbrer . '.png';

            }
            if($success){
                $user->email = $email;
                $user->name = $name;
                $user->password = User::getPasswordHash($password);
                $user->save();
            }
            return $this->view->render(SAMPLES['views']['admin']['profile'], ['user' => $user]);
        } else {
            $this->redirect(SAMPLES['endpoints']['blog']['index']);
        }

    }

    public function deleteUserAction()
    {
        if ($this->isAdmin()) {
            $user = User::query()->where('id', $_POST['user_id'])->first();
            $user->delete();
            $this->redirect(SAMPLES['endpoints']['admin']['index']);
        } else {
            $this->redirect(SAMPLES['endpoints']['blog']['index']);
        }
    }

}