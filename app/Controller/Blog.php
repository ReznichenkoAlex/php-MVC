<?php

namespace App\Controller;

use App\Model\PostEloquent as Post;
use Base\AbstractController;

class Blog extends AbstractController
{
    public function indexAction()
    {
        if (!$this->user) {
            $this->redirect('/user/register');
        }
        $posts = Post::query()->limit(20)->orderByDesc('id')->get()->toArray();
        return $this->view->render('Blog/index.phtml',
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

            if ($_FILES['userfile']['size']) {
                $fileContent = file_get_contents($_FILES['userfile']['tmp_name']);
                $numbrer = mt_rand(1, 1000);
                $fileLocation = $this->user['name'] . '_image' . $numbrer . '.png';
                file_put_contents(getcwd() . '/images/' . '/posts/' . $this->user['name'] . '_image' . $numbrer . '.png',
                    $fileContent);
                $post->image = $fileLocation;
            }
            $post->save();
            $this->redirect('/blog/index');
        }
    }

    public function deletePostAction()
    {
        $post_id = $_REQUEST['post_id'];
        if ($post_id && in_array($_SESSION['id'], ADMIN_LIST)) {
            $post = Post::find($_REQUEST['post_id']);
            $post->delete();
            $this->redirect('/blog/index');
        }
    }

    public function imageAction()
    {
        header('Content-type: image/png');
        $sanitazePath = htmlspecialchars($_GET['filePath']);
        $data = file_get_contents(getcwd() . '/images/posts/' . $sanitazePath);
        echo $data;
    }


}