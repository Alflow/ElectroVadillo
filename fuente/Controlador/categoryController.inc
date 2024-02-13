<?php
// Ejemplo de controlador para página home de la aplicación
require_once __DIR__ . '/../Repositorio/GestionBDRepositorio.php';
require_once __DIR__ . '/../../app/utilidades/sanitiza.inc';
class categoryController
{


    public function showCategory()
    {
        // Si no ha añadido ningún producto, inicializa el carrito de sesión en 0
        if (!isset($_SESSION['productsInBasket'])) {
            $_SESSION['productsInBasket'] = 0;
        }




        // if (isset($_SESSION['basket'])) {
        //     echo '<pre>';
        //     var_dump($_SESSION['basket']);

        //     echo '</pre>';
        // }
        if (isset($_GET['cat'])) {
            try {

                $categories = ((new GestionBDRepositorio))->getCategories();

                $categoryProductsTemp = ((new GestionBDRepositorio)->getProductsByCategory($_GET['cat']));
                // Maquetando estructura final del array de productos de la categoría.
                $categoryProductsFinal = [];
                foreach ($categoryProductsTemp as $index => $details) {
                    $categoryProductsFinal[$details['codigo']] =
                        [
                            'descripcion' => $details['descripcion'],
                            'pv' => $details['pv'],
                            'stock' => $details['stock'],
                            'idCategoria' => $details['idCategoria'],

                        ];
                }
            } catch (PDOException $ex) {
                echo ($ex->getMessage());
            }


            // -----------------------------SI SE ESTÁ PIDIENDO UN PRODUCTO................................................................
            if (isset($_GET['order'])) {
                // saneamos el id del producto para proceder a meterlo al carrito
                $productId = sanea($_GET['order']);

                // preparamos el array con todos los campos que querremos pasar a la función de BDRepositiorio, para que 
                // haga un insert en la tabla carrito

                $productToBasketInfo = [];
                if (isset($_SESSION['socio'])) {
                    $productToBasketInfo['buyer'] = $_SESSION['socio']['id'];
                } else {
                    $productToBasketInfo['buyer'] = session_id();
                }

                // ATENCIÓN. NO FUNCIONARÁ CON EL SESION ID YA QUE LA COLUMNA ID EN CARRITO ES INT. 
                // HAY QUE MODIFICAR LA COLUMNA DE ESA TABLA O USAR EN SU DEFECTO UNA COLUMNA DE CORREO QUE SOPORTE VARCHAR PARA PONER EL SESSION ID EN SU DEFECTO




                $productToBasketInfo['productId'] = $productId;
                $productToBasketInfo['quantity'] = 1;
                $productToBasketInfo['productPrice'] = $categoryProductsFinal[$productId]['pv'];

                ((new GestionBDRepositorio))->insertIntoBasket($productToBasketInfo);

                echo '<pre>';
                var_dump($productToBasketInfo);
                echo '</pre>';

                // Lógica temporal para guardar en un carrito de $_SESSION. Debemos usar la tabla pedido.
                if (isset($_SESSION['basket'][$productId])) {
                    // Producto ya existe, incrementar la cantidad
                    $_SESSION['basket'][$productId]['cantidad']++;

                    if (isset($_SESSION['productsInBasket'])) {
                        $_SESSION['productsInBasket']++;
                    }
                } else {
                    // Producto no existe en el carrito, añadir con cantidad inicial de 1
                    if (((new GestionBDRepositorio))->checkProductExists($productId)) {
                        $_SESSION['basket'][$productId] = [
                            'cantidad' => 1
                        ];

                        if (isset($_SESSION['productsInBasket'])) {
                            $_SESSION['productsInBasket']++;
                        }
                    } else {
                        echo '<script>alert("¡OCURRIÓ UN ERROR! ESE ARTÍCULO NO EXISTE ")</script>';
                    }
                }


                header('location: index.php?ctl=showCategory&cat=' . $_GET['cat']);
            }
        }

        require __DIR__ . '/../../app/plantillas/category.php';
    }
}
