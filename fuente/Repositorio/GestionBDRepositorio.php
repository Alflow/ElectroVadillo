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


}
