<?php

namespace Base;

use App\Controller\Blog;
use App\Controller\User;
use App\Model\UserEloquent as UserModel;

class Application
{
    private $route;
    /** @var AbstractController */
    private $controller;
    private $actionName;

    public function __construct()
    {
        $this->route = new Route();
    }

    public function run()
    {
        try{
            session_start();
            $this->addRoutes();
            $this->initController();
            $this->initAction();

            $view = new View();
            $this->controller->setView(view: $view);
            $this->initUser();

            $content = $this->controller->{$this->actionName}();

            echo $content;
        }
        catch (RedirectException $e)
        {
            header('Location: ' . $e->getUrl());
        }
        catch (RouteException $e)
        {
            header("HTTP/1.0 404 Not Found");
            echo $e->getMessage();
        }
    }

    public function initUser()
    {
        $id = $_SESSION['id'] ?? null;
        if ($id){
            $user = UserModel::query()->where('id', $id)->first()->toArray();
            if($user){
                $this->controller->setUser($user);
            }
        }
    }

    private function addRoutes()
    {
        /** @uses \App\Controller\User::LoginAction() */
        $this->route->addRoute('/user/go', User::class, 'login');
        /** @uses \App\Controller\User::RegisterAction() */
        $this->route->addRoute('/user/reg', User::class, 'register');
        /** @uses \App\Controller\Blog::indexAction() */
        $this->route->addRoute('/blog/ix', Blog::class, 'index');
        $this->route->addRoute('/blog', Blog::class, 'index');
    }

    private function initController()
    {
        $controllerName = $this->route->getControllerName();
        if (!class_exists($controllerName)) {
            throw new RouteException('Can\'t find controller' . $controllerName);
        }
        $this->controller = new $controllerName();
    }

    private function initAction()
    {
        $actionName = $this->route->getActionName();

        if (!method_exists($this->controller, $actionName)) {
            throw new RouteException('Action ' . $actionName . ' doesn\'t exist in ' . get_class($this->controller));
        }
        $this->actionName = $actionName;
    }
}