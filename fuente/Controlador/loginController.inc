<?php
// Ejemplo de controlador para página home de la aplicación
require_once __DIR__ . '/../Repositorio/GestionBDRepositorio.php';
require_once __DIR__ . '/../../app/utilidades/sanitiza.inc';

class LoginController
{
    public function checkLoginForm()
    {
        if (isset($_POST['ok'])) {
            var_dump('PULSADO OK DE LOGIN');
            $inputs = $_POST['credentials'];
            $submittedMail = sanea($inputs['userEmail']);
            $submittedPassword = sanea($inputs['userPassword']);



            // var_dump($submittedMail);
            // var_dump($submittedPassword);
            try {

                $socio = ((new GestionBDRepositorio))->getClienteByECorreo($submittedMail);


                if (password_verify($submittedPassword, $socio['pwd'])) {
                    // DATOS DE ACCESO CORRECTOS. (PASSWORD CORRECTA)
                    $_SESSION['email'] = $submittedMail; // Guarda el correo del usuario en la sesión
                    header('Location: index.php');
                    exit();
                } else {
                    $errorIncorrectData = 'El correo o la contraseña son incorrectos';
                }
            } catch (PDOException $ex) {
                $errorUserNotExists = $ex->getMessage();
            } catch (Exception $ex) {
                if ($ex->getCode() === 100) {
                    $errorNoExiste = $ex->getMessage();
                }
            }

            var_dump($socio);
        }


        require __DIR__ . '/../../app/plantillas/login.php';
    }
}
