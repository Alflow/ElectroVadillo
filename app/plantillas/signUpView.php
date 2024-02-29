<?php ob_start() ?>

<div class="col-md-6">
    <form method="post">
        <legend>Formulario de registro</legend>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="signUp[userEMail]">
            <div id=" emailHelp" class="form-text">No comprartiremos tus datos con nadie.
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="signUp[userPassword]">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Confirma tu contraseña</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="signUp[userPasswordConfirm]">
            </div>
            
            <input type="submit" class="btn btn-primary" name="ok" value="Registrarme">
            
            <?php
            if (isset($errors)) {
                foreach ($errors as $error => $errorMessage) {
                    echo ('<p>' . $errorMessage . '  </p>');
                }
            }
            ?>
    </form>
     
</div>

<?php $contenido = ob_get_clean() ?>

<?php include 'base.php' ?>