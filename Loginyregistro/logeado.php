<?php
session_start();
if($_SESSION['logged'] == 'yes')
{
	echo 'Bienvenido '.$_SESSION['user'].', esta es tu pagina personal.';	
}else{
	echo 'No estas logeado, largate de aqui.';	
}
?>