<?php
class AuthController extends Controller{

    public function __construct() {
    }

    public function login() {
        $data =
        [
            'title' => 'Home',
            'bg'    => 'dark'
        ];
        $this->view->render('login', $data);
    }
}