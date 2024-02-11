<?php
// Ejemplo de controlador para página home de la aplicación
require_once __DIR__ . '/../Repositorio/GestionBDRepositorio.php';
require_once __DIR__ . '/../../app/utilidades/sanitiza.inc';
class categoryController
{


    public function showCategory()
    {
        if(!isset($_SESSION['productsInBasket'])){
            $_SESSION['productsInBasket']=0;
        }


        if (isset($_GET['order'])) {

            $productId = sanea($_GET['order']);


            if (isset($_SESSION['basket'][$productId])) {
                // Producto ya existe, incrementar la cantidad
                $_SESSION['basket'][$productId]['cantidad']++;

                if(isset($_SESSION['productsInBasket'])){
                    $_SESSION['productsInBasket']++;
                }
            } else {
                // Producto no existe en el carrito, añadir con cantidad inicial de 1
                if (((new GestionBDRepositorio))->checkProductExists($productId)) {
                    $_SESSION['basket'][$productId] = [
                        'cantidad' => 1
                    ];

                    if(isset($_SESSION['productsInBasket'])){
                        $_SESSION['productsInBasket']++;
                    }
                } else {
                    echo '<script>alert("¡OCURRIÓ UN ERROR! ESE ARTÍCULO NO EXISTE ")</script>';
                }
            }


            header('location: index.php?ctl=showCategory&cat=' . $_GET['cat']);
        }

        if (isset($_SESSION['basket'])) {
            echo '<pre>';
            var_dump($_SESSION['basket']);

            echo '</pre>';
        }
        if (isset($_GET['cat'])) {
            try {

                $categories = ((new GestionBDRepositorio))->getCategories();

                $categoryProductsTemp = ((new GestionBDRepositorio)->getProductsByCategory($_GET['cat']));

                $categoryProductsFinal = [];
                foreach ($categoryProductsTemp as $index => $details) {
                    $categoryProductsFinal[$details['codigo']] =
                        [
                            'descripcion' => $details['descripcion'],
                            'pv' => $details['pv'],
                            'stock' => $details['stock'],
                            'idCategoria' => $details['idCategoria'],
                            // 'imagen' => $details['imagen']
                        ];
                }
                // var_dump($categoryProductsFinal);
            } catch (PDOException $ex) {
                echo ($ex->getMessage());
            }
        }

        require __DIR__ . '/../../app/plantillas/category.php';
    }
}
