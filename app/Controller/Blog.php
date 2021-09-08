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
        return $this->view->render('Blog/index.phtml', ['user' => $this->user, 'messages' => $messages, 'AdminList' => ADMIN_LIST]);
    }

    public function sendPostAction()
    {
        $incomingText = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : null;
        // загрузка картинки
        if ($incomingText) {
            $message = (new Message())
                ->setText($incomingText)
                ->setUserId($this->user->getId())
                ->setUserName($this->user->getName());
            if ($_FILES['userfile']['size']) {
                $fileContent = file_get_contents($_FILES['userfile']['tmp_name']);
                $fileLocation = getctwd() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . time() . '.png';
                file_put_contents($fileLocation, $fileContent);
                header('Content-type: image/png');
                $message->setImage($fileLocation);
            }

            $message->addMessage();
            $this->redirect('/blog/index');
        }
    }

    public function deletePostAction()
    {
        if($_REQUEST['post_id']){
            echo $_REQUEST['post_id'];
            Message::deleteMessageByPostId($_REQUEST['post_id']);
            $this->redirect('/blog/index');
        }
    }


}