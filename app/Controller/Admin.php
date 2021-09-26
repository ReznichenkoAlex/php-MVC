<?php

namespace App\Controller;

use App\Model\UserEloquent as User;
use Base\AbstractController;
use Intervention\Image\ImageManagerStatic as Image;

class Admin extends AbstractController
{
    public function indexAction()
    {
        if (in_array($_SESSION['id'], ADMIN_LIST)) {
            $users = User::all()->toArray();
            return $this->view->renderTwig('Admin/index.twig', ['users' => $users]);
        } else {
            $this->redirect('/blog/index');
        }
    }

    public function profileAction()
    {
        if (in_array($_SESSION['id'], ADMIN_LIST)) {
            $user_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
            if ($user_id) {
                $user = User::query()->where('id', $user_id)->first()->toArray();
                return $this->view->renderTwig('/Admin/profile.twig', ['user' => $user]);
            }
        } else {
            $this->redirect('/blog/index');
        }
    }

    public function updateProfileAction()
    {
        if (in_array($_SESSION['id'], ADMIN_LIST)) {
            $success = true;
            $user = User::query()->where('id', $_POST['user_id'])->first();
            $name = htmlspecialchars(trim($_POST['name']));
            $email = htmlspecialchars(trim($_POST['email']));
            $password = htmlspecialchars(trim($_POST['password']));
            $passwordAgain = htmlspecialchars(trim($_POST['passwordAgain']));
            if(!$name){
                $this->view->assign('error', 'Введите имя');
                $success = false;
            }
            if(!$email){
                $this->view->assign('error', 'Введите почту');
                $success = false;
            }
            if(!$password){
                $this->view->assign('error', 'Введите пароль');
                $success = false;
            }
            if(!$passwordAgain){
                $this->view->assign('error', 'Введите пароль ещё раз');
                $success = false;
            }
            if($password !== $passwordAgain){
                $this->view->assign('error' ,'Пароли не совпадают');
                $success = false;
            }
            if(User::getPasswordHash($password) === $user->password){
                $this->view->assign('error' ,'Новый пароль не должен совпадать со старым');
                $success = false;
            }
            if($_FILES['userfile']['size']){
                $numbrer = mt_rand(1, 1000);
                $image = Image::make($_FILES['userfile']['tmp_name'])->resize(50, 50);
                $image->save(getcwd() . '/images' . '/avatars/' . $user->name . '_avatarSmall_' . $numbrer . '.png');
                $user->avatar_image = $user->name . '_avatarSmall_' . $numbrer . '.png';

            }
            if($success){
                $user->email = $email;
                $user->name = $name;
                $user->password = User::getPasswordHash($password);
                $user->save();
            }
            return $this->view->renderTwig('/Admin/profile.twig', ['user' => $user]);
        } else {
            $this->redirect('/blog/index');
        }

    }

    public function deleteUserAction()
    {
        if (in_array($_SESSION['id'], ADMIN_LIST)) {
            $user = User::query()->where('id', $_POST['user_id'])->first();
            $user->delete();
            $this->redirect('/admin/index');
        } else {
            $this->redirect('/blog/index');
        }
    }

}