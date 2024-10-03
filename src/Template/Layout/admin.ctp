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

$cakeDescription = 'Sistema de Apoio';
$usuario         = $this->request->session()->read('logado');
$perfil          = $this->request->session()->read('perfil');
$controller      = $this->request->params['controller'];

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
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <?= $this->Html->css('styles.css') ?>
    <?= $this->Html->script('vendor/jquery/jquery.min.js') ?>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>


    <!-- Custom fonts for this template-->
    <?php # $this->Html->css('fontawesome-free/css/all.min.css') ?>
    <?php # $this->Html->css('fontawesome-free/css/fontawesome.min.css') ?>
    <?php #$this->Html->css('font-awesome-4.7.0/css/font-awesome.min.css') ?>

    <?php #$this->Html->css('bootstrap.min') ?>
    <?php # $this->Html->css('sb-admin-2.css') ?>
    <?php # $this->Html->css('sb-add.css') ?>
    <?= $this->Html->css('typeahead.css') ?>

    <!-- Date Picker -->
    <?= $this->Html->css('date-picker/bootstrap-datepicker.css') ?>
    <?= $this->Html->css('date-picker/bootstrap-datepicker3.css') ?>

    <!-- Bootstrap core JavaScript-->
    <?php # $this->Html->script('vendor/jquery/jquery.min.js') ?>
    <?php # $this->Html->script('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>

    <!-- Core plugin JavaScript-->
    <?= $this->Html->script('vendor/jquery-easing/jquery.easing.min.js') ?>

    <!-- Custom scripts for all pages-->
    <?php # $this->Html->script('sb-admin-2.js') ?>
    <?php # $this->Html->script('mask/jquery.mask.min.js') ?>
    <?= $this->Html->script('date-picker/js/bootstrap-datepicker.js') ?>
    <?= $this->Html->script('date-picker/locales/bootstrap-datepicker.pt-BR.min.js') ?>
    <?= $this->Html->script('typeahead.js') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <?= $this->Html->script('scripts.js') ?>
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script-->
    <!--script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script-->

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>



    <?php // Info controller $this->fetch('title') ?>
</head>
<body id="sb-nav-fixed">

    <!-- Sidebar :: col [1/2] -->
    <?php if ($controller != 'Panels'): ?>
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-secondary">
            <div class=''>
                <button class="btn btn-link btn-sm order-1 order-lg-0 me-lg-0" id="sidebarToggle" href="#">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand pt-0" href="/admin">
                    <i class="fa-solid fa-pen-fancy"></i>
                    Agenda
                </a>
            </div>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Admin</div>
                            <a class="nav-link" href="/dashboard">
                                <div class="sb-nav-link-icon">
                                    <i class="fa-regular fa-calendar-check"></i>
                                </div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Interface</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 512 512">
                                        <path d="M0 416c0 17.7 14.3 32 32 32l54.7 0c12.3 28.3 40.5 48 73.3 48s61-19.7 73.3-48L480 448c17.7 0 32-14.3 32-32s-14.3-32-32-32l-246.7 0c-12.3-28.3-40.5-48-73.3-48s-61 19.7-73.3 48L32 384c-17.7 0-32 14.3-32 32zm128 0a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zM320 256a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm32-80c-32.8 0-61 19.7-73.3 48L32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l246.7 0c12.3 28.3 40.5 48 73.3 48s61-19.7 73.3-48l54.7 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-54.7 0c-12.3-28.3-40.5-48-73.3-48zM192 128a32 32 0 1 1 0-64 32 32 0 1 1 0 64zm73.3-64C253 35.7 224.8 16 192 16s-61 19.7-73.3 48L32 64C14.3 64 0 78.3 0 96s14.3 32 32 32l86.7 0c12.3 28.3 40.5 48 73.3 48s61-19.7 73.3-48L480 128c17.7 0 32-14.3 32-32s-14.3-32-32-32L265.3 64z"/>
                                    </svg>
                                </div>
                                Sistema
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="/Horarios">Horários</a>
                                    <a class="nav-link" href="/Municipios">Municipios</a>
                                    <a class="nav-link" href="/Localidades">Localidades</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 448 512">
                                        <path d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L64 64C28.7 64 0 92.7 0 128l0 16 0 48L0 448c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-256 0-48 0-16c0-35.3-28.7-64-64-64l-40 0 0-40c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L152 64l0-40zM48 192l80 0 0 56-80 0 0-56zm0 104l80 0 0 64-80 0 0-64zm128 0l96 0 0 64-96 0 0-64zm144 0l80 0 0 64-80 0 0-64zm80-48l-80 0 0-56 80 0 0 56zm0 160l0 40c0 8.8-7.2 16-16 16l-64 0 0-56 80 0zm-128 0l0 56-96 0 0-56 96 0zm-144 0l0 56-64 0c-8.8 0-16-7.2-16-16l0-40 80 0zM272 248l-96 0 0-56 96 0 0 56z"/>
                                    </svg>
                                </div>
                                Eventos
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link" href="/Cultos">Cultos</a>
                                    <a class="nav-link" href="/Ensaio">Ensaios Locais</a>
                                    <a class="nav-link" href="/Regional">Ensaios Regionais</a>
                                    <a class="nav-link" href="/Batismo">Batismos</a>
                                    <a class="nav-link" href="/Reuniao">Reuniões</a>
                                    <a class="nav-link" href="/Ordenacao">Ordenações</a>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        Admin
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <?= $this->fetch('content') ?>
            </div>





    <?php endif; ?>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Atenção</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Tem certeza que deseja sair do sistema ?</div>
                <div class="modal-footer">

                    <a class="btn btn-success no-radius" href="/Users/logout">
                        <i class="fa fa-check"></i>
                        Sair
                    </a>


                    <button class="btn btn-link no-link text-primary" type="button" data-dismiss="modal">
                        <i class="fa fa-reply"></i>
                        Cancelar
                    </button>

                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>
    // Validações de Formulario Bootstrap

    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false)
    })();

</script>