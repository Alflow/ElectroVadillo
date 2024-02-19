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

            $errors = [];
            var_dump($submittedMail);
            if (empty($submittedMail)) {
                $errors['errorEmailNotSubmitted']  = "Debe introducir un correo";
            }

            if (empty($submittedPassword)) {
                $errors['errorPasswordNotSubmitted'] = "Debe introducir una contraseña";
            }

            if (!filter_var($submittedMail, FILTER_VALIDATE_EMAIL)) {
                $errors['errorEmailBadFormat']  = "El correo debe tener formato de correo electrónica";
            }
            var_dump($errors);
            // var_dump($submittedPassword);

            if (empty($errors)) {
                try {

                    $socio = ((new GestionBDRepositorio))->getClienteByECorreo($submittedMail);


                    if (password_verify($submittedPassword, $socio['pwd'])) {
                        // DATOS DE ACCESO CORRECTOS. (PASSWORD CORRECTA)
                        $_SESSION['socio'] = $socio; // Guarda el correo del usuario en la sesión

                        if (isset($_SESSION['productsInBasket'])) {
                            ((new GestionBDRepositorio))->updateCartBuyer(session_id(), $socio['id']);
                        }
                        header('Location: index.php');
                        exit();
                    } else {
                        $errors['errorIncorrectCredentials'] = 'El correo o la contraseña son incorrectos';
                    }
                } catch (PDOException $ex) {
                    $errorBD = $ex->getMessage();
                } catch (Exception $ex) {
                    if ($ex->getCode() === 100) {
                        $errors['errorDoesNotExists'] = $ex->getMessage();
                    }
                }

                if (!empty($socio)) {
                    var_dump($socio);
                } else {
                    var_dump("no lo ha encontrado");
                }
            }
        }


        require __DIR__ . '/../../app/plantillas/login.php';
    }

    public function logOut()
    {
        unset($_SESSION['socio']);
        $_SESSION = '';
        //retrasa dos segundos el cierre de sesión.
        sleep(2);
        // Destruimos la sesión y redirigimos 
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
