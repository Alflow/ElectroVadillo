<?php
function sanea($cadena)
{ $cad = $cadena;
  $cad = htmlspecialchars(stripslashes(trim($cadena)));
  return $cad;
}
