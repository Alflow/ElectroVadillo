<?php
// Ejemplo de controlador para página home de la aplicación
require_once __DIR__ . '/../Repositorio/GestionBDRepositorio.php';
class categoryController
{


    public function showCategory()
    {
        try {
            if (isset($_GET['cat'])) {
                $categoryProductsTemp = ((new GestionBDRepositorio)->getProductsByCategory($_GET['cat']));

                $categoryProductsFinal = [];
                foreach ($categoryProductsTemp as $index => $details) {
                    $categoryProductsFinal[$details['codigo']] =
                        [
                            'descripcion' => $details['descripcion'],
                            'pv' => $details['pv'],
                            'stock' => $details['stock'],
                            'idCategoria' => $details['idCategoria'],
                            'imagen' => $details['imagen']
                        ];
                }
                // var_dump($categoryProducts);
            }
        } catch (PDOException $ex) {
            echo ($ex->getMessage());
        }

        require __DIR__ . '/../../app/plantillas/category.php';
    }
}
