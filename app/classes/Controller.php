<?php
class Controller extends View{
    protected $view;
    protected $model;
    protected $data = array();
    protected $params;
    protected $template;

    public function __construct($params) {
        $this->params = $params;
        $this->view = new View();
    }

    public function getModel($model) {
        $model = ucfirst($model) . 'Model';
        $modelPath = APP . 'models/' . $model . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            if (class_exists($model)) {
                return new $model();
            }
        }
    }

    public function getParams() {
        return $this->params;
    }

    public function setTemplate($template) {
        $this->template = $template;
    }

    public function renderView($viewName, $data = array(), $template = null) {
        $this->view->render($viewName, $data, $template);
    }
}