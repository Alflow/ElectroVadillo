<?php

require_once __DIR__ . '/../../core/conexionBd.inc';
class GestionBDRepositorio
{
    public function getCategories(): array
    {
        $sql = 'SELECT id, nombre, imagen, descripcion FROM categoria';
        try {
            $con = ((new ConexionBd))->getConexion();
            $snt = $con->prepare($sql);
            $snt->execute();

            $arrayTemp = $snt->fetchAll(PDO::FETCH_ASSOC);
            // die(var_dump($arrayTemp));
            $arrayFinal = [];
            foreach ($arrayTemp as $index => $valor) {
                $arrayFinal[$valor['id']] = ['nombre' => $valor['nombre'], 'imagen' => $valor['imagen'], 'descripcion' => $valor['descripcion']];
            }

            return $arrayFinal;
            // var_dump($arrayCategorias);
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        } catch (Exception $ex) {
            throw $ex->getMessage();
        } finally {
            if (isset($snt)) {
                unset($snt);
            }
            if (isset($con)) {
                $con = null;
            }
        }
    }



    public function getClienteByECorreo($eCorreo)
    {
        $sql = 'SELECT id, direccion, cP, idPueblo eCorreo, pwd FROM cliente WHERE eCorreo = :eCorreo';
        try {
            $con = (new ConexionBd())->getConexion();
            $snt = $con->prepare($sql);
            $snt->bindParam(':eCorreo', $eCorreo);
            $snt->execute();
            $fila = $snt->fetch(PDO::FETCH_ASSOC);
            if ($fila === false) {
                throw new Exception('No hay socios con ese eCorreo', 100);
            } else {
                return $fila;
            }
        } catch (\PDOException $ex) {
            throw $ex;
        }
    }

    public function getProductsByCategory(int $category): array
    {
        $sql = 'SELECT codigo, descripcion, pv, stock, idCategoria FROM articulo
        WHERE idCategoria = :categoria';

        try {
            $con = ((new ConexionBd))->getConexion();
            $snt = $con->prepare($sql);
            $snt->bindParam(':categoria', $category, PDO::PARAM_STR);
            $snt->execute();


            $products = $snt->fetchAll(PDO::FETCH_ASSOC);

            return $products;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        } finally {
            if (isset($snt)) {
                unset($snt);
            }
            if (isset($con)) {
                $con = null;
            }
        }

        return $products;
    }

    // Función que comprueba tanto la existencia del producto como su disponibilidad. 
    // Si el producto no existe o no tiene más de 1 existencia, devolverá un false. 

    public function checkProductExists(string $codeProduct)
    {
        $sql = 'SELECT * from articulo WHERE codigo = :codeProduct AND stock > 0';

        try {
            $con = ((new ConexionBd))->getConexion();
            $snt = $con->prepare($sql);
            $snt->bindParam(':codeProduct', $codeProduct, PDO::PARAM_STR);
            $snt->execute();
            $product = $snt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        } finally {
            if (isset($snt)) {
                unset($snt);
            }
            if (isset($con)) {
                $con = null;
            }
        }

        if (($product) == false) {
            return false;
        } else {
            return true;
        }
    }




    public function insertIntoBasket(array $productToBasket, $buyer)
    {
        $sqlExists = 'SELECT * FROM dbo.CARRITO WHERE comprador = :comprador AND codArticulo = :productId';

        // En el caso de que NO  exista el producto en el carrito de nuestro cliente
        $sqlNotExistsInsert = 'INSERT INTO dbo.carrito (comprador, codArticulo, cantidad, pv) VALUES (:buyer, :codArt, :qtity, :price)';
        $sqlNotExistsUpdate = 'UPDATE dbo.articulo SET stock = stock -1 WHERE codigo = :codArt';

        // En el caso de que  SÍ  exista el producto en el carrito de nuestro cliente
        $sqlExistsUpdateBasket = 'UPDATE dbo.carrito SET cantidad = cantidad + 1 WHERE codArticulo = :codArt AND comprador = :buyer';



        try {
            $con = ((new ConexionBd))->getConexion();
            $con->beginTransaction();
            $snt = $con->prepare($sqlExists);

            $snt->bindParam(':comprador', $buyer);
            $snt->bindParam(':productId', $productToBasket['productId']);
            $snt->execute();
            $response = $snt->fetchAll(PDO::FETCH_ASSOC);

            // Si no existía en carrito ese producto pedido por ese comprador...
            if (empty($response)) {
                $snt = $con->prepare($sqlNotExistsInsert);

                $snt->bindParam(':buyer', $productToBasket['buyer']);
                $snt->bindParam(':codArt', $productToBasket['productId']);
                $snt->bindParam(':qtity', $productToBasket['quantity']);
                $snt->bindParam(':price', $productToBasket['productPrice']);


                if (!$snt->execute()) {
                    $con->rollBack();
                    throw new Exception('No ha sido posible la transacción');
                }
                $snt = $con->prepare($sqlNotExistsUpdate);
                $snt->bindParam(':codArt', $productToBasket['productId']);

                if (!$snt->execute()) {
                    $con->rollBack();
                    throw new Exception('No ha sido posible la transacción');
                }
            } else {
                $snt = $con->prepare($sqlExistsUpdateBasket);
                $snt->bindParam(':codArt', $productToBasket['productId']);
                $snt->bindParam(':buyer', $buyer);

                if (!$snt->execute()) {
                    $con->rollBack();
                    throw new Exception('No ha sido posible la transacción');
                }


                $snt = $con->prepare($sqlNotExistsUpdate);
                $snt->bindParam(':codArt', $productToBasket['productId']);

                if (!$snt->execute()) {
                    $con->rollBack();
                    throw new Exception('No ha sido posible la transacción');
                }
            }
            $con->commit();
        } catch (PDOException $ex) {
            $con->rollBack();
            throw $ex;
        }
    }

    public function substractFromBasket($productId, $clientId)
    {
        // $sqlExists = 'SELECT * FROM dbo.CARRITO WHERE comprador = :comprador AND codArticulo = :productId';

        //    EN CASO DE QUE SÍ EXISTA EN EL CARRITO DEL COMPRADOR ESE PRODUCTO 
        $sqlUpdateStock = 'UPDATE articulo SET stock = stock + 1 WHERE codigo = :codigo';
        $sqlSubstractOne = 'IF EXISTS (SELECT * FROM dbo.carrito WHERE codArticulo = :codArt and comprador = :comprador AND cantidad = 1)
        BEGIN
            DELETE FROM carrito WHERE codArticulo = :codArt and comprador = :comprador 
        END
        ELSE
        BEGIN
            UPDATE carrito SET cantidad = cantidad - 1 WHERE codArticulo = :codArt and comprador = :comprador
        END';




        try {

            $con = ((new ConexionBd))->getConexion();
            $con->beginTransaction();
            // $snt = $con->prepare($sqlUpdateStock);

            // $snt->bindParam(':codigo', $productId);
            // // $pruebita = $snt->execute();
            // // die(var_dump($pruebita));
            // if (!$snt->execute()) {
            //     $con->rollBack();
            //     throw new Exception('No ha sido posible la transacción');
            //     // exit();
            // }


            $snt2 = $con->prepare($sqlSubstractOne);
            $snt2->bindParam(':codArt', $productId);
            $snt2->bindParam(':comprador', $clientId);
            $pruebita2 = $snt2->execute();
            var_dump($pruebita2);

            // if (!$snt2->execute() ) {
            //     $con->rollBack();
            //     throw new Exception('No ha sido posible la transacción');
            //     // exit();
            // }

            $con->commit();
        } catch (PDOException $ex) {
            $con->rollBack();
            throw new Exception('No ha sido posible la transacción2');
        }
    }



    public function cancelBasket($idCliente)
    {
        $sqlSelect = 'SELECT * FROM dbo.carrito WHERE comprador = :buyer';

        $sqlUpdateStock = 'UPDATE dbo.articulo SET stock = stock + ? WHERE codigo = ?';

        $sqlDeleteCarrito = 'DELETE FROM dbo.carrito WHERE comprador = :buyer';


        try {
            $con = ((new ConexionBd)->getConexion());
            $con->beginTransaction();

            // Selección de productos en el carrito para el comprador

            $sntSelect = $con->prepare($sqlSelect);
            $sntSelect->bindParam(':buyer', $idCliente, PDO::PARAM_STR);
            $sntSelect->execute();
            $buyerProductsForCancel = $sntSelect->fetchAll(PDO::FETCH_ASSOC);



            // Preparación para actualizar stock
            $sntUpdateStock = $con->prepare($sqlUpdateStock);

            foreach ($buyerProductsForCancel as $index) {
                $sntUpdateStock->bindValue(1, $index['cantidad'], PDO::PARAM_INT);
                $sntUpdateStock->bindValue(2, $index['codArticulo'], PDO::PARAM_INT);
                $sntUpdateStock->execute();
            }

            // Eliminación de carrito
            $sntDeleteCart = $con->prepare($sqlDeleteCarrito);
            $sntDeleteCart->bindParam(':buyer', $idCliente, PDO::PARAM_STR);
            $sntDeleteCart->execute();

            $con->commit();
        } catch (PDOException $ex) {
            $con->rollback();
            throw $ex;
        }
    }

    // PENDIENTE IMPLEMENTAR ESTA FUNCIÓN SIN LAS COOKIES PERO EN EL ACCESS CONTROLLER. 
    public function updateCartBuyer($carritoId, $userId)
    {
        // Código para actualizar el carrito en la base de datos
        $sql = "UPDATE carrito SET comprador = :userId WHERE comprador = :carritoId";
        try {

            $con = ((new ConexionBd))->getConexion();
            $con->beginTransaction();
            $snt = $con->prepare($sql);

            $snt->bindParam(':userId', $userId);
            $snt->bindParam(':carritoId', $carritoId);

            if (!$snt->execute()) {
                $con->rollBack();
                throw new Exception('No ha sido posible la transacción');
            }
            $con->commit();
        } catch (PDOException $ex) {
            $con->rollBack();
            throw $ex;
        }
    }
}
