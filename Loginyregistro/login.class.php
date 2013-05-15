<?php
/**
 * Clase Login para validar un user comprobando su user (o email) y contraseña
 * Forma parte del paquete de tutoriales en PHP disponible en http://www.emiliort.com
 * @author Emilio Rodríguez - http://www.emiliort.com
 */
class Login {
    
    public $tabla='tutorial1_usuarios'; //nombre de la tabla usarios
    public $campo_user='user'; //campo que contiene los datos de los users (se puede usar el email)
    public $campo_clave='pass'; //campo que contiene la contraseña
    public $metodo_encriptacion='texto'; //método utilizado para almacenar la contrasela. Opciones: sha1, md5, o texto

    private $link; //identificador de la conexión mysql que usamos
    
    /**
     * establecemos el método  de construccion de la clase que se llamará al crear el objeto. Conectamos a la base de datos
     * @return bool
     */
   public function __construct() {
       //1 - conectamos a la base de datos utilizando los parámetros globales
       // deberiamos utilizar una clase de acceso a la base de datos con el patrón singleton, pero lo dejo para otro tutorial
        $this->link =  mysql_connect(SERVIDOR_MYSQL, USUARIO_MYSQL, PASSWORD_MYSQL);

        if (!$this->link) {
            trigger_error('Error al conectar al servidor mysql: ' . mysql_error(),E_USER_ERROR);
        }
        
        // Seleccionar la base de datos activa
        $db_selected = mysql_select_db(BASE_DATOS,$this->link);
        if (!$db_selected) {
            trigger_error ('Error al conectar a la base de datos: ' . mysql_error($this->link),E_USER_ERROR);
        }
        
        return true;
        
   }
   
   //el metodo de destrucción al destruir el objeto
   public function __destruct() {
       mysql_close($this->link);
   }
   
   
    /**
     * valida un user y contraseña
     * @param string $user
     * @param string $pass
     * @return bool
     */
    public function login($user, $pass) {

        //user y pass tienen datos?
        if (empty($user)) return false;
        if (empty ($pass)) return false;

        //2 - preparamos la consulta SQL a ejecutar utilizando sólo el user y evitando ataques de inyección SQL.
        $query='SELECT '.$this->campo_user.', '.$this->campo_clave.' FROM '.$this->tabla.' WHERE '.$this->campo_user.'="'.  mysql_real_escape_string($user).'" LIMIT 1 '; //la tabla y el campo se definen en los parametros globales
        $result = mysql_query($query);
        if (!$result) {
            trigger_error('Error al ejecutar la consulta SQL: ' . mysql_error($this->link),E_USER_ERROR);
        }


        //3 - extraemos el registro de este user
        $row = mysql_fetch_assoc($result);

        if ($row) {
            //4 - Generamos el hash de la contraseña encriptada para comparar o lo dejamos como texto plano
            switch ($this->metodo_encriptacion) {
                case 'sha1'|'SHA1':
                    $hash=sha1($pass);
                    break;
                case 'md5'|'MD5':
                    $hash=md5($pass);
                    break;
                case 'texto'|'TEXTO':
                    $hash=$pass;
                    break;
                default:
                    trigger_error('El valor de la propiedad metodo_encriptacion no es válido. Utiliza MD5 o SHA1 o TEXTO',E_USER_ERROR);
            }

            //5 - comprobamos la contraseña
            if ($hash==$row[$this->campo_clave]) {
                @session_start();
                $_SESSION['user']=array('user'=>$row[$this->campo_user]); //almacenamos en memoria el user
                // en este punto puede ser interesante guardar más datos en memoria para su posterior uso, como por ejemplo un array asociativo con el id, nombre, email, preferencias, ....
                return true; //user y contraseña validadas
            } else {
                @session_start();
                unset($_SESSION['user']); //destruimos la session activa al fallar el login por si existia
                return false; //no coincide la contraseña
            }
        } else {
            //El user no existe
            return false;
        }

    }
    
    


    /**
     * Veridica si el user está logeado
     * @return bool
     */
    public function estoy_logeado () {
        @session_start(); //inicia sesion (la @ evita los mensajes de error si la session ya está iniciada)

        if (!isset($_SESSION['user'])) return false; //no existe la variable $_SESSION['user']. No logeado.
        if (!is_array($_SESSION['user'])) return false; //la variable no es un array $_SESSION['user']. No logeado.
        if (empty($_SESSION['user']['user'])) return false; //no tiene almacenado el user en $_SESSION['user']. No logeado.

        //cumple las condiciones anteriores, entonces es un user validado
        return true;

    }

    /**
     * Vacia la sesion con los datos del user validado
     */
    public function logout() {
        @session_start(); //inicia sesion (la @ evita los mensajes de error si la session ya está iniciada)
        unset($_SESSION['user']); //eliminamos la variable con los datos de user;
        session_write_close(); //nos asegurmos que se guarda y cierra la sesion
        return true;
    }
    
    
}




    
?>