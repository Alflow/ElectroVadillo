<?php
// Ejemplo de controlador para página home de la aplicación
require_once __DIR__ . '/../Repositorio/GestionBDRepositorio.php';
class DefaultController
{
  public function inicio()
  {
    //vista inicial, preparamos la variable en sesión de precio total para el offcanvas del carrito. 
    if (!isset($_SESSION['totalPrice'])) {
      $_SESSION['totalPrice'] = 0;
    }

    $_SESSION['control'] = 'inicio';




    try {
      
      $arrayCategorias = ((new GestionBDRepositorio)->getCategories());
    } catch (PDOException $ex) {
      echo ($ex->getMessage());
    }

    require __DIR__ . '/../../app/plantillas/inicio.php';
  }
}
