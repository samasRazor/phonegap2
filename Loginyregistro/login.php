<?php
/*
 * Valida un usuario y contraseña o presenta el formulario para hacer login
 */

if ($_SERVER['REQUEST_METHOD']=='POST') { // ¿Nos mandan datos por el formulario?
    include('php_lib/config.ini.php'); //incluimos configuración
    include('php_lib/login.class.php'); //incluimos las funciones
    $Login=new Login();
    //si hace falta cambiamos las propiedades tabla, campo_usuario, campo_contraseña, metodo_encriptacion

    //verificamos el usuario y contraseña mandados
    if ($Login->login($_POST['user'],$_POST['pass'])) {

       //acciones a realizar cuando un usuario se identifica
       //EJ: almacenar en memoria sus datos completos, registrar un acceso en una tabla mysql
       //Estas acciones se veran en los siguientes tutorianes en http://www.emiliort.com
   
        //saltamos al inicio del área restringida
        header('Location: http://pruebasbalidea.hol.es/trivial/pagina-acceso-restringido.php');
        die();
    } else {
        //acciones a realizar en un intento fallido
        //Ej: mostrar captcha para evitar ataques fuerza bruta, bloquear durante un rato esta ip, ....
        //Estas acciones se veran en los siguientes tutorianes en http://www.emiliort.com

        //preparamos un mensaje de error y continuamos para mostrar el formulario
        

        $mensaje='Usuario o contraseña incorrecto.';
    }
} //fin if post
?>

