<?php


$mapeoRutas =
  array(
    'inicio' =>
    array('controller' => 'defaultController', 'action' => 'inicio'),
    'showCategory' =>
    array('controller' => 'categoryController', 'action' => 'showCategory'),
    'login' =>
    array('controller' => 'LoginController', 'action' => 'checkLoginForm'),
    'logOut' =>
    array('controller' => 'LoginController', 'action' => 'logOut'),
    'cancelBasket' =>
    array('controller' => 'CategoryController', 'action' => 'cancelCart'),
    'signUp' =>
    array('controller' => 'signUpController', 'action' => 'signUp')
  );




      //  categoryController