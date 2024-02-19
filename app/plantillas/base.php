<!DOCTYPE html>
<html>

<head>
  <title>Electro Vadillo</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://kit.fontawesome.com">
  <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href='web/css/estilos.css' />

</head>

<body>

  <header class="p-3">
    <div class="container d-flex flex-wrap align-items-center justify-content-between col-md-12">

      <!-- Título a la izquierda -->
      <div class="align-items-center col-xl-1">
        <a href="index.php">
          <img class="img-fluid" src="web/imagenes/logos/logoElectroVadillo.png" alt="logotipo de Electro Vadillo">
        </a>
      </div>

      <!-- Navegación en el medio -->
      <ul class="nav mx-auto mb-2 mb-lg-0 ms-4">
        <li><a href="index.php?ctl=inicio" class="nav-link px-2 text-black fs-3">Inicio</a></li>
        <li><a href="#" class="nav-link px-2 text-black fs-3">Preguntas y respuestas</a></li>
        <li><a href="#" class="nav-link px-2 text-black fs-3">Quienes Somos</a></li>
      </ul>

      <?php if (isset($_SESSIO['socio'])) : ?>
        <?php

        ?>
        <span>
          Bienvenido <?= $_SESSION['socio']['']   ?>
        </span>

      <?php endif; ?>

      <!-- Formulario de búsqueda y botones a la derecha -->
      <div class="d-flex">

        <?php if (isset($_SESSION['socio'])) : ?>
          <a href="index.php?ctl=logOut">
            <button type="button" class="btn btn-danger  mx-5" data-bs-toggle="modal" data-bs-target="#exampleModal">
              SALIR
            </button>
          </a>
        <?php else : ?>
          <a href="index.php?ctl=login">
            <button type="button" class="btn btn-light  mx-5" data-bs-toggle="modal" data-bs-target="#exampleModal">
              LOGIN
            </button>
          </a>


        <?php endif; ?>




        <!-- <button type="button" class="btn btn-warning">Regístrate</button> -->


        <a class="col-4 mt-1 ms-4 px-2 position-relative" data-bs-target="#offcanvasWithBothOptions" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasWithBothOptions">
          <span class="material-symbols-outlined text-black fs-2">
            shopping_cart
          </span>
          <?php
          if (isset($_SESSION['productsInBasket'])) {
            // echo '<pre>';

            echo ('<span class="col-2 position-absolute productsInBasket">' . $_SESSION['productsInBasket'] . '</span>');
            // echo '</pre>';
          }

          ?>
        </a>



      </div>

    </div>
  </header>










  <div class="container mt-4" id="contenido">
    <h1 class="fs-3">ELECTRO VADILLO</h1>

    <!-- OFFCANVAS DE CARRITO  -->
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title mt-3" id="offcanvasExampleLabel">Productos en su carrito:</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="list-group">
          <?php foreach ($_SESSION['basket'] as $code => $details) : ?>
            <li class="list-group-item d-flex align-items-center justify-content-between">
              <div class="col-3">
                <img src="web/imagenes/<?= $code ?>.png" class="img-fluid" alt="Producto">
                <span><?= $code ?></span>
              </div>
              <div class="col-4"><span>x<?= $details['cantidad'] ?></span></div>
              <div class="col-5 d-flex">
                <div class="d-flex bg-primary align-items-center justify-content-center rounded col-4" style="max-height: 1.5rem;">-</div>
                <div class="d-flex align-items-center justify-content-center col-3" style="max-height: 1.5rem;"><?= $details['cantidad'] ?></div>
                <div class="d-flex bg-primary align-items-center justify-content-center rounded col-4" style="max-height: 1.5rem;">+</div>
              </div>

              <!-- PENDIENTE ASIGNAR FUNCIONALIDAD DE BOTÓN, CONTROLADOR Y RUTAS. -->
              <!-- // PENDIENTE ASIGNAR FUNCIONALIDAD DE BOTÓN, CONTROLADOR Y RUTAS.// -->
            </li>
            
            <?php endforeach; ?>
            
            
          </ul>
          <div class="col-12">
            <span> Precio total </span> <div> <span><?=$_SESSION['totalPrice']?>€</span></div>
            <a href="">
              <button type="button" class="btn btn-primary btn-sm text-end">Tramitar pedido</button>
            </a>
          
        </div>
      </div>

      <div class="dropdown mt-3">
        <!-- <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            Dropdown button
          </button> -->
        <!-- <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul> -->
      </div>
    </div>

    <!-- CONTROLA SI EL USUARIO HA INICIADO SESIÓN -->
    <?= $contenido ?>
  </div>

  <!-- iconos de redes sociales -->
  <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="facebook" viewBox="0 0 16 16">
      <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
    </symbol>
    <symbol id="instagram" viewBox="0 0 16 16">
      <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
    </symbol>
    <symbol id="twitter" viewBox="0 0 16 16">
      <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
    </symbol>
  </svg>
  <div class="container ">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
      <div class="col-md-4 d-flex align-items-center">
        <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
          <img src="assets/media/LOGO/LOGO OSCURO.png" alt="" style="max-width: 100px;">
        </a>
        <span class="mb-3 mb-md-0 text-body-secondary">&copy; 2024 ELECTRO VADILLO (Designed by Alfredo A.H)</span>
      </div>

      <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
        <li class="ms-3"><a class="text-body-secondary twitter-icon" href="#"><svg class="bi" width="24" height="24">
              <use xlink:href="#twitter" />
            </svg></a></li>
        <li class="ms-3"><a class="text-body-secondary instagram-icon" href="#"><svg class="bi" width="24" height="24">
              <use xlink:href="#instagram" />
            </svg></a></li>
        <li class="ms-3"><a class="text-body-secondary facebook-icon" href="#"><svg class="bi" width="24" height="24">
              <use xlink:href="#facebook" />
            </svg></a></li>
      </ul>
    </footer>
  </div>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/f43bcdbaa8.js" crossorigin="anonymous"></script>
</body>

</html>