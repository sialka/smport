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

$cakeDescription = 'PIA';
use Cake\Network\Session;

$controller = $this->request->params['controller'];    

?>

<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $cakeDescription ?></title>

    <?= $this->Html->meta('icon') ?>

    <!-- Custom fonts for this template-->
    <?= $this->Html->css('fontawesome-free/css/all.min.css') ?>
    <?= $this->Html->css('login.css') ?>    

    <!-- link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" -->

    <?= $this->Html->css('sb-admin-2.css') ?>    
    <?= $this->Html->css('sb-add.css') ?>

    <?php
    if ($controller == 'Panels'){
        echo $this->Html->css('animation.css');
    }    
    ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <!-- Bootstrap core JavaScript-->
    <?= $this->Html->script('vendor/jquery/jquery.min.js') ?>
    <?= $this->Html->script('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>

    <!-- Core plugin JavaScript-->
    <?= $this->Html->script('vendor/jquery-easing/jquery.easing.min.js') ?>

    <!-- Custom scripts for all pages-->
    <?= $this->Html->script('sb-admin-2.min.js') ?>  
    
    <?php    
    

    if ($controller == 'Panels') {
        $class_param1 = "bg-black";
        $class_param2 = "container-fluid";
        $habilitar_painel = true;
    }else{
        $class_param1 = "bg-gradient-primary";
        $class_param2 = "container";
        $habilitar_painel = false;
    }
    
    ?>
    
</head>
<body class="<?= $class_param1 ?>">

    <div class="<?= $class_param2 ?>">
        <?= $this->fetch('content') ?>
    </div>

</body>
</html>
