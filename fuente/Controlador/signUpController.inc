<?php
// Ejemplo de controlador para página home de la aplicación
require_once __DIR__ . '/../Repositorio/GestionBDRepositorio.php';
require_once __DIR__ . '/../../app/utilidades/sanitiza.inc';

class SignUpController{

    public function signUp (){
        $arrayProvincias = (new GestionBDRepositorio())->getProvincias();

        if(isset($_POST['ok'])){
            $errors= [];
            
            $inputs = $_POST['signUp'];
            echo '<pre>';
            var_dump($inputs);
            echo '</pre>';
            
            // Saneamiento de inputs
            $userFullName = sanea($inputs['fullName']);
            $userAddress = sanea($inputs['address']);
            $userCP = sanea($inputs['cp']);
            $userMail = sanea($inputs['userEMail']);
            $userPassword = sanea($inputs['userPassword']);
            $userPasswordConfirm = sanea($inputs['userPasswordConfirm']);

            if(empty($userFullName)){
                $errors['emptyFullName'] = 'Es necesario que introduzcas tu nombre';
            }
            if(empty($userAddress)){
                $errors['emptyAddress'] = 'Es necesario que introduzcas tu dirección';
            }
            if(empty($userCP)){
                $errors['emptyUserCP'] = 'Es necesario que introduzcas tu CP';
            }

            if(!filter_var($userMail, FILTER_VALIDATE_EMAIL)){
                $errors['badFormatEmail']= 'Formato de correo electrónico no válido';
            }

            if($userPassword !== $userPasswordConfirm){
                $errors['notEqualPasswords'] = 'Las contraseñas deben ser iguales';
            }

            // si no se ha producido ningún error, comenzamos la funcionalidad de registro
            if(empty($errors)){
                var_dump("NO SE HAN APRECIADO ERRORES");
                $dataForDB = [];
                $dataForDB['name']= $userFullName;
                $dataForDB['address']= $userAddress;
                $dataForDB['cp']= $userCP;
                $dataForDB['email']= $userMail;
                $dataForDB['pass']=password_hash($userPassword, PASSWORD_DEFAULT); 
                var_dump($dataForDB['pass']);
                // (new GestionBDRepositorio())->signUpUser($dataForDB);         
               header('location: index.php');
            }
        }
        require __DIR__ . '/../../app/plantillas/signUpView.php';
    }



}