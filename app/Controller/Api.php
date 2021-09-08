<?php

namespace App\Controller;

use App\Model\Message;
use Base\AbstractController;

class Api extends AbstractController
{
    public function MessagesAction()
    {
        if (isset($_REQUEST['id'])) {
            $messages = Message::getMessagesByUserId($_REQUEST['id']);
            if(!$messages){
                echo "Этот пользователь не отправлял сообщения";
                return;
            }
            $json = json_encode($messages);
            header("Content-Type: application/json");
            echo $json;
        } else {
            echo "Пользователь не найден ";
        }
    }
}