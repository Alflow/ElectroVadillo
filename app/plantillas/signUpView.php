<?php ob_start() ?>

<div class="col-md-6">
    <form method="post">
        <legend>Formulario de registro</legend>
        <div class="mb-3">
            <div class="mb-3">
                <label for="fullName" class="form-label">Nombre completo</label>
                <input type="text" class="form-control" id="fullName" name="signUp[fullName]">
            </div>
            <label for="provincias">Indique su provincia</label>
            <select class="form-select" aria-label="Default select example" name="provincias" id="provincias">
                <option value="" selected>Indique su provincia provincia</option>
                <?php foreach ($arrayProvincias as $code=>$name) :?>

                    <option value="<?= $code ?>"><?= $name ?></option>
                <?php endforeach; ?>
            </select>

            <div class="mb-3">
                <label for="address" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="address" name="signUp[address]">
            </div>
            
            
            <br>
            <div class="mb-3">
                <label for="cp" class="form-label">Código Postal</label>
                <input type="text" class="form-control" id="cp" name="signUp[cp]" placeholder="12217">
            </div>
            <label for="exampleInputEmail1" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="signUp[userEMail]">
            <div id="emailHelp" class="small tex-white">No comprartiremos tus datos con nadie.
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="signUp[userPassword]">
            </div>
            <div class="mb-3">
                <label for="exampleInputPasswordConfirm" class="form-label">Confirma tu contraseña</label>
                <input type="password" class="form-control" id="exampleInputPasswordConfirm" name="signUp[userPasswordConfirm]">
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