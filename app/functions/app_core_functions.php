<?php
/*====================================================
=            Funciones  de la aplicación            =
Autor: Angel Calderon Mantilla
Año: 2024
====================================================*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception AS EmailException;

/**
 * Convierte el elemento en objeto
 *
 * @param [type] $array
 * @return void
 */

function to_object($array)
{
  return json_decode(json_encode($array));
}

/**
 * Regresa el nombre de nuestra aplicación
 * 
 * @return string
 */
function get_sitename()
{
  return SITE_NAME;
}

/**
 * Regresa la versión de nuestra aplicación
 * 
 * @return string
 */
function get_version()
{
  return SITE_VERSION;
}

/**
 * Regresa el nombre del framework
 * 
 * @return string
 */

function get_app_name()
{
  return APP_NAME;
}

/**
 * Regresa la versión del framework
 * 
 * @return string
 */
function get_app_version()
{
  return APP_VERSION;
}

/**
 * Devuelve el email general de la aplicación
 * 
 * @return string
 */
function get_siteemail()
{
  return SITE_EMAIL;
}

/**
 * Regresa la fecha actual 
 * 
 * @return string
 */

function now()
{
  return date('Y-m-d H:i:s');
}

/**
 * Salida del body como json
 * @param array $json
 * @param boolean $die
 * @return void
 */

function json_output($data, $die = true)
{
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json; charset=utf-8');

  if (is_array($data)) {
    $data = json_encode($data);
  }

  echo $data;

  if ($die) {
    die();
  }

  return true;
}

/**
 * Construye un nuevo string json
 * @param integer $status
 * @param array $data
 * @param string $msg
 * @return void
 */
function json_build($status = 200, $data = null, $msg = '', $error_code = null)
{
  /*
  1 xx : Información
  2 xx : Éxito
  3 xx : Redirección
  4 xx : Error del cliente
  5 xx : Error del servidor
  */

  if (empty($msg) || $msg == '') {
    switch ($status) {
      case 200:
        $msg = 'OK';
        break;
      case 201:
        $msg = 'Created';
        break;
      case 400:
        $msg = 'Bad Request';
        break;
      case 403:
        $msg = 'Forbidden';
        break;
      case 404:
        $msg = 'Not Found';
        break;
      case 500:
        $msg = 'Internal Server Error';
        break;
      case 503:
        $msg = 'Service Unavailable';
        break;
      case 550:
        $msg = 'Permission Denied';
        break;
      default:
        break;
    }
  }

  $json = [
    'status' => $status,
    'error' => false,
    'msg' => $msg,
    'data' => $data
  ];

  if (in_array($status, [400, 403, 404, 500, 503, 550])) {
    $json['error'] = true;
  }

  if ($error_code != null) {
    $json['error_code'] = $error_code;
  }

  return json_encode($json);
}

/**
 * Regresa parseado un modulo
 *
 * @param string $view
 * @param array $data
 * @return void
 */

function get_module($view, $data = [])
{
  $file = MODULES . $view . 'Module.php';

  $output = '';

  //Por si queremos trbajar cn objecto

  $d =  to_object($data);

  if (!is_file($file)) {
    return false;
  }

  ob_start();
  require_once $file;
  $output = ob_get_clean();

  return $output;
}


/**
 * Formatea un número a divisa
 *
 * @param float $amount
 * @param string $symbol
 * @return void
 */
function money($amount, $symbol = '$')
{
  return $symbol . number_format($amount, 2, '.', ',');
}


/**
 * Carga una opcion de configuración de la bd
 *
 * @param mixed $option
 * @return void
 */
function get_config($option)
{
  return optionModel::search($option);
}

/**
 * Generar un link dinámico con parametros get y token
 * 
 */
function buildURL($url, $params = [], $redirection = true, $csrf = true)
{

  // Check if theres a ?
  $query     = parse_url($url, PHP_URL_QUERY);
  $_params[] = 'hook=' . strtolower(SITE_NAME);
  $_params[] = 'action=doing-task';

  // Si requiere token csrf
  if ($csrf) {
    $_params[] = '_t=' . CSRF_TOKEN;
  }

  // Si requiere redirección
  if ($redirection) {
    $_params[] = 'redirect_to=' . urlencode(CUR_PAGE);
  }

  // Si no es un array regresa la url original
  if (!is_array($params)) {
    return $url;
  }

  // Listando parametros
  foreach ($params as $key => $value) {
    $_params[] = sprintf('%s=%s', urlencode($key), urlencode($value));
  }

  $url .= strpos($url, '?') ? '&' : '?';
  $url .= implode('&', $_params);
  return $url;
}

/**
 * Loggea un registro en un archivo de logs del sistema, usado para debugging
 *
 * @param string $message
 * @param string $type
 * @param boolean $output
 * @return mixed
 */
function logger($message, $type = 'debug', $output = false)
{
  $types = ['debug', 'import', 'info', 'success', 'warning', 'error'];

  if (!in_array($type, $types)) {
    $type = 'debug';
  }

  $now_time = date("d-m-Y H:i:s");

  $message = "[" . strtoupper($type) . "] $now_time - $message";

  if (!$fh = fopen(LOGS . "app_log.log", 'a')) {
    error_log(sprintf('Can not open this file on %s', LOGS . 'app_log.log'));
    return false;
  }

  fwrite($fh, "$message\n");
  fclose($fh);
  if ($output) {
    print "$message\n";
  }

  return true;
}

/**
Formateo de la hora en tres variantes
d M, Y,
m Y,
d m Y,
mY,
d M, Y time
 **/
function format_date($date_string, $type = 'd M, Y')
{
  setlocale(LC_ALL, "es_MX.UTF-8", "es_MX", "esp");

  $date = new DateTime($date_string);
  $date->setTimezone(new DateTimeZone('America/Mexico_City'));
  
  $fmtMes = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::FULL);
  $fmtMes->setPattern('MMMM');

  $fmtDia = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::FULL);
  $fmtDia->setPattern('EEEE');

  $date = [
    'año'        => $date->format('Y'),
    'mes'        => ucfirst($fmtMes->format($date)),
    'mes_corto'  => substr($fmtMes->format($date), 0, 3),
    'dia'        => $date->format('j'),
    'dia_mes'    => $date->format('d'),
    'dia_semana' => ucfirst($fmtDia->format($date)),
    'hora'       => $date->format('H'),
    'minutos'    => $date->format('i'),
    'tiempo'     => $date->format('H:i')
  ];

  switch ($type) {
    case 'd M, Y':
      return $date['dia'] . ' de ' . $date['mes'] . ', ' . $date['año'];
      break;
    case 'm Y':
      return sprintf('%s %s', $date['mes'], $date['año']);
      break;
    case 'd m Y':
      return $date['dia'] . ' ' . $date['mes_corto'] . ' ' . $date['año'];
      break;
    case 'mY':
      return ucfirst($date['mes_corto']) . ', ' . $date['año'];
      break;
    case 'MY':
      return ucfirst($date['mes']) . ', ' . $date['año'];
      break;
    case 'd M, Y time':
      return $date['dia'] . ' de ' . $date['mes'] . ', ' . $date['año'] . ' a las ' . date('H:i A', strtotime($date_string));
      break;
    case 'time':
      return $date['tiempo'] . ' ' . date('A', strtotime($date_string));
      break;
    case 'date time':
      return $date['dia'] . '/' . $date['mes_corto'] . '/' . $date['año'] . ' ' . $date['tiempo'] . ' ' . date('A', strtotime($date_string));
      break;
    case 'short': //01/Nov/2019
      return sprintf('%s/%s/%s', $date['dia_mes'], ucfirst($date['mes_corto']), $date['año']);
      break;
    default:
      return $date['dia'] . ' de ' . $date['mes'] . ', ' . $date['año'];
      break;
  }
}


/**
 * Sanitiza un valor ingresado por usuario
 *
 * @param string $str
 * @param boolean $cleanhtml
 * @return void
 */
function clean($str, $cleanhtml = false) {
  $str = trim($str);
  
  if ($cleanhtml === true) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }
  
  // Remove HTML tags
  $str = strip_tags($str);

  return $str;
}
/**
 * Reconstruye un array de archivos posteados
 *
 * @param array $files
 * @return void
 */
function arrenge_posted_files($files) {
	if(empty($files)) {
		return false;
	}
	
	foreach ($files['error'] as $err) {
		if(intval($err) === 4){
			return false;
		}
	}
	
	$file_ary   = array();
	$file_count = (is_array($files)) ? count($files['name']) : 1;
	$file_keys  = array_keys($files);
	
	for ($i = 0; $i < $file_count; $i++) {
		foreach ($file_keys as $key) {
			$file_ary[$i][$key] = $files[$key][$i];
		}
	}

	return $file_ary;
}

/**
 * Genera un string o password
 *
 * @param integer $tamano
 * @param string $type
 * @return void
 */
function random_password($length = 8, $type = 'default') {
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
  
  if ($type === 'numeric') {
		$alphabet = '1234567890';
	}
  
  $pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
  
  for ($i = 0; $i < $length; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
  }
  
	return str_shuffle(implode($pass)); //turn the array into a string
}

/**
 * Agregar ellipsis a un string
 *
 * @param string $string
 * @param integer $lng
 * @return void
 */
function add_ellipsis($string , $lng = 100) {
	if(!is_integer($lng)) {
		$lng = 100;
	}

  $output = strlen($string) > $lng ? mb_substr($string, 0, $lng, 'UTF-8').'...' : $string;
	return $output;
}

/**
 * Devuelve la IP del cliente actual
 *
 * @return void
 */
function get_user_ip() {
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if (getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if (getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if (getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if (getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if (getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}

/**
 * Devuelve el sistema operativo del cliente
 *
 * @return void
 */
function get_user_os() {
	if (isset( $_SERVER ) ) {
		$agent = $_SERVER['HTTP_USER_AGENT'];
	} else {
		global $HTTP_SERVER_VARS;
		if ( isset( $HTTP_SERVER_VARS ) ) {
			$agent = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		}
		else {
			global $HTTP_USER_AGENT;
			$agent = $HTTP_USER_AGENT;
		}
	}
	$ros[] = array('Windows XP', 'Windows XP');
	$ros[] = array('Windows NT 5.1|Windows NT5.1)', 'Windows XP');
	$ros[] = array('Windows 2000', 'Windows 2000');
	$ros[] = array('Windows NT 5.0', 'Windows 2000');
	$ros[] = array('Windows NT 4.0|WinNT4.0', 'Windows NT');
	$ros[] = array('Windows NT 5.2', 'Windows Server 2003');
	$ros[] = array('Windows NT 6.0', 'Windows Vista');
	$ros[] = array('Windows NT 7.0', 'Windows 7');
	$ros[] = array('Windows CE', 'Windows CE');
	$ros[] = array('(media center pc).([0-9]{1,2}\.[0-9]{1,2})', 'Windows Media Center');
	$ros[] = array('(win)([0-9]{1,2}\.[0-9x]{1,2})', 'Windows');
	$ros[] = array('(win)([0-9]{2})', 'Windows');
	$ros[] = array('(windows)([0-9x]{2})', 'Windows');
	$ros[] = array('Windows ME', 'Windows ME');
	$ros[] = array('Win 9x 4.90', 'Windows ME');
	$ros[] = array('Windows 98|Win98', 'Windows 98');
	$ros[] = array('Windows 95', 'Windows 95');
	$ros[] = array('(windows)([0-9]{1,2}\.[0-9]{1,2})', 'Windows');
	$ros[] = array('win32', 'Windows');
	$ros[] = array('(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})', 'Java');
	$ros[] = array('(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}', 'Solaris');
	$ros[] = array('dos x86', 'DOS');
	$ros[] = array('unix', 'Unix');
	$ros[] = array('Mac OS X', 'Mac OS X');
	$ros[] = array('Mac_PowerPC', 'Macintosh PowerPC');
	$ros[] = array('(mac|Macintosh)', 'Mac OS');
	$ros[] = array('(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'SunOS');
	$ros[] = array('(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'BeOS');
	$ros[] = array('(risc os)([0-9]{1,2}\.[0-9]{1,2})', 'RISC OS');
	$ros[] = array('os/2', 'OS/2');
	$ros[] = array('freebsd', 'FreeBSD');
	$ros[] = array('openbsd', 'OpenBSD');
	$ros[] = array('netbsd', 'NetBSD');
	$ros[] = array('irix', 'IRIX');
	$ros[] = array('plan9', 'Plan9');
	$ros[] = array('osf', 'OSF');
	$ros[] = array('aix', 'AIX');
	$ros[] = array('GNU Hurd', 'GNU Hurd');
	$ros[] = array('(fedora)', 'Linux - Fedora');
	$ros[] = array('(kubuntu)', 'Linux - Kubuntu');
	$ros[] = array('(ubuntu)', 'Linux - Ubuntu');
	$ros[] = array('(debian)', 'Linux - Debian');
	$ros[] = array('(CentOS)', 'Linux - CentOS');
	$ros[] = array('(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)', 'Linux - Mandriva');
	$ros[] = array('(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)', 'Linux - SUSE');
	$ros[] = array('(Dropline)', 'Linux - Slackware (Dropline GNOME)');
	$ros[] = array('(ASPLinux)', 'Linux - ASPLinux');
	$ros[] = array('(Red Hat)', 'Linux - Red Hat');
	$ros[] = array('(linux)', 'Linux');
	$ros[] = array('(amigaos)([0-9]{1,2}\.[0-9]{1,2})', 'AmigaOS');
	$ros[] = array('amiga-aweb', 'AmigaOS');
	$ros[] = array('amiga', 'Amiga');
	$ros[] = array('AvantGo', 'PalmOS');
	$ros[] = array('[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3})', 'Linux');
	$ros[] = array('(webtv)/([0-9]{1,2}\.[0-9]{1,2})', 'WebTV');
	$ros[] = array('Dreamcast', 'Dreamcast OS');
	$ros[] = array('GetRight', 'Windows');
	$ros[] = array('go!zilla', 'Windows');
	$ros[] = array('gozilla', 'Windows');
	$ros[] = array('gulliver', 'Windows');
	$ros[] = array('ia archiver', 'Windows');
	$ros[] = array('NetPositive', 'Windows');
	$ros[] = array('mass downloader', 'Windows');
	$ros[] = array('microsoft', 'Windows');
	$ros[] = array('offline explorer', 'Windows');
	$ros[] = array('teleport', 'Windows');
	$ros[] = array('web downloader', 'Windows');
	$ros[] = array('webcapture', 'Windows');
	$ros[] = array('webcollage', 'Windows');
	$ros[] = array('webcopier', 'Windows');
	$ros[] = array('webstripper', 'Windows');
	$ros[] = array('webzip', 'Windows');
	$ros[] = array('wget', 'Windows');
	$ros[] = array('Java', 'Unknown');
	$ros[] = array('flashget', 'Windows');
	$ros[] = array('(PHP)/([0-9]{1,2}.[0-9]{1,2})', 'PHP');
	$ros[] = array('MS FrontPage', 'Windows');
	$ros[] = array('(msproxy)/([0-9]{1,2}.[0-9]{1,2})', 'Windows');
	$ros[] = array('(msie)([0-9]{1,2}.[0-9]{1,2})', 'Windows');
	$ros[] = array('libwww-perl', 'Unix');
	$ros[] = array('UP.Browser', 'Windows CE');
	$ros[] = array('NetAnts', 'Windows');
	$file = count ( $ros );
	$os = '';
	for ( $n=0 ; $n < $file ; $n++ ){
		if ( @preg_match('/'.$ros[$n][0].'/i' , $agent, $name)){
			$os = @$ros[$n][1].' '.@$name[2];
			break;
		}
	}
	return trim ( $os );
}

/**
 * Devuelve el navegador del cliente
 *
 * @return void
 */
function get_user_browser() {
	$user_agent = (isset($_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : NULL);

	$browser        = "Unknown Browser";

	$browser_array = array(
		'/msie/i'      => 'Internet Explorer',
		'/firefox/i'   => 'Firefox',
		'/safari/i'    => 'Safari',
		'/chrome/i'    => 'Chrome',
		'/edge/i'      => 'Edge',
		'/opera/i'     => 'Opera',
		'/netscape/i'  => 'Netscape',
		'/maxthon/i'   => 'Maxthon',
		'/konqueror/i' => 'Konqueror',
		'/mobile/i'    => 'Handheld Browser'
	);

	foreach ($browser_array as $regex => $value) {
		if (preg_match($regex, $user_agent)) {
			$browser = $value;
		}
	}

	return $browser;
}

/**
 * Inserta campos htlm en un formulario
 *
 * @return string
 */
function insert_inputs() {
	$output = '';

	if(isset($_POST['redirect_to'])){
		$location = $_POST['redirect_to'];
	} else if(isset($_GET['redirect_to'])){
		$location = $_GET['redirect_to'];
	} else {
		$location = CUR_PAGE;
	}

	$output .= '<input type="hidden" name="redirect_to" value="'.$location.'">';
	$output .= '<input type="hidden" name="timecheck" value="'.time().'">';
	$output .= '<input type="hidden" name="csrf" value="'.CSRF_TOKEN.'">';
	$output .= '<input type="hidden" name="hook" value="jserp_hook">';
	$output .= '<input type="hidden" name="action" value="post">';
	return $output;
}

/**
 * Genera un nombre de archivo random
 *
 * @param integer $size
 * @param integer $span
 * @return string
 */
function generate_filename($size = 12, $span = 3) {
	if(!is_integer($size)){
		$size = 6;
	}
	
	$name = '';
	for ($i=0; $i < $span; $i++) { 
		$name .= random_password($size).'-';
	}

	$name = rtrim($name , '-');
	return strtolower($name);
}

/**
 * Formatea el tamaño de un archivo
 *
 * @param float $size
 * @param integer $precision
 * @return string
 */
function filesize_formatter($size , $precision = 1) {
	$units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	$step = 1024;
	$i = 0;
	while (($size / $step) > 0.9) {
		$size = $size / $step;
		$i++;
	}
	return round($size, $precision) . $units[$i];
}

/**
 * Arregla las diagonales invertidas de una URL
 *
 * @param string $url
 * @return string
 */
function fix_url($url) {
	return str_replace('\\', '/', $url);
}

/**
 * Regresa el valor de sesión o un index en especial
 *
 * @param string $v
 * @return mixed
 */
function get_session($v = null) {
  if($v === null){
    return $_SESSION;
  }

  /** If it's an array of data must be dot separated */
  if(strpos($v , ".") !== false) {
    $array = explode('.',$v);
    $lvls = count($array);

    for ($i=0; $i < $lvls; $i++) { 
      if(!isset($_SESSION[$array[$i]])){
        return false;
      }
    }

  }

  if(!isset($_SESSION[$v])){
    return false;
  }

  if(empty($_SESSION[$v])){
    unset($_SESSION[$v]);
    return false;
  }

  return $_SESSION[$v];
}

/**
 * Guarda en sesión un valor y un index
 *
 * @param string $k
 * @param mixed $v
 * @return bool
 */
function set_session($k, $v) {
  $_SESSION[$k] = $v;
  return true;
}

/**
 * Envía un correo electrónico usando PHPMailer
 *
 * @param string $from
 * @param string $to
 * @param string $subject
 * @param string $body
 * @param string $alt
 * @param string $bcc
 * @param string $reply_to
 * @param array $attachments
 * @return void
 */
function send_email($from, $to, $subject, $body, $alt = null, $bcc = null, $reply_to = null, $attachments = []) {
	$mail     = new PHPMailer(true);
	$mail->isSMTP();
	$template = 'emailTemplate';
	
	try {
		$mail->CharSet = 'UTF-8';
		// Remitente
		$mail->setFrom($from, get_sitename());

		// Destinatario
		$mail->addAddress($to);

		if ($reply_to != null) {
			$mail->addReplyTo($reply_to);
		}

		if ($bcc != null) {
			$mail->addBCC($bcc);
		}

		// Attachments
		if (!empty($attachments)) {
			foreach ($attachments as $file) {
				if (!is_file($file)) {
					continue;
				}

				$mail->addAttachment($file);
			}
		}

		// Content
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body    = get_module($template, ['alt' => $alt, 'body' => $body, 'subject' => $subject]);
		$mail->AltBody = $alt;

		$mail->send();
		return true;

	} catch (EmailException $e) {
		throw new Exception($e->getMessage());
	}
}

/**
 * Muestra en pantalla los valores pasados
 *
 * @param mixed $data
 * @return void
 */
function debug($data) {
  echo '<pre>';
  if(is_array($data) || is_object($data)) {
    print_r($data);
  } else {
    echo $data;
  }
  echo '</pre>';
}

/**
 * Creación de un token de 32 caractores por defecto
 *
 * @param integer $length
 * @return string
 */
function generate_token($length = 32) {
	$token = null;
	if (function_exists('bin2hex')) {
		$token = bin2hex(random_bytes($length)); // ASDFUHASIO32Jasdasdjf349mfjads9mfas4asdf
	} else {
		$token = bin2hex(openssl_random_pseudo_bytes($length)); // asdfuhasi487a9s49mafmsau84
	}

	return $token;
}

/**
 * Valida los parametros pasados en POST
 *
 * @param array $required_params
 * @param array $posted_data
 * @return void
 */
function check_posted_data($required_params = [] , $posted_data = []) {

  if(empty($posted_data)) {
    return false;
  }

  // Keys necesarios en toda petición
  $defaults = ['hook','action'];
  $required_params = array_merge($required_params,$defaults);
  $required = count($required_params);
  $found = 0;

  foreach ($posted_data as $k => $v) {
    if(in_array($k , $required_params)) {
      $found++;
    }
  }

  if($found !== $required) {
    return false;
  }

  return true;
}

/**
 * Valida parametros ingresados en la URL como GET
 *
 * @param array $required_params
 * @param array $get_data
 * @return void
 */
function check_get_data($required_params = [] , $get_data = []) {

  if(empty($get_data)) {
    return false;
  }

  // Keys necesarios en toda petición
  $defaults = ['hook','action'];
  $required_params = array_merge($required_params, $defaults);
  $required = count($required_params);
  $found = 0;

  foreach ($get_data as $k => $v) {
    if(in_array($k , $required_params)) {
      $found++;
    }
  }

  if($found !== $required) {
    return false;
  }

  return true;
}

/**
 * Agrega un tooltip con más información definida como string
 *
 * @param string $str
 * @param string $color
 * @param string $icon
 * @return void
 */
function more_info($str , $color = 'text-info' , $icon = 'fas fa-exclamation-circle') {
  $str = clean($str);
  $output = '';
  $output .= '<span class="'.$color.'" '.tooltip($str).'><i class="'.$icon.'"></i></span>';
  return $output;
}

/**
 * Agrega un placeholder a un campo input
 *
 * @param string $string
 * @return void
 */
function placeholder($string = 'Lorem ipsum') {
  return sprintf('placeholder="%s"', $string);
}

/**
 * Agrega un tooltip en plantalla
 *
 * @param string $title
 * @return void
 */
function tooltip($title = null) {
	if($title == null){
		return false;
	}

	return 'data-toggle="tooltip" title="'.$title.'"';
}

/**
 * Genera un menú dinámico con base a los links pasados
 *
 * @param array $links
 * @param string $active
 * @return void
 */
function create_menu($links, $slug_active = 'home') {
  $output = '';
  $output .= '<ul class="nav flex-column">';
  foreach ($links as $link) {
    if ($slug_active === $link['slug']) {
      $output .= 
      sprintf(
        '<li class="nav-item">
        <a class="nav-link active" href="%s">
          <span data-feather="%s"></span>
          %s
        </a>
        </li>',
        $link['url'],
        $link['icon'],
        $link['title']
      );
    } else {
      $output .= 
      sprintf(
        '<li class="nav-item">
        <a class="nav-link" href="%s">
          <span data-feather="%s"></span>
          %s
        </a>
        </li>',
        $link['url'],
        $link['icon'],
        $link['title']
      );
    }
  }
  $output .= '</ul>';

  return $output;
}

/**
 * Función para cargar el url de nuestro asset logotipo del sitio
 *
 * @return void
 */
function get_logo() {
	$default_logo_name = 'logo';
	$default_logo_size = '500';
	$default_logo_ext  = 'png';
	$logo              = sprintf('%s_%s.%s', $default_logo_name, $default_logo_size, $default_logo_ext); // logo_500.png
	if (!is_file(IMAGES_PATH.$logo)) {
		return false;
	}

	return IMAGES.$logo;
}

/**
 * Carga y regresa un valor determinao de la información del usuario
 * guardada en la variable de sesión actual
 *
 * @param string $key
 * @return mixed
 */
function get_user($key = null) {
	if (!isset($_SESSION['user_session'])) return false;

	$session = $_SESSION['user_session']; // información de la sesión del usuario actual, regresará siempre falso si no hay dicha sesión

	if (!isset($session['user']) || empty($session['user'])) return false;

	$user = $session['user']; // información de la base de datos o directamente insertada del usuario

	if ($key === null) return $user;

	if (!isset($user[$key])) return false; // regresará falso en caso de no encontrar el key buscado

	// Regresa la información del usuario
	return $user[$key];
}

/**
 * Determina si el sistema está en modo demostración o no
 * para limitar el acceso o la interacción de los usuarios y funcionalidades
 *
 * @return boolean
 */
function is_demo()
{
	return IS_DEMO;
}

/**
 * Función para validar si el sistema es una demostración
 * y guardar una notificación flash y redirigir al usuario
 * de así solicitarlo
 *
 */
function check_if_demo($flash = true, $redirect = true) {
  $demo = is_demo();

  if ($demo == false) return false;

  if ($flash == true) {
    Flasher::new(sprintf('No disponible en la versión de demostración de %s.', get_sitename()), 'danger');
  }

  if ($redirect == true) {
    Redirect::back();
  }

  return true;
}

/**
 * Para registrar una hoja de estilos de forma manual y automática
 *
 * @param array $stylesheets
 * @param string $comment
 * @return bool
 */
function register_styles($styles) {
  global $App_Styles;

  foreach ($styles as $style) {
    // No verificar si el archivo existe para los estilos de CDN
    if (strpos($style['file'], 'http') !== false || file_exists(CSS_DIR . $style['file'])) {
      $App_Styles[] = 
      [
        'comment' => $style['comment'],
        'files'   => [$style['file']]
      ];
    }
  }
  
  return true;
}

/**
 * Carga y muestra las hojas de estilo registradas.
 *
 * Esta función carga las hojas de estilo registradas en la variable global $App_Styles
 * y muestra el código HTML para incluirlas en la página.
 *
 * @return string El código HTML para incluir las hojas de estilo registradas.
 */
function load_styles() {
  global $App_Styles;

  $dir = CSS_DIR;
  $stylesheets = glob($dir . "{,*/,*/*/}*.css", GLOB_BRACE);

  foreach($stylesheets as $stylesheet) {
    $stylesheetUrl = CSS . str_replace($dir, '', $stylesheet);
    // Verificar si el archivo existe antes de incluirlo
    if (file_exists($stylesheet)) {
      $App_Styles[] = 
      [
        'comment' => 'ARCHIVO: ' . basename($stylesheet),
        'files'   => [$stylesheetUrl]
      ];
    }
  }

  $output = '';

  if(empty($App_Styles)){
    return $output;
  }

  // Iterar sobre cada elemento registrado
  foreach (json_decode(json_encode($App_Styles)) as $css) {
    if($css->comment){
      $output .= "\t".'<!-- '.$css->comment.' -->'."\n";
    }

    // Iterar sobre cada path de archivo registrado
    foreach ($css->files as $f) {
      $output .= "\t".'<link rel="stylesheet" href="'.$f.'" >'."\n";
    }
  }

  return $output;
}

/**
 * Para registrar uno o más scripts de forma manual y automática
 *
 * @param array $scripts
 * @return bool
 */
function register_scripts($scripts) {
  global $App_Scripts;

  foreach ($scripts as $script) {
    // No verificar si el archivo existe para los scripts de CDN
    if (strpos($script['file'], 'http') !== false || file_exists($script['file'])) {
      $App_Scripts[] = 
      [
        'comment' => $script['comment'],
        'files'   => [$script['file']]
      ];
    }
  }

  return true;
}

/**
 * Carga los scrips registrados de forma manual y automática
 * por la función register_scripts()
 *
 * @return string
 */
function load_scripts() {
  global $App_Scripts;

  $dir = JS_DIR;
  $Scripts = glob($dir . "{,*/,*/*/}*.js", GLOB_BRACE);

  foreach($Scripts as $script) {
    $scripts = JS . str_replace($dir, '', $script);
    // Verificar si el archivo existe antes de incluirlo
    if (file_exists($script)) {
      $App_Scripts[] = 
      [
        'comment' => 'FILE: ' . basename($script),
        'files'   => [$scripts]
      ];
    }
  }

  $output = '';

  if(empty($App_Scripts)){
    return $output;
  }

  // Iterar sobre cada elemento registrado
  foreach (json_decode(json_encode($App_Scripts)) as $js) {
    if($js->comment){
      $output .= "\t".'<!-- '.$js->comment.' -->'."\n";
    }

    // Iterar sobre cada path de archivo registrado
    foreach ($js->files as $f) {
      $output .= "\t".'<script src="'.$f.'"></script>'."\n";
    }
  }

  return $output;
}


/**
 * Registar un nuevo valor para el objeto del Sistema (App)
 * insertado en el pie del sitio como objeto para
 * acceder a los parámetros de forma sencilla
 *
 * @param string $key
 * @param mixed $value
 * @return bool
 */
function register_to_App_obj($key, $value) {
	global $App_Object;

  $Angel_Scripts[$key] = clean($value);

  return true;
}

/**
 * Carga el objeto Angel registrado y todos sus valores
 * por defecto y personalizados
 *
 * @return string
 */
function load_App_obj() {
	global $App_Object;
	$output = '';

  if(empty($App_Object)){
    return $output;
  }

	$output .= '<script>';
	$output .= 'var App = {'."\n";

	// Iterar sobre todos los elementos registrados
  foreach ($App_Object as $k => $v) {
		$output .= sprintf('%s: "%s",'."\n", $k, $v);
	}

	$output .= '};';
	$output .= '</script>';

  return $output;
}

/**
 * Registra los parámetro por defecto del Sistema
 *
 * @return bool
 */
function app_obj_default_config() {
	global $App_Object;

	$App_Object =
	[
		'sitename'     => get_sitename(),
		'version'      => get_version(),
		'app_name'     => get_app_name(),
		'app_version'  => get_app_version(),
		'csrf'         => CSRF_TOKEN,
		'url'          => URL,
		'cur_page'     => CUR_PAGE,
		'is_local'     => IS_LOCAL,
		'is_demo'      => IS_DEMO,
		'basepath'     => BASEPATH,
		'sandbox'      => SANDBOX,
		'port'         => PORT,
		'request_uri'  => REQUEST_URI,
		'assets'       => ASSETS,
		'images'       => IMAGES,
		'uploaded'     => UPLOADED
	];

	return true;
}