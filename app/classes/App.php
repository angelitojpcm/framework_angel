<?php

class App
{
    /*====================================================
    Properties of  the framework
    Propiedades del framework
    Build of:
       Angel Calderon Mantilla

    Year: 2024
    Version: 1.0
    ====================================================*/

    private $framework = "Angel Framework";
    private $version = "1.0";
    private $lng = "es";
    private $uri = [];
    private $use_composer = true;


    //Principal function of the framework

    function __construct()
    {
        $this->init();
    }

    /*====================================================
    Método para ejecutar cada "método" de forma subsecuente
    Method for executing each "method" subsequently
    ====================================================*/

    private function init()
    {
        //TODOS LOS METODOS A EJECUTAR CONSECUTIVAMENTE
        $this->init_session();
        $this->init_load_config();
        $this->init_load_functions();
        $this->init_load_composer();
        $this->init_autoload();
        $this->init_styles();
        $this->init_scripts();
        $this->init_csrf();
        $this->init_globals();
        $this->init_custom();
        $this->dispatch();
    }


    /**
     * Inicializa la sesión si no está iniciada.
     *
     * @return void
     */
    private function init_session()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return;
    }

    /**
     * Carga la configuración del framework.
     *
     * @return void
     */

    private function init_load_config()
    {

        //Cargar del archivo settings nicalmente paa establecer las constates personalizadas
        //desde un comienzo en la ejecución del framework


        $file = 'app/config/app_config.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione.', $file, $this->framework));
        }

        require_once $file;

        $file = 'app/core/settings.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione.', $file, $this->framework));
        }

        require_once $file;

        return;
    }

    /**
     * Metodo para cargar las funciones del framework y del usuario
     *
     * @return void
     */

    private function init_load_functions()
    {
        $file = FUNCTIONS . 'app_core_functions.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione.', $file, $this->framework));
        }

        require_once $file;

        $file = FUNCTIONS . 'app_custom_functions.php';

        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione.', $file, $this->framework));
        }

        require_once $file;

        return;
    }

    /**
     * Metodo para cargar las librerias de composer
     *
     * @return void
     */

    private function init_load_composer()
    {
        if (!$this->use_composer) {
            return;
        }

        $file = 'app/vendor/autoload.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione.', $file, $this->framework));
        }

        require_once $file;

        return;
    }

    /**
     * Método para cargar todos los archivos de forma automática
     *
     * @return void
     */

    private function init_autoload()
    {
        require_once CLASSES . 'Autoloader.php';
        Autoloader::init();
        return;
    }

    /**
     * Método para cargar los estilos
     */

    private function init_styles(){
        register_styles([
            ['file' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', 'comment' => 'Bootstrap CDN'],
          ]);
    }

    /**
     * Método para cargar los scripts
     */
     
    private function init_scripts(){
        register_scripts([
            ['file' => 'https://code.jquery.com/jquery-3.6.0.min.js', 'comment' => 'jQuery'],
            ['file' => 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', 'comment' => 'Popper.js'],
            ['file' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', 'comment' => 'Bootstrap'],
        ]);
    }

    /**
     * Método para cargar el token CSRF
     *
     * @return void
     */

    private function init_csrf()
    {
        $csrf = new Csrf();
        define('CSRF_TOKEN', $csrf->get_token());
    }

    /**
     * Método para cargar las variables globales
     *
     * @return void
     */

    private function init_globals()
    {
        // Objeto Angel que será insertado en el footer como script javascript dinámico para fácil acceso
        app_obj_default_config();
    }

    /**
     * Usado para carga de procesos personalizados del sistema
     * funciones, variables, set up
     *
     * @return void
     */

    private function init_custom()
    {
        // Inicializar procesos personalizados del sistema o aplicación
        // ........
    }

    /**
     * Método para filtrar y descomponer los elementos de nuestra url y uri
     *
     * @return void
     */

    private function filter_url()
    {
        if (isset($_GET['uri'])) {
            $this->uri = $_GET['uri'];
            $this->uri = rtrim($this->uri, '/');
            $this->uri = filter_var($this->uri, FILTER_SANITIZE_URL);
            $this->uri = explode('/', strtolower($this->uri));
            return $this->uri;
        }
    }

    /**
     * Método para cargar el controlador y el método
     * de la url
     * @return void
     */
    private function dispatch()
    {
        $this->filter_url();

        //Nececitamos saber si esta psando el nombe de un controlador de URI 
        //$this->uri[0] es el controlador en cuestion
        if (isset($this->uri[0])) {
            $current_controller = $this->uri[0];
            unset($this->uri[0]);
        } else {
            $current_controller = DEFAULT_CONTROLLER;
        }


        //Ejecutar el controlador
        //Verificar si el controlador existe
        $controller = $current_controller . 'Controller';
        if (!class_exists($controller)) {
            $current_controller = DEFAULT_ERROR_CONTROLLER;
            $controller = DEFAULT_CONTROLLER . 'Controller';
        }

        //Instanciar el metodo del controlador solicitado

        if (isset($this->uri[1])) {
            $method = str_replace('-', '_', $this->uri[1]); //Reemplazar guiones por guiones bajos

            //Verificar si el metodo existe dentro de la clase a ejecutar (controlador)
            if (!method_exists($controller, $method)) {
                $controller = DEFAULT_ERROR_CONTROLLER . 'Controller';
                $current_method = DEFAULT_METHOD;
                $current_controller = DEFAULT_ERROR_CONTROLLER;
            } else {
                $current_method = $method;
            }
            unset($this->uri[1]);
        } else {
            $current_method = DEFAULT_METHOD;
        }


        //Creando constantes para usar mas adelante
        define('CONTROLLER', $current_controller);
        define('METHOD', $current_method);

        //Obteniendo los parametros de la url
        $params = array_values(($this->uri) ? [] : $this->uri);

        // Instanciando el controlador con los parámetros
        $controller = new $controller($params);

        //LLama al metodo que el usuario solicita
        if (empty($params)) {
            call_user_func(array($controller, $current_method));
        } else {
            call_user_func_array(array($controller, $current_method), $params);
        }

        return;
    }

    /**
     * Iniciar el framework
     *
     * @return string
     */

    public static function run()
    {
        $angel =  new self();
        return;
    }
}
