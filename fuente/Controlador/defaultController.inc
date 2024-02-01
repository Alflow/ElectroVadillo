<?php
// Ejemplo de controlador para página home de la aplicación
class DefaultController
{ public function inicio()
  { 
    require __DIR__ . '/../../app/plantillas/inicio.php';
  }
}
