# Nombre del Proyecto

Descripción breve del proyecto.

## Estructura del Proyecto

- .htaccess 
- app/ 
    - classes/ 
        - Angel.php 
        - Auth.php 
        - Autoloader.php 
        - Controller.php 
        - Csrf.php 
        - Db.php 
        - Flasher.php 
        - Model.php 
        - PaginationHandler.php 
        - Redirect.php 
        - View.php 
    - composer.json 
    - composer.lock 
    - config/ 
        - angel_config.php 
    - controllers/ 
        - authController.php 
        - homeController.php 
    - core/ 
        - settings.php 
    - functions/ 
        - angel_core_functions.php 
        - angel_custom_functions.php 
    - models/ 
    - vendor/ 
    - assets/ 
        - css/ 
        - favicon/ 
        - img/ 
        - js/ 
    - index.php 
    - prepros.config 
    - templates/ 
        - includes/ 
        - modules/ 
        - views/

## Dependencias

- [PHPMailer](app/vendor/phpmailer/phpmailer/README.md)

## Instalación

1. Clona el repositorio en tu máquina local.
2. Navega al directorio del proyecto.
3. Ejecuta `composer install` para instalar las dependencias del proyecto.
4. Configura tu servidor web para que apunte al directorio `public` del proyecto.
5. Abre el archivo  `app/config/angel_config.php` modifica las configuraciones según tu entorno.

## Uso

Para usar este proyecto, puedes iniciar tu servidor web y visitar la URL de tu aplicación. Las clases principales como [`Angel`](app/classes/Angel.php), [`Auth`](app/classes/Auth.php), y [`Controller`](app/classes/Controller.php) se utilizan para manejar la mayoría de las funcionalidades del proyecto.
## Contribución

Las contribuciones son siempre bienvenidas. Si deseas contribuir al proyecto, puedes seguir estos pasos:

1. Haz un "Fork" del proyecto.
2. Crea tu Feature Branch (`git checkout -b feature/AmazingFeature`).
3. Haz commit de tus cambios (`git commit -m 'Add some AmazingFeature'`).
4. Haz Push al Branch (`git push origin feature/AmazingFeature`).
5. Abre un Pull Request.

Por favor, asegúrate de actualizar los tests según corresponda.

## Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo [`LICENSE`](LICENSE) para más detalles.