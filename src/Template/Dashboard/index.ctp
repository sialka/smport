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
      <h5 class="text-center mt-2"><strong>AGENDA</strong></h5>

      <!-- Cultos Semanal -->

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
                echo "<li class='list-group-item active'>Horário: <strong>{$hora}</strong></li>";
                foreach($igrejas as $igreja){
                  echo "<li class='list-group-item text-center text-dark'><strong>{$igreja}</strong></li>";
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

        <?php if(count($regional) == 0){ ?>

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

$(document).ready(function() {

  // ToolTip
  $('[data-toggle="tooltip"]').tooltip();

  // Modal
  <?= $this->element('modal_confirm'); ?>

});

function changeId(id){
  console.log(id);
  $('#municipio_id').val(id);
}

</script>