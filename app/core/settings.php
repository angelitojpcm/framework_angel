<?php
/*====================================================
=            Configuración de la aplicación            =
Autor: Angel Calderon Mantilla
Año: 2024
====================================================*/

//Definir el uso horario o timezone del sistema segun el usuario
date_default_timezone_set('America/Bogota');

define('PREPOS', true);

//Languaje
define('LANG', $this->lng);

//Version of apllication
define('ANGEL_NAME', $this->framework);
define('ANGEL_VERSION', $this->version);
// define('SITE_NAME', $this->sitename);   
define('SITE_NAME', 'Angel Framework');
define('SITE_EMAIL', 'angelitojpcmantilla22@gmail.com');
define('SITE_VERSION', '1.0.0');

/*====================================================
=            Constantes de la aplicación            =
====================================================*/

//Port and Url of application
define('PORT', '8848');
define('PROTOCOL', isset($_SERVER['HTTPS']) ? 'https://' : 'http://');
define('HOST', $_SERVER['HTTP_HOST'] === 'localhost' ? (PREPOS ? 'localhost:' . PORT : 'localhost') : $_SERVER['HTTP_HOST']);
define('REQUEST_URI', $_SERVER['REQUEST_URI']);
define('URL', PROTOCOL . '://' . HOST . '/');
define('CUR_PAGE', PROTOCOL . '://' . HOST . REQUEST_URI);


//Routes of directories and files
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', getcwd() . DS);

define('APP', ROOT . 'app' . DS);
define('CLASSES', APP . 'classes' . DS);
define('CONFIG', APP . 'config' . DS);
define('CONTROLLERS', APP . 'controllers' . DS);
define('FUNCTIONS', APP . 'functions' . DS);
define('MODELS', APP . 'models' . DS);
define('LOGS', APP . 'logs' . DS);

define('TEMPLATES', APP . 'templates' . DS);
define('INCLUDES', TEMPLATES . 'includes' . DS);
define('MODULES', TEMPLATES . 'modules' . DS);
define('VIEWS', TEMPLATES . 'views' . DS);

//Route of resources and assets absolute

define('IMAGES_PATH', ROOT . 'assets' . DS . 'images' . DS);

//Rooutes of files or assets whit base url
define('ASSETS', URL . 'assets/');
define('CSS', ASSETS . 'css/');
define('FAVICON', ASSETS . 'favicon/');
define('FONTS', ASSETS . 'fonts/');
define('IMAGES', ASSETS . 'images/');
define('JS', ASSETS . 'js/');
define('PLUGINS', ASSETS . 'plugins/');
define('UPLOADS',  ROOT . 'assets' . DS . 'uploads' . DS);
define('UPLOADED', ASSETS . 'uploads/');

//Crentials of database
define('LDB_ENGINE', 'mysql');
define('LDB_HOST', 'localhost');
define('LDB_NAME', 'angel_framework');
define('LDB_USER', 'root');
define('LDB_PASS', '');
define('LDB_CHARSET', 'utf8');


//Controler for default / the method for default / and controller for error por default
define('DEFAULT_CONTROLLER', 'home');
define('DEFAULT_METHOD', 'index');
define('DEFAULT_ERROR_CONTROLLER', 'error');

