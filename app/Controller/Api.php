<?php

namespace App\Controller;

use App\Model\PostEloquent as Post;
use Base\AbstractController;

class Api extends AbstractController
{
    public function MessagesAction()
    {
        $id = $_REQUEST['id'];
        if (is_numeric($id)) {
            $messages = Post::query()
                ->where('user_id', $id)
                ->limit(20)->orderByDesc('id')
                ->get()
                ->toArray();
            if(!$messages){
                echo SAMPLES['errors']['api']['noMessages'];
                return;
            }
            $json = json_encode($messages);
            header("Content-Type: application/json");
            echo $json;
        } else {
            echo SAMPLES['errors']['api']['notNumeric'];
        }
    }
}