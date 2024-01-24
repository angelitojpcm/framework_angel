<?php 

/**
 * Clase para redireccionar al usuario a diferentes secciones del sitio web.
 */
class Redirect
{
    private $location;

    /**
     * Método para redirigir al usuario a una sección determinada.
     *
     * @param string $location La ubicación a la que se debe redirigir al usuario.
     * @return void
     */
    public static function to($location)
    {
        $self = new self();
        $self->location = $location;

        // Si las cabeceras ya fueron enviadas
        if (headers_sent()) {
            echo '<script type="text/javascript">';
            echo 'window.location.href="'.URL.$self->location.'";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url='.URL.$self->location.'" />';
            echo '</noscript>';
            die();
        } 

        // Cuando pasamos una URL externa a nuestro sitio
        if (strpos($self->location, 'http') !== false) {
            header('Location: '.$self->location);
            die();
        }

        // Redirigir al usuario a otra sección
        header('Location: '.URL.$self->location);
        die();
    }

    /**
     * Redirige de vuelta a la URL previa.
     *
     * @param string $location La ubicación a la que se debe redirigir al usuario.
     * @return void
     */
    public static function back($location = '')
    {
        if(!isset($_POST['redirect_to']) && !isset($_GET['redirect_to']) && $location == ''){
            header('Location: '.URL.DEFAULT_CONTROLLER);
            die();
        }

        if(isset($_POST['redirect_to'])){
            header('Location: '.$_POST['redirect_to']);
            die();
        }

        if(isset($_GET['redirect_to'])){
            header('Location: '.$_GET['redirect_to']);
            die();
        }

        if(!empty($location)){
            self::to($location);
        }
    }
}