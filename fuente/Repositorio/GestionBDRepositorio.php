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

    public function getProductsByCategory(int $category): array
    {
        $sql = 'SELECT articulo.codigo, articulo.descripcion, articulo.pv, articulo.stock, articulo.idCategoria, categoria.imagen FROM articulo JOIN categoria ON (articulo.idCategoria = categoria.id)
        WHERE articulo.idCategoria = :categoria';

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
}
