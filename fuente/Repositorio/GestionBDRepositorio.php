<?php

require_once __DIR__ . '/../../core/conexionBd.inc';
class GestionBDRepositorio
{
    public function getCategorias(): array
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
}
