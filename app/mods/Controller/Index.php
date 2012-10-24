<?php
class Controller_Index extends Controller_Abstract{

	protected $_controller = 'index';
	
	public function indexAction(){
		
	}
	
	public function contactoAction(){
		
	}

	public function recibeAction(){
		
		if($_POST['name'] != '' && $_POST['email'] != '' && $_POST['message'] != '' && $_POST['human'] == 4){
			
			$nombre = $_POST['name'];
			$email  = $_POST['email'];
			$message = $_POST['message'];

			$to      = 'paul@paulsoberanes.com';
			$titulo = 'Nuevo Mensaje de Contacto';
			
			$cabeceras = "From:".$nombre." (".$email.")" . "\r\n" .
			    'Reply-To: paul@paulsoberanes.com' . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();

			mail($para, $titulo, $mensaje, $cabeceras);

		}
	}

}