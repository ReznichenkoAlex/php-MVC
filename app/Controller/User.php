<?php

namespace App\Controller;

use App\Model\UserEloquent as UserModel;
use Base\AbstractController;

class User extends AbstractController
{
    public function loginAction()
    {
        $email = isset($_POST['email']) ? trim($_POST['email']) : null;

        if ($email) {
            $password = isset($_POST['password']) ? trim($_POST['password']) : null;
            $user = UserModel::query()->where('email', $email)->first()->toArray();
            $success = true;
            if (!$password) {
                $this->view->assign('error', SAMPLES['errors']['user']['login']['inputPassword']);
                $success = false;
            }
            if ($success) {
                if (!$user) {
                    $this->view->assign('error', SAMPLES['errors']['user']['login']['incorrectLoginOrPassword']);
                }
                if ($user) {
                    if ($user['password'] !== UserModel::getPasswordHash($password)) {
                        $this->view->assign('error', SAMPLES['errors']['user']['login']['incorrectLoginOrPassword']);
                    } else {
                        $_SESSION['id'] = $user['id'];
                        $this->redirect(SAMPLES['endpoints']['blog']['index']);
                    }
                }
            }
        }
        return $this->view->render(SAMPLES['views']['user']['register']);

    }

    public function registerAction()
    {

        $name = isset($_POST['name']) ? trim($_POST['name']) : null;
        $email = isset($_POST['email']) ? trim($_POST['email']) : null;
        $password = isset($_POST['password']) ? trim($_POST['password']) : null;
        $passwordAgain = isset($_POST['passwordAgain']) ? trim($_POST['passwordAgain']) : null;

        if ($email) {
            $success = true;
            if (!$name) {
                $this->view->assign('error', SAMPLES['errors']['user']['register']['inputName']);
                $success = false;
            }
            if (!$email) {
                $this->view->assign('error', SAMPLES['errors']['user']['register']['inputEmail']);
                $success = false;
            }
            if (!$password) {
                $this->view->assign('error', SAMPLES['errors']['user']['register']['inputPassword']);
                $success = false;
            }
            if (!$passwordAgain ) {
                $this->view->assign('error', SAMPLES['errors']['user']['register']['inputPasswordAgain']);
                $success = false;
            }
            if(strlen($password) < 4){
                $this->view->assign('error', SAMPLES['errors']['user']['register']['shortPasswordLength']);
                $success = false;
            }
            if($password !== $passwordAgain){
                $this->view->assign('error', SAMPLES['errors']['user']['register']['passwordsMustMatch']);
                $success = false;
            }


            $user = UserModel::query()->where('email', $email)->first();
            if($user){
                $this->view->assign('error', SAMPLES['errors']['user']['register']['EmailIsBusy']);
                $success = false;
            }
            if ($success) {
                $userData = [
                    'name' => $name,
                    'email' => $email,
                    'password' => UserModel::getPasswordHash($password)
                ];
                $user = UserModel::create($userData)->toArray();
                $_SESSION['id'] = $user['id'];
                $this->setUser($user);
                $this->redirect(SAMPLES['endpoints']['blog']['index']);
            }
        }

        return $this->view->render(SAMPLES['views']['user']['register']);


    }

    public function logoutAction()
    {
        session_destroy();

        $this->redirect(SAMPLES['endpoints']['user']['login']);
    }
}