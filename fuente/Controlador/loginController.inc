<?php
// Ejemplo de controlador para página home de la aplicación
require_once __DIR__ . '/../Repositorio/GestionBDRepositorio.php';
class LoginController
{
    public function checkLoginForm()
    {


        if (isset($_POST['okLogin'])) {
            die('PULSADO OK DE LOGIN');
           switch($_SESSION)
        }
/*
        try {
            // (new GestionBDRepositorio)->getPwd();
            $arrayCategorias = ((new GestionBDRepositorio)->getCategories());
        } catch (PDOException $ex) {
            echo ($ex->getMessage());
        }*/
    }
}
