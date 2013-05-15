<?php
require_once('funciones.php');
conectar('mysql.hostinger.es', 'u767888830_root', 'vegeta', 'u767888830_usuarios');

//Recibir
$user = strip_tags($_POST['user']);
$pass = strip_tags(sha1($_POST['pass']));
$mail = strip_tags($_POST['mail']);
$ip = $_SERVER['REMOTE_ADDR'];

if($_POST["user"] == "" && $_POST["pass"] ==""&& $_POST["mail"]= "")
{
	echo "<meta http-equiv ='Refresh' content='12;url=http://pruebasbalidea.hol.es/trivial/registro.html'>";
	
}
else
{
	echo "Registro guardado.Seras redireccionad automaticamente en 5 segundos ";
	echo "<meta http-equiv='Refresh' content='5;url=http://pruebasbalidea.hol.es/trivial/registro.html'>";
    
} 

$query = @mysql_query('SELECT * FROM tutorial1_usuarios WHERE user="'.mysql_real_escape_string($user).'"');
if($existe = @mysql_fetch_object($query))
{
	echo 'El usuario '.$user.' ya existe.';	
}else{
	$meter = @mysql_query('INSERT INTO tutorial1_usuarios (user, pass, mail, ip) values ("'.mysql_real_escape_string($user).'", "'.mysql_real_escape_string($pass).'", "'.mysql_real_escape_string($mail).'", "'.$ip.'")');
	if($meter)
	{
		echo 'Usuario registrado con exito';
		header("Location: http://pruebasbalidea.hol.es/trivial/login.html");
		
	}else{
		echo 'Hubo un error en el registro.';	
	}
}

?>
