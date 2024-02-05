<?php
// Ejemplo de controlador para página home de la aplicación
require_once __DIR__ . '/../Repositorio/GestionBDRepositorio.php';
class DefaultController
{
  public function inicio()
  {
    try {
      $arrayCategorias = ((new GestionBDRepositorio)->getCategories());
    } catch (PDOException $ex) {
      echo ($ex->getMessage());
    }

    require __DIR__ . '/../../app/plantillas/inicio.php';
  }
}
