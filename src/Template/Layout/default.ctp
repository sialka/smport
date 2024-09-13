<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CCB';
//$usuario         = $this->request->session()->read('logado');
//$perfil          = $this->request->session()->read('perfil');
//$controller      = $this->request->params['controller'];
//$body            = $controller == "Panels" ? "" : "page-top";
//$wrapper         = $controller == "Panels" ? "" : "wrapper";
//$overflow        = $controller == "Panels" ? "overflow: hidden" : "";

?>

<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>
        <?= $cakeDescription ?>
    </title>

    <?= $this->Html->meta('icon') ?>

    <!-- Bootstrap CSS -->
    <?= $this->Html->css('sb-admin-2.css') ?>
    <?= $this->Html->css('sb-add.css') ?>

    <?= $this->Html->script('vendor/jquery/jquery.min.js') ?>
    <?= $this->Html->script('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>

    <style>
      .bg {
        /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#ffffff+0,f1f1f1+50,e1e1e1+51,f6f6f6+100;White+Gloss+%231 */
        background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(241,241,241,1) 50%,rgba(225,225,225,1) 51%,rgba(246,246,246,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
      }
      .bb {
        border: 1px solid lightgray;
        border-bottom: 1.5px solid gray;
        font-size: 2rem;
      }

      .no-radius{
        border-radius: 0px;
      }
    </style>

</head>

    <body class="">
        <?= $this->fetch('content') ?>
    </body>

</html>

