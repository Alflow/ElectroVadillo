<?php
function nl2br2(string $string): string
{ //$cad = str_replace(array("\r\n", "\r", "\n"), "<br>", $string);
  return nl2br(stripcslashes($string));
}
