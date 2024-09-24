<?php

  $data_week = getdate();

  $dias_da_semana = $schema[$semana_atual];

  if (count($dias_da_semana) < 5){
    $dias = 7-count($dias_da_semana);
    for ($i = 1; $i <= $dias; $i++) {
      array_push($dias_da_semana, $i);
    }
  }

?>
<style>
  .list-group-choice-municipios{
    max-height: 300px;
    margin-bottom: 10px;
    overflow:scroll;
    overflow-x: hidden;
    -webkit-overflow-scrolling: touch;
}
</style>
<div class="container-fluid m-0">

  <div class="row">
    <div class="col-xl-4 offset-xl-4 col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-sm-12 col-xs-12">

      <?= $this->Html->image('logo.jpg', ["class"=>"img-fluid mx-auto d-block mt-2", "style" => "height:60px"]); ?>
      <h5 class="text-center mt-2">
        <a href="#" id="admin" style="text-decoration: none; color: gray">
          <strong>AGENDA</strong>
        </a>
      </h5>

      <!-- CULTOS -->

      <h4 class="text-center mt-3 bg bb">CALENDARIO DE CULTOS</h4>

      <button class="btn btn-block no-radius btn-outline-primary mb-2" data-toggle="modal" data-target="#exampleModal">LOCAL: <?= $btn_local_title; ?></button>

      <ul class="list-group no-radius">
      <?php foreach($semana as $key => $dia): ?>

        <?php
          $class = $semana_us_br[$data_week['weekday']] == $dia ? "text-primary" : "text-dark";
        ?>

          <li class='list-group-item no-radius text-center text-dark' style="background-color: #fafafa; -width: 50%; -margin-left: 10rem">
            <a class="btn btn-block no-radius p-0 m-0 text-center <?= $class; ?>"
              data-toggle="collapse" href="#<?= $dia ?>" role="button" aria-expanded="false" aria-controls="<?= $dia ?>">
              <strong><?= "{$dias_da_semana[$key]} - {$dia}" ?></strong>
            </a>
          </li>

          <div class="collapse" id="<?= $dia; ?>">
            <ul class="list-group no-radius mt-2 mb-4" -style='border: 1px solid #4e73df;'>
            <?php

            foreach($agenda[$dia] as $hora => $igrejas){


              if(count($igrejas) >0 ) {

                // ToDo Criar componente

                echo "<li class='list-group-item active'>Horário: <strong>{$hora}</strong></li>";

                foreach($igrejas as $igreja){

                    $texto = '';

                    if($igreja[1] != '0'){
                      $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16" fill="currentColor"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/></svg>';
                      $texto .= '<span class="text-warning">' . $svg . '</span> ' . '<strong>'. $igreja[0] . '</strong>';
                    }else{
                      $texto .= $igreja[0];
                    }

                    if($igreja[2] != ''){
                      echo "<a href='{$igreja[2]}' style='text-decoration: none; color: #13293D'><li class='list-group-item text-center'>{$texto}</li></a>";
                    }else{
                      echo "<li class='list-group-item text-center text-dark'><strong>{$texto}</strong></li>";
                    }





                }
              }
            }
            ?>
            </ul>
          </div>


      <?php endforeach; ?>
      </ul>

      <!-- ENSARIO LOCAL -->
      <h4 class="text-center mt-3 bg bb"
        data-toggle="collapse" href="#ensaio" role="button" aria-expanded="false" aria-controls="ensaio">
        ENSAIOS LOCAIS
      </h4>

      <div class="collapse" id="ensaio">

        <?php if(count($ensaio) == 0){ ?>

          <p class='text-center'>Nenhum Ensaio Local para essa semana.</p>

        <?php }else{ ?>

          <ul class="list-group no-radius mb-5">
          <?php
          foreach($ensaio as $datas => $locais){
            echo "<li class='list-group-item no-radius active'>Data: <strong>{$datas}</strong></li>";
            foreach($locais as $local){
              echo "<li class='list-group-item no-radius text-center text-dark'><strong>{$local}</strong></li>";
            }
          }
          ?>
          </ul>

        <?php } ?>

      </div>

      <!-- ENSARIO REGIONAL -->
      <h4 class="text-center mt-3 bg bb"
        data-toggle="collapse" href="#regional" role="button" aria-expanded="false" aria-controls="regional">
        ENSAIOS REGIONAIS
      </h4>

      <div class="collapse" id="regional">

        <?php if(count($regional) == 0){ ?>

          <p class='text-center'>Nenhum Ensaio Regional cadastrado para este Município.</p>

        <?php }else{ ?>

          <ul class="list-group no-radius mb-5">
          <?php
          foreach($regional as $datas => $locais){
            echo "<li class='list-group-item no-radius active'>Data: <strong>{$datas}</strong></li>";
            foreach($locais as $local){
              $info = explode('-', $local);
              $hora = $info[0];
              $cidade = $info[1];
              $localidade = $info[2];
              echo "<li class='list-group-item no-radius text-center text-dark'><strong>{$localidade}</strong><br><small>{$hora} - {$cidade}</small></li>";
            }
          }
          ?>
          </ul>

        <?php } ?>

      </div>

      <!-- BATISMO -->
      <h4 class="text-center mt-3 bg bb"
        data-toggle="collapse" href="#batismo" role="button" aria-expanded="false" aria-controls="batismo">
        BATISMOS
      </h4>

      <div class="collapse" id="batismo">

        <?php if(count($batismo) == 0){ ?>

          <p class='text-center'>Nenhum Batismo cadastrado para esse Município.</p>

        <?php }else{ ?>

          <ul class="list-group no-radius mb-5">
          <?php
          foreach($batismo as $datas => $locais){
            echo "<li class='list-group-item no-radius active'>Data: <strong>{$datas}</strong></li>";
            foreach($locais as $local){
              $info = explode('-', $local);
              $hora = $info[0];
              $cidade = $info[1];
              $localidade = $info[2];
              echo "<li class='list-group-item no-radius text-center text-dark'><strong>{$localidade}</strong><br><small>{$hora} - {$cidade}</small></li>";
            }
          }
          ?>
          </ul>

        <?php } ?>

      </div>

      <!-- REUNIÕES -->
      <h4 class="text-center mt-3 bg bb"
        data-toggle="collapse" href="#reunioes" role="button" aria-expanded="false" aria-controls="batismo">
        REUNIÕES
      </h4>

      <div class="collapse" id="reunioes">
        <?php if(count($reunioes) == 0){ ?>
          <p class='text-center'>Nenhum Reunião cadastrada.</p>
        <?php }else{ ?>
          <ul class="list-group no-radius mb-5">
          <?php
          foreach($reunioes as $datas => $locais){
            echo "<li class='list-group-item no-radius active'>Data: <strong>{$datas}</strong></li>";
            foreach($locais as $local){
              $info = explode('-', $local);
              $hora = $info[0];
              $cidade = $info[1];
              $localidade = $info[2];
              echo "<li class='list-group-item no-radius text-center text-dark'><strong>{$localidade}</strong><br><small>{$hora} - {$cidade}</small></li>";
            }
          }
          ?>
          </ul>
        <?php } ?>
      </div>

      <!--ORDENAÇÕES -->
      <h4 class="text-center mt-3 bg bb"
        data-toggle="collapse" href="#ordenacoes" role="button" aria-expanded="false" aria-controls="batismo">
        ORDENAÇÕES
      </h4>

      <div class="collapse" id="ordenacoes">

        <?php if(count($ordenacoes) == 0){ ?>
          <p class='text-center'>Nenhuma Ordenação cadastrada.</p>
        <?php }else{ ?>
          <ul class="list-group no-radius mb-5">
          <?php
          foreach($ordenacoes as $datas => $locais){
            echo "<li class='list-group-item no-radius active'>Data: <strong>{$datas}</strong></li>";
            foreach($locais as $local){
              $info = explode('-', $local);
              $hora = $info[0];
              $cidade = $info[1];
              $localidade = $info[2];
              echo "<li class='list-group-item no-radius text-center text-dark'><strong>{$localidade}</strong><br><small>{$hora} - {$cidade}</small></li>";
            }
          }
          ?>
          </ul>
        <?php } ?>

      </div>

    </div>
  </div>

</div>

<!-- Modal Filtro -->
<?= $this->Form->create("", array('class' => 'form-inline p-0', 'type' => 'post')) ?>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">


                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Municipios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                  <div class="form-row normal mt-2">
                      <div class="col-12">
                        <ul class="list-group list-group-choice-municipios p-1">
                          <?php
                          # echo "<li class='list-group-item list-group-item-action active'><strong>DEFINIR:</strong></li>";
                          foreach($municipios as $key => $municipio){


                            echo "<li class='list-group-item list-group-item-action text-center text-dark'>
                              <a href='#' onclick='changeId({$key})'>
                              <strong>{$municipio}</strong>
                              </a>
                            </li>";

                          }
                          ?>
                        </ul>
                        <?=
                            $this->Form->input('municipio_id',
                                    array(
                                        'id'          => 'municipio_id',
                                        'class'       => 'form-control hide',
                                        'type'        => 'number',
                                        'style'       => 'display: none',
                                        'placeholder' => '0',
                                        'div'         => false,
                                        'label'       => false,
                                    )
                            )
                            ?>
                      </div>
                  </div>
                </div>

                <div class="modal-footer bg-footer text-center">

                    <button type="submit" class="btn btn-success normal no-radius">
                        <i class="fa fa-check pr-1"></i>
                        Confirmar
                    </button>
                    <button type="button" class="btn btn-link no-link text-primary normal" data-dismiss="modal">Cancelar</button>

                </div>



            </div>
        </div>
    </div>

<?= $this->Form->end() ?>

<script>

let clicou = 0

$(document).ready(function() {

  // ToolTip
  $('[data-toggle="tooltip"]').tooltip();

  // Modal
  <?= $this->element('modal_confirm'); ?>


  $('#admin').on('click', function(){
      // minha ação aqui
      clicou = clicou + 1
      if (clicou >= 5){
        clicou = 0
        $("#admin").attr("href", "/admin")
      }
  });

});

function changeId(id){
  console.log(id);
  $('#municipio_id').val(id);
}



</script>