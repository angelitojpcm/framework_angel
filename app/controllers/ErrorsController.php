<?php 
class ErrorsController extends Controller{
    public function __construct($params) {
        parent::__construct($params);
        $this->template = 'default';
    }
    public function index() {
        $this->data['title'] = 'Error';
        $this->renderView('index', $this->data);
    }

    public function error404() {
        $this->data['title'] = 'Error 404';
        $this->renderView('404', $this->data);
    }
}