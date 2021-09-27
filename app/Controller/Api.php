<?php

namespace App\Controller;

use App\Model\PostEloquent as Post;
use Base\AbstractController;
use Base\ViewJson;

class Api extends AbstractController
{
    public function MessagesAction()
    {
        $this->setView(new ViewJson());
        $id = $_REQUEST['id'];
        if (is_numeric($id)) {
            $messages = Post::query()
                ->where('user_id', $id)
                ->limit(20)->orderByDesc('id')
                ->get()
                ->toArray();
            if(!$messages){
                return $this->view->render($messages, SAMPLES['errors']['api']['noMessages'] );
            }
            return $this->view->render($messages);
        } else {
            return $this->view->render('', SAMPLES['errors']['api']['notNumeric']);
        }
    }
}