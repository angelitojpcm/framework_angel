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
    private $router;


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
            ['file' => 'bootstrap.min.css', 'comment' => 'Bootstrap'],
            ['file' => 'font-awesome.min.css', 'comment' => 'Font Awesome'],
            ['file' => 'themify-icons.css', 'comment' => 'Themify Icons'],
            ['file' => 'flaticon-set.css', 'comment' => 'Flaticon Set'],
            ['file' => 'elegant-icons.css', 'comment' => 'Elegant Icons'],
            ['file' => 'magnific-popup.css', 'comment' => 'Magnific Popup'],
            ['file' => 'owl.carousel.min.css', 'comment' => 'Owl Carousel'],
            ['file' => 'owl.theme.default.min.css', 'comment' => 'Owl Theme Default'],
            ['file' => 'animate.css', 'comment' => 'Animate'],
            ['file' => 'bootsnav.css', 'comment' => 'Bootsnav'],
            ['file' => 'style.css', 'comment' => 'Style'],
            ['file' => 'responsive.css', 'comment' => 'Responsive'],
            ['file' => 'https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800&display=swap', 'comment' => 'Google'],
          ]);
    }

    /**
     * Método para cargar los scripts
     */

    private function init_scripts(){
        register_scripts([
            ['file' => 'jquery-1.12.4.min.js', 'comment' => 'JQuery'],
            ['file' => 'bootstrap.min.js', 'comment' => 'Bootsrap'],
            ['file' => 'jquery.appear.js', 'comment' => 'Jquery Appear'],
            ['file' => 'jquery.easing.min.js', 'comment' => 'Jquery Easing'],
            ['file' => 'jquery.magnific-popup.min.js', 'comment' => 'Jquery Magnific Popup'],
            ['file' => 'modernizr.custom.13711.js', 'comment' => 'Modernizr'],
            ['file' => 'owl.carousel.min.js', 'comment' => 'Owl Carousel'],
            ['file' => 'wow.min.js', 'comment' => 'Wow'],
            ['file' => 'progress-bar.min.js', 'comment' => 'Progress Bar'],
            ['file' => 'isotope.pkgd.min.js', 'comment' => 'Isotope'],
            ['file' => 'imagesloaded.pkgd.min.js', 'comment' => 'Images Loaded'],
            ['file' => 'count-to.js', 'comment' => 'Count to'],
            ['file' => 'YTPlayer.min.js', 'comment' => 'YTPlayer'],
            ['file' => 'jquery.nice-select.min.js', 'comment' => 'Jquery Nice'],
            ['file' => 'loopcounter.js', 'comment' => 'Loop Counter'],
            ['file' => 'bootsnav.js', 'comment' => 'Bootsnav'],
            ['file' => 'main.js', 'comment' => 'Main'],
            ['file' => 'custom.js', 'comment' => 'Custom'],
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
    private function dispatch() {
        // Filtrar la URL y separar la URI
        $this->filter_url();

        // Comprobar si se ha proporcionado un controlador en la URI
        if(isset($this->uri[0])) {
            $current_controller = $this->uri[0];
            unset($this->uri[0]);
        } else {
            // Si no se ha proporcionado un controlador, usar el controlador por defecto
            $current_controller = DEFAULT_CONTROLLER;
        }

        // Comprobar si la clase del controlador existe
        $controller = $current_controller.'Controller';
        if(!class_exists($controller)) {
            // Si no existe, usar el controlador de errores por defecto
            $current_controller = DEFAULT_ERROR_CONTROLLER;
            $controller = DEFAULT_ERROR_CONTROLLER.'Controller';
            $method = DEFAULT_ERROR_METHOD;
        } else {
            // Comprobar si se ha proporcionado un método en la URI
            if(isset($this->uri[1])) {
                $method = str_replace('-', '_', $this->uri[1]);

                // Comprobar si el método existe en la clase del controlador
                if(!method_exists($controller, $method)) {
                    // Si no existe, usar el controlador y el método de errores por defecto
                    $controller = DEFAULT_ERROR_CONTROLLER.'Controller';
                    $method = DEFAULT_ERROR_METHOD;
                    $current_controller = DEFAULT_ERROR_CONTROLLER;
                }
            } else {
                // Si no se ha proporcionado un método, usar el método por defecto
                $method = DEFAULT_METHOD;
            }
        }

        // Definir constantes para el controlador y el método actuales
        define('CONTROLLER', $current_controller);
        define('METHOD', $method);

        // Crear una nueva instancia del controlador y llamar al método con los parámetros proporcionados
        $params = array_values(empty($this->uri) ? [] : $this->uri);
        $controller = new $controller($params);

        if(empty($params)) {
            call_user_func([$controller, $method]);
        } else {
            call_user_func_array([$controller, $method], $params);
        }
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
