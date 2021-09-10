<?php

namespace App\Controller;

use App\Model\User as UserModel;
use Base\AbstractController;

class User extends AbstractController
{
    public function loginAction()
    {
        $email = isset($_POST['email']) ? trim($_POST['email']) : null;

        if ($email) {
            $password = isset($_POST['password']) ? trim($_POST['password']) : null;
            $user = UserModel::getByEmail($email);
            $success = true;
            if (!$password) {
                $this->view->assign('error', 'Введите пароль');
                $success = false;
            }
            if ($success) {
                if (!$user) {
                    $this->view->assign('error', 'Неверный логин или пароль');
                }
                if ($user) {
                    if ($user->getPassword() !== UserModel::getPasswordHash($password)) {
                        $this->view->assign('error', 'Неверный логин или пароль');
                    } else {
                        $_SESSION['id'] = $user->getId();
                        $this->redirect('/blog/index');
                    }
                }
            }
        }

        return $this->view->render('User/register.phtml');

    }

    public function registerAction()
    {

        $name = isset($_POST['name']) ? trim($_POST['name']) : null;
        $email = isset($_POST['email']) ? trim($_POST['email']) : null;
        $password = isset($_POST['password']) ? trim($_POST['password']) : null;
        $passwordAgain = isset($_POST['passwordAgain']) ? trim($_POST['passwordAgain']) : null;

        if (isset($_POST['email'])) {
            $success = true;
            if (!$name) {
                $this->view->assign('error', 'Введите имя');
                $success = false;
            }
            if (!$email) {
                $this->view->assign('error', 'Введите почту');
                $success = false;
            }
            if (!$password) {
                $this->view->assign('error', 'Введите пароль');
                $success = false;
            }
            if (!$passwordAgain ) {
                $this->view->assign('error', 'Введите пароль ещё раз');
                $success = false;
            }
            if(strlen($password) < 4){
                $this->view->assign('error', 'Длина пароля должна быть не менее 4 символов');
                $success = false;
            }
            if($password !== $passwordAgain){
                $this->view->assign('error', 'Пароли должны совпадать');
                $success = false;
            }


            $user = UserModel::getByEmail($email);
            if($user){
                $this->view->assign('error', 'Пользователь с такой почтой уже существует');
                $success = false;
            }

            if ($success) {
                $user = (new UserModel())
                    ->setName($name)
                    ->setEmail($email)
                    ->setPassword(UserModel::getPasswordHash($password));

                $user->save();

                $_SESSION['id'] = $user->getId();
                $this->setUser($user);

                $this->redirect('/blog/index');
            }
        }

        return $this->view->render('User/register.phtml');


    }

    public function logoutAction()
    {
        session_destroy();

        $this->redirect('/user/login');
    }
}