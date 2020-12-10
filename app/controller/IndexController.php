<?php
namespace controller;
class IndexController extends Controller
{
    public function index()
    {
        $title = 'home page';
        $data = ['helle', 'world'];

        $this->assign('title', $title);
        $this->assign('data', $data);

        $menu = 'this is a menu';
        $this->assign('menu', $menu);

        $this->display('index.html');
    }
}
