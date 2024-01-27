<?php
class AuthController extends Controller {

    protected $auth;
    protected $data;

    public function __construct($params, Auth $auth) {
        parent::__construct($params);
        $this->auth = $auth;

        // Establece 'bg-gradient' por defecto
        $this->data['bg'] = 'bg-gradient';
    }

    private function mergeData($data) {
        return array_merge($this->data, $data);
    }

    public function login() {
        $data =
        [
            'title' => 'Iniciar sesión',
            'auth'  => $this->auth->validate()
        ];

        // Fusiona $data con los datos por defecto
        $data = $this->mergeData($data);

        if(!$this->auth->validate()) {
            $this->view->render('login', $data);
            return;
        }
        echo $this->auth->validate();
        Redirect::to('home');
    }

    public function register() {
        // Aquí puedes usar $this->auth para acceder a los métodos de Auth
        // ...

        $data =
        [
            'title' => 'Registrarse',
        ];

        // Fusiona $data con los datos por defecto
        $data = $this->mergeData($data);

        $this->view->render('register', $data);
    }
}