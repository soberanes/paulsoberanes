<?php
class Controller_Contacto extends Controller_Abstract{

	protected $_controller = 'contacto';
	
	public function indexAction(){
			$var = isset($_GET['var']) ? $_GET['var'] : 0;
			$this->set_view_vars['var'] = $var;
	}

	public function recibeAction(){
			if($_POST['name'] != "" && $_POST['email'] != "" && $_POST['message'] != "" && $_POST['human'] == 4  ){
				$para      = "paul@paulsoberanes.com";
				$titulo = "Nuevo mensaje de contacto";
				$mensaje = $_POST['message'];
				$cabeceras = "From: ".$_POST['name']." (".$_POST['email'].")" . "\r\n" .
				    "X-Mailer: PHP/" . phpversion();

				mail($para, $titulo, $mensaje, $cabeceras);
				header("location: contacto/index/var/1");
			}else{ echo "ERROR"; }
	}

}