<!-- PENDIENTE CHEKEAR EXISTENCIA DE PRODUCTO EN TABLA CARRITO EN TODAS LAS FUNCIONALIDADES DE CATEGORÍA -->


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
        if (!isset($_SESSION['totalPrice'])) {
            $_SESSION['totalPrice'] = 0;
        }

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

                if (!((new GestionBDRepositorio))->checkProductExists($productId)) {
                    echo ('<script>alert("¡OCURRIÓ UN ERROR! ESE ARTÍCULO NO EXISTE ")</script>');
                    header('location: index.php?ctl=showCategory&cat=' . $_GET['cat']);
                    exit();
                }

                $productToBasketInfo['productId'] = $productId;
                $productToBasketInfo['quantity'] = 1;
                $productToBasketInfo['productPrice'] = $categoryProductsFinal[$productId]['pv'];
                // AÑADIDA CATEGORÍA AL ARRAY DEL PRODUCTO PEDIDO
                $productToBasketInfo['category'] = $categoryProductsFinal[$productId]['idCategoria'];

                if ((((new GestionBDRepositorio))->checkProductExists($productId)) == true) {
                } else {
                    var_dump('ERRRRRRRORRRRRR, NO EXISTE ESE PRODUCTO O NO HAY EXISTENCIAS');
                }

                // echo '<pre>';
                // var_dump($productToBasketInfo);
                // echo '</pre>';

                // Lógica temporal para guardar en un carrito de $_SESSION. Debemos usar la tabla pedido.
                if (isset($_SESSION['basket'][$productId])) {
                    // Producto ya existe en el session carrito, incrementar la cantidad en la posición cantidad del producto del carrito y añadir en tabla carrito
                    if (((new GestionBDRepositorio))->checkProductExists($productId)) {


                        ((new GestionBDRepositorio))->insertIntoBasket($productToBasketInfo, $productToBasketInfo['buyer']);
                    }
                    $_SESSION['basket'][$productId]['cantidad']++;
                    $_SESSION['basket'][$productId]['precio'] += $productToBasketInfo['productPrice'];
                    $_SESSION['basket'][$productId]['categoria'] = $productToBasketInfo['category'];

                    if (isset($_SESSION['productsInBasket'])) {
                        $_SESSION['productsInBasket']++;
                    }

                    $_SESSION['totalPrice'] += $productToBasketInfo['productPrice'];
                } else {
                    // Producto no existe en el carrito, añadir con cantidad inicial de 1
                    if (((new GestionBDRepositorio))->checkProductExists($productId)) {
                        $_SESSION['basket'][$productId] = [
                            'cantidad' => 1, 'precio' => $productToBasketInfo['productPrice'], 'categoria' => $productToBasketInfo['category']
                        ];

                        if (isset($_SESSION['productsInBasket'])) {
                            $_SESSION['productsInBasket']++;
                        }
                        $_SESSION['totalPrice'] += $productToBasketInfo['productPrice'];
                        ((new GestionBDRepositorio))->insertIntoBasket($productToBasketInfo, $productToBasketInfo['buyer']);
                    } else {
                        echo '<script>alert("¡OCURRIÓ UN ERROR! ESE ARTÍCULO NO EXISTE ")</script>';
                        header('location: index.php?ctl=showCategory&cat=' . $_GET['cat']);
                        exit();
                    }
                }


                header('location: index.php?ctl=showCategory&cat=' . $_GET['cat']);
                exit();
            }


            // -----------------------------SI SE ESTÁ RESTANDO UN PRODUCTO

            if (isset($_GET['substract'])) {
                $productIdSubstract = sanea($_GET['substract']);


                // var_dump($_SESSION['basket'][$productIdSubstract]);

                if (isset($_SESSION['basket'][$productIdSubstract])) {
                    if ($_SESSION['basket'][$productIdSubstract]['cantidad'] > 1) {
                        $_SESSION['basket'][$productIdSubstract]['cantidad']--;
                    } else {
                        // var_dump($_SESSION['basket'][$productIdSubstract]);
                        unset($_SESSION['basket'][$productIdSubstract]);
                        
                        // var_dump($_SESSION['basket'][$productIdSubstract]);
                    }
                }
                if (isset($_SESSION['socio'])) {
                    // echo '<pre>';
                    // var_dump($_SESSION['socio']);
                    // echo '</pre>';
                    ((new GestionBDRepositorio))->substractFromBasket2($productIdSubstract, $_SESSION['socio']['id']);
                    $_SESSION['productsInBasket']--;
                } else {
                    ((new GestionBDRepositorio))->substractFromBasket2($productIdSubstract, session_id());
                    $_SESSION['productsInBasket']--;
                }

                header('location: index.php?ctl=showCategory&cat=' . $_GET['cat']);
                exit();
            }
        }

        require __DIR__ . '/../../app/plantillas/category.php';
    }
    public function cancelCart()
    {
        $arrayCategorias = ((new GestionBDRepositorio)->getCategories());
        (new GestionBDRepositorio)->cancelBasket($_SESSION['socio']['id']);
        unset($_SESSION['basket']);
        $_SESSION['totalPrice'] = 0;
        // echo '<pre>';
        // var_dump($arrayPruebaCancel);
        // echo '</pre>';




        header('location: index.php?ctl=inicio');
    }
}
