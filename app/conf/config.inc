<?php
/* configuraciones comunes a la aplicación */
class configuracion
{ private $dbParams = []; /*Vector con parámetros de configuración del SGBD */

  public function __construct()
  { $this->dbParams = ["driver"=>"pdo_sqlsrv", //no utilizada realmente
                       "server"=>"(local)", //nombre servidor
                       "port"=>"1433", //puerto por el que escucha las peticiones de la aplicación
                       "database"=>"NOMBRE-BASE-DATOS-CAMBIAR", //nombre de la base de datos
                       "user"=>null, //usuario definido para la aplicación
                       "pass"=>null, //contraseña del usuario
                       "charset"=>"utf-8"]; //conjunto de caracteres
  }

  public function getDbParams(): Array
  { return $this->dbParams;
  }
}
