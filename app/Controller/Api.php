<?php

namespace App\Controller;

use App\Model\PostEloquent as Post;
use Base\AbstractController;

class Api extends AbstractController
{
    public function MessagesAction()
    {
        if (isset($_REQUEST['id'])) {
            $messages = Post::query()
                ->where('user_id', $_REQUEST['id'])
                ->limit(20)->orderByDesc('id')
                ->get()
                ->toArray();
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