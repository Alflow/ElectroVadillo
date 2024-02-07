<?php
// Ejemplo de controlador para página home de la aplicación
require_once __DIR__ . '/../Repositorio/GestionBDRepositorio.php';
require_once __DIR__ . '/../../app/utilidades/sanitiza.inc';
class categoryController
{


    public function showCategory()
    {
        if (isset($_GET['order'])) {
            // pendiente verificación de integridad de los datos. Se debe comprobar que el producto existe en la tabla de productos.
            // Si el código es correcto, hacer el push, en caso contrario mostrar un error
            $_SESSION['basket'][] = [sanea($_GET['order'])];

            header('location: index.php?ctl=showCategory&cat=' . $_GET['cat']);
        }

        if (isset($_SESSION['basket'])) {
            var_dump($_SESSION['basket']);
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
