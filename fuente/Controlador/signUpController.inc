<?php
// Ejemplo de controlador para página home de la aplicación
require_once __DIR__ . '/../Repositorio/GestionBDRepositorio.php';
require_once __DIR__ . '/../../app/utilidades/sanitiza.inc';

class SignUpController{

    public function signUp (){


        require __DIR__ . '/../../app/plantillas/signUpView.php';
    }



}