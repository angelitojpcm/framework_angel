<?php 

class View {

  public static function render($view, $data = [])
  {
    // Convertir el array asociativo en objeto
    $d = to_object($data); // $data en array assoc o $d en objectos

    $viewPath = self::getViewPath($view);

    if(!file_exists($viewPath)) {
      throw new Exception(sprintf('No existe la vista "%sView" en la carpeta "%s".', $view, CONTROLLER));
    }

    require_once $viewPath;
    exit();
  }

  private static function getViewPath($view)
  {
    return VIEWS . CONTROLLER . DS . $view . 'View.php';
  }
}