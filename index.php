<?php
session_start();
ob_start();
header('p3p: CP="NOI ADM DEV PSAi COM NAV OUR OTR STP IND DEM"');
$uri = substr($_SERVER["SCRIPT_NAME"],0,strripos($_SERVER["SCRIPT_NAME"],"/"));

$protocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';

$url =  "{$protocol}://{$_SERVER["HTTP_HOST"]}$uri";

define('URL', $url);
define('APP_HOME',dirname(__FILE__) . DIRECTORY_SEPARATOR . 'app');

$include_path  =  APP_HOME . DIRECTORY_SEPARATOR . 'library'
				. PATH_SEPARATOR
				. APP_HOME . DIRECTORY_SEPARATOR . 'mods' ;

set_include_path( $include_path . PATH_SEPARATOR . get_include_path());

function __autoload($class_name){
	$class = str_replace('_',DIRECTORY_SEPARATOR,$class_name);
	$file = $class . '.php';
	if ($fh = @fopen($file, 'r', true)) {
		include_once $file;
	}
	@fclose($fh);
}

require_once 'funciones.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestScript = str_replace('index.php',NULL,$_SERVER['SCRIPT_NAME']);
$get = str_replace($requestScript, NULL, $requestUri);
$get = explode('?',$get);
$get = $get[0];
$get = explode("/",$get);

$_GET['controller'] = $controller = $get[0] ? $get[0] : 'index';
$_GET['action']     = $action     = (isset($get[1]) && $get[1] )? $get[1] : "index";

if(count($get)>2){
	$items = count($get); 
	for($item = 2; $item < $items; $item+=2){
		if($get[$item]){
			$_GET[ $get[$item] ] = isset($get[$item+1])?$get[$item+1]:count($_GET);
		}
	}
}

$response   = isset($_GET['response']) ? $_GET['response'] : 'html';

$controller = str_replace("-"," ",$controller);
$controller = ucwords(strtolower( $controller ));
$controller = str_replace(" ",NULL, $controller);
$action = str_replace("-"," ",$action);
$action = ucwords(strtolower( $action ));
$action = str_replace(" ",NULL, $action);

$controller_class = "Controller_{$controller}";
try{
	if(class_exists($controller_class)){
		$controller_object = new $controller_class();
		if( is_callable( array($controller_object, $action.'Action') ) ){
			if( $controller_object instanceof Controller_Abstract ){
				$controller_object->dispatch( $action );
			}else{
				throw new Exception("{$controller} no es un controlador válido");
			}
		} else {
			throw new Exception("La acción solicitada no esta disponible para {$controller}");
		}
	}else{
		ob_get_clean();
		header('Location: '.URL.'/error-page');
	}
}catch (Exception $ex){
	$exception = new Controller_Exception();
	$exception->dispatch('showError', $ex);
}

 is_ajax() || $response == 'json' && header('Content-type: application/json');

if( !is_ajax() && $response == 'html' ){
	$content  = 'content';
	$$content = ob_get_clean();		
	$path_file = APP_HOME . DIRECTORY_SEPARATOR . 'mods' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR  . "layout.phtml";
	include_once($path_file);
}