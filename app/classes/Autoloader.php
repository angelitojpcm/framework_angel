<?php

class Autoloader
{
    /**
     * Método encargado de ejecutar el autocargador de forma estática
     *
     * @return void
     */
    public static function init()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * Método encargado de cargar las clases de forma automática
     *
     * @param [type] $class
     * @return void
     */

    private static function autoload($class)
    {
        if (is_file(CLASSES . $class . '.php')) {
            require_once CLASSES . $class . '.php';
        } elseif (is_file(CONTROLLERS . $class . '.php')) {
            require_once CONTROLLERS . $class . '.php';
        } elseif (is_file(MODELS . $class . '.php')) {
            require_once MODELS . $class . '.php';
        }

        return;
    }
}
