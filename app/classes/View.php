<?php 

class View {

  public static function render($view, $data = [])
  {
    global $app;
    global $d;
    $d = to_object($data); // $data en array assoc o $d en objectos

    if (is_object($d)) {
      $d->app = $app; // Agrega $app al objeto $d
    } else {
      throw new Exception('La conversión a objeto falló.');
    }

    $viewPath = VIEWS . CONTROLLER . DS . $view . 'View.php';
    if(!file_exists($viewPath)) {
      throw new Exception(sprintf('No existe la vista "%sView" en la carpeta "%s".', $view, CONTROLLER));
    }
    include INCLUDES.'header.php';
    include INCLUDES.'components.php';

    // Si el controlador es 'home', incluye el archivo 'slider.php'
    if (CONTROLLER == 'home') {
      include INCLUDES.'slider.php';
    }

    require_once $viewPath;
    require_once INCLUDES.'footer.php';
    exit();
  }

}