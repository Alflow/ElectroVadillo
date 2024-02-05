<?php

// var_dump($arrayCategorias);

?>
<?php ob_start() ?>

<div class="row row-cols-2 row-cols-md-6 g-4">
  <?php foreach ($arrayCategorias as $categoria => $detalles) : ?>

    <div class="col">
      <a href="index.php?ctl=showCategory&cat=<?= $categoria  ?>" class="text-decoration-none">
        <div class="card">
          <img src="web/imagenes/<?= $detalles['imagen']  ?>" class="card-img-top" alt="asdsd">
          <div class="card-body">
            <h5 class="card-title"><?= $detalles['nombre']  ?></h5>
            <p class="card-text"><?= substr($detalles['descripcion'], 0, 60) . '...'  ?></p>
          </div>
        </div>
      </a>
    </div>

  <?php endforeach; ?>

</div>

<?php $contenido = ob_get_clean() ?>

<?php include 'base.php' ?>