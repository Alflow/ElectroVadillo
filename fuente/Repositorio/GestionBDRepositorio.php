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


    public function checkProductExists(string $codeProduct)
    {
        $sql = 'SELECT 1 from articulo WHERE codigo = :codeProduct';

        try {
            $con = ((new ConexionBd))->getConexion();
            $snt = $con->prepare($sql);
            $snt->bindParam(':codeProduct', $codeProduct, PDO::PARAM_STR);
            $snt->execute();
            $product = $snt->fetchAll(PDO::FETCH_ASSOC);
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

        if (empty($product)) {
            return false;
        } else {
            return true;
        }
    }


    public function insertIntoBasket(array $productToBasket)
    {
        $sql = 'INSERT INTO dbo.carrito (comprador, codArticulo, cantidad, pv) VALUES (:buyer, :codArt, :qtity, :price)';

        try {

            $con = ((new ConexionBd))->getConexion();
            $con->beginTransaction();
            $snt = $con->prepare($sql);

            $snt->bindParam(':buyer', $productToBasket['buyer']);
            $snt->bindParam(':codArt', $productToBasket['productId']);
            $snt->bindParam(':qtity', $productToBasket['quantity']);
            $snt->bindParam(':price', $productToBasket['productPrice']);


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


    // PENDIENTE IMPLEMENTAR ESTA FUNCIÓN SIN LAS COOKIES PERO EN EL ACCESS CONTROLLER. 
    public function updateCartBuyer($carritoId, $userId)
    {
        // Código para actualizar el carrito en la base de datos
        $sql = "UPDATE Carrito SET comprador = :userId WHERE comprador = :carritoId";
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

        // Aquí, prepara y ejecuta la consulta con $userId y $carritoId
    }
}
