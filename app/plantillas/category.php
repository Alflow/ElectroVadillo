<?php ob_start() ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Cables</li>
    </ol>
</nav>

<p>
    <?php
    echo ($categories[$_GET['cat']]['descripcion']);

    ?>
</p>
<div class="row row-cols-2 row-cols-md-2 g-4">
    <?php foreach ($categoryProductsFinal as $code => $details) : ?>
        <div class="col">
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="web/imagenes/<?= $code . '.png' ?>" class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= $code  ?></h5>
                            <p class="card-text"><?= $details['descripcion']  ?></p>
                            <p class="card-text"><?= number_format($details['pv'], 2) . '€' ?></p>
                            <?php if ($details['stock'] < 80) : ?>
                                <p class="card-text"><small class="text-danger"> ¡Quedan pocas unidades!</small></p>
                            <?php endif; ?>
                            <a href="index.php?ctl=showCategory&cat=<?= $details['idCategoria'] . '&order=' . $code    ?>">
                                <button type="button" class="btn btn-primary btn-sm mb-3 text-end">Añadir a la cesta</button>
                            </a>

                        </div>
                    </div>



                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<?php $contenido = ob_get_clean() ?>

<?php include 'base.php' ?>