<?php

namespace App\Controller;

use App\Model\PostEloquent as Post;
use Base\AbstractController;

class Blog extends AbstractController
{
    public function indexAction()
    {
        if (!$this->user) {
            $this->redirect(SAMPLES['endpoints']['user']['register']);
        }
        $posts = Post::query()->limit(20)->orderByDesc('id')->get()->toArray();
        return $this->view->render(SAMPLES['views']['blog']['index'],
            ['user' => $this->user, 'messages' => $posts, 'AdminList' => ADMIN_LIST]);
    }

    public function sendPostAction()
    {
        $incomingText = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : null;
        if ($incomingText) {
            $post = new Post();
            $post->text = $incomingText;
            $post->user_id = $this->user['id'];
            $post->user_name = $this->user['name'];

            $img = $_FILES;
            if ($img['userfile']['size']) {
                $fileContent = file_get_contents($img['userfile']['tmp_name']);
                $numbrer = mt_rand(1, 1000);
                $fileLocation = $this->user['name'] . '_image' . $numbrer . '.png';
                file_put_contents(getcwd() . '/images/' . '/posts/' . $this->user['name'] . '_image' . $numbrer . '.png',
                    $fileContent);
                $post->image = $fileLocation;
            }
            $post->save();

        }
        $this->redirect(SAMPLES['endpoints']['blog']['index']);
    }

    public function deletePostAction()
    {
        $post_id = $_REQUEST['post_id'];
        if ($post_id && $this->isAdmin()) {
            $post = Post::find($post_id);
            $post->delete();
            $this->redirect(SAMPLES['endpoints']['blog']['index']);
        }
    }

    public function imageAction()
    {
        header('Content-type: image/png');
        $filePath = $_GET['filePath'];
        $sanitazePath = htmlspecialchars($filePath);
        $data = file_get_contents(getcwd() . '/images/posts/' . $sanitazePath);
        echo $data;
    }


}