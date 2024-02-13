<?php
// Ejemplo de controlador para la página de categoría de la aplicación
require_once __DIR__ . '/../Repositorio/GestionBDRepositorio.php';
require_once __DIR__ . '/../../app/utilidades/sanitiza.inc';

class categoryController
{
    public function showCategory()
    {
        
        // Asegurar que hay una variable de sesión para el carrito
        if (!isset($_SESSION['productsInBasket'])) {
            $_SESSION['productsInBasket'] = 0;
            $_SESSION['basket'] = array();
        }


        if (isset($_SESSION['socio']) && isset($_COOKIE['carrito_id'])) {
            $userId = $_SESSION['socio']['id'];
            $carritoId = $_COOKIE['carrito_id'];
            // Actualiza el carrito para establecer el comprador como el usuario que acaba de iniciar sesión
            ((new GestionBDRepositorio))->updateCartBuyer($carritoId, $userId);

            // Elimina la cookie del carrito ya que ahora está vinculada a un usuario registrado
            setcookie('carrito_id', "", time() - 3600, "/");
            // unseteamos
            unset($_SESSION['carrito_id']);
        }


        if (isset($_GET['cat'])) {
            try {
                // Obtener categorías y productos de la categoría solicitada
                $categories = ((new GestionBDRepositorio))->getCategories();
                $categoryProductsTemp = ((new GestionBDRepositorio)->getProductsByCategory($_GET['cat']));

                // Construir la estructura final de los productos
                $categoryProductsFinal = [];
                foreach ($categoryProductsTemp as $index => $details) {
                    $categoryProductsFinal[$details['codigo']] = [
                        'descripcion' => $details['descripcion'],
                        'pv' => $details['pv'],
                        'stock' => $details['stock'],
                        'idCategoria' => $details['idCategoria'],
                    ];
                }
            } catch (PDOException $ex) {
                echo $ex->getMessage();
                exit;
            }




            // Si se solicita añadir un producto al carrito
            if (isset($_GET['order'])) {
                $productId = sanea($_GET['order']);
                $productToBasketInfo = [
                    'productId' => $productId,
                    'quantity' => 1,
                    'productPrice' => $categoryProductsFinal[$productId]['pv']
                ];

                // Si el usuario no está registrado, se utiliza un identificador único almacenado en una cookie
                if (!isset($_SESSION['socio'])) {
                    if (!isset($_COOKIE['carrito_id'])) {
                        $carritoId = uniqid('carrito_', true);
                        setcookie('carrito_id', $carritoId, time() + 86400, "/"); // La cookie expira en 24 horas
                    } else {
                        $carritoId = $_COOKIE['carrito_id'];
                    }
                    $_SESSION['carrito_id'] = $carritoId;
                    $productToBasketInfo['buyer'] = $carritoId;
                } else {
                    // El usuario está registrado, usar su ID
                    $productToBasketInfo['buyer'] = $_SESSION['socio']['id'];
                }

                
                // Insertar el producto en el carrito de la base de datos
                ((new GestionBDRepositorio))->insertIntoBasket($productToBasketInfo);

                // Redirigir para evitar reenvíos de formulario
                header('Location: index.php?ctl=showCategory&cat=' . $_GET['cat']);
                exit;
            }
        }

        // Incluir la plantilla de vista para la categoría
        require __DIR__ . '/../../app/plantillas/category.php';
    }
}