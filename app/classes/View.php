<?php 

class View {

  public static function render($view, $data = [])
  {
    global $d;
    $d = to_object($data); // $data en array assoc o $d en objectos
    $viewPath = VIEWS . CONTROLLER . DS . $view . 'View.php';
    if(!file_exists($viewPath)) {
      throw new Exception(sprintf('No existe la vista "%sView" en la carpeta "%s".', $view, CONTROLLER));
    }
    include INCLUDES.'header.php';
    require_once $viewPath;
    require_once INCLUDES.'footer.php';
    exit();
  }

}

