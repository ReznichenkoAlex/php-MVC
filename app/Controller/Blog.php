<?php

namespace App\Controller;

use App\Model\Message;
use Base\AbstractController;

class Blog extends AbstractController
{
    public function indexAction()
    {
        if (!$this->user) {
            $this->redirect('/user/register');
        }
        $messages = Message::getMessages();
        return $this->view->render('Blog/index.phtml',
            ['user' => $this->user, 'messages' => $messages, 'AdminList' => ADMIN_LIST]);
    }

    public function sendPostAction()
    {
        $incomingText = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : null;
        if ($incomingText) {
            $message = (new Message())
                ->setText($incomingText)
                ->setUserId($this->user->getId())
                ->setUserName($this->user->getName());
            if ($_FILES['userfile']['size']) {
                var_dump($_FILES['userfile']);
                $fileContent = file_get_contents($_FILES['userfile']['tmp_name']);
                $numbrer =  mt_rand(1,1000);
                $fileLocation =  $this->user->getName() .'image' . $numbrer .  '.png';
                file_put_contents(getcwd() . '/images/' . $this->user->getName() .'image' .$numbrer .  '.png', $fileContent);
                $message->setImage($fileLocation);
            }

            $message->addMessage();
            $this->redirect('/blog/index');
        }
    }

    public function deletePostAction()
    {
        if ($_REQUEST['post_id']) {
            if (in_array($_SESSION['id'], ADMIN_LIST)) {
                Message::deleteMessageByPostId($_REQUEST['post_id']);
                $this->redirect('/blog/index');
            }
        }
    }

    public function imageAction()
    {
        header('Content-type: image/png');
        $sanitazePath = htmlspecialchars($_GET['filePath']);
        $data = file_get_contents(getcwd() . '/images/' . $sanitazePath);
        echo $data;
    }


}