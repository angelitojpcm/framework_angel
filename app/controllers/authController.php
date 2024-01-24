<?php
class AuthController extends Controller{

    public function __construct($params) {
        parent::__construct($params);
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