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

  .bc {
    background-color: lightgray;
  }

  .list-group-choice-municipios{
    max-height: 300px;
    margin-bottom: 10px;
    overflow:scroll;
    overflow-x: hidden;
    -webkit-overflow-scrolling: touch;
  }
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
    border-radius: 0px !important;
  }
  .fixed {
    position: fixed;
    bottom:0;
    left:0;
  }
  .accordion{
    --bs-accordion-btn-focus-border-color: black !important;
    --bs-accordion-active-color: black !important;
  }
</style>


<div class="col-xxl-4 offset-xxl-4 col-xl-6 offset-xl-3 col-md-8 offset-md-2 col-sm-12 col-xs-12 bc" style="height: 100vh">

  <nav class='navbar navbar-light bg-dark p-0' -class="sb-topnav navbar navbar-expand navbar-dark bg-dark col-12">
      <!-- Navbar Brand-->
      <div class="container-fluid">

        <div class='col-4 text-left'>
          <a class="btn btn-block no-radius text-white" id="admin" href="#">
            <i class="fa-regular fa-calendar-check fa-xl"></i>
          </a>
        </div>

        <div class='col-4 text-center'>
          <div class="align-self-center">
            <div style='font-size: 1.2em' class='text-white'>Agenda</div>
            <div style='font-size: 0.7em' class='text-white text-uppercase'><?= $btn_local_title; ?></div>
          </div>
        </div>

        <div class='col-4 text-end'>
          <button class="btn btn-block no-radius text-white text-uppercase -bg-danger" data-bs-toggle="modal" data-bs-target="#modalCidade">
            <i class="fa-solid fa-location-dot fa-xl"></i>
          </button>
        </div>

      </div>
  </nav>

  <main>
    <div class="accordion no-radius p-3" id="accordionExample">

      <!-- CULTOS -->
      <div class="accordion-item no-radius">
        <?= $this->element('accordion_category', [ 'name' => 'CULTOS', 'target' => 'collapse1' ]); ?>

        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
          <div class="accordion-body p-2">

          <ul class="list-group no-radius">
            <?php foreach($semana as $key => $dia): ?>

              <?php
                $class = $semana_us_br[$data_week['weekday']] == $dia ? "text-dark" : "text-secondary";
                $icon1 = $semana_us_br[$data_week['weekday']] == $dia ? '<i class="fa-solid fa-angles-left"></i>' : "";
                $icon2 = $semana_us_br[$data_week['weekday']] == $dia ? '<i class="fa-solid fa-angles-right"></i>' : "";
                $corF  = $semana_us_br[$data_week['weekday']] == $dia ? "bg-success" : "bg-secondary";
                $corT  = $semana_us_br[$data_week['weekday']] == $dia ? "text-success" : "text-secondary";

                $dia_calendario = strlen($dias_da_semana[$key]) == 1 ? '0'.$dias_da_semana[$key] : $dias_da_semana[$key];
              ?>

              <li class='list-group-item no-radius text-left text-dark bg-light mb-1 px-2'>
                <a class="btn btn-block no-radius p-0 m-0 text-left <?= $class; ?>" data-bs-toggle="collapse" href="#<?= $dia ?>" role="button" aria-expanded="false" aria-controls="<?= $dia ?>">
                  <strong>
                    <?= "<span style='border-radius: 50%; font-size: 0.8rem' class='text-light {$corF} p-1 m-0'>{$dia_calendario}</span><span class='{$corT}'> {$dia}</span>"; ?>
                  </strong>
                </a>
              </li>

              <div class="collapse" id="<?= $dia; ?>">
                <ul class="list-group no-border no-radius mt-2 mb-2">

                <?php
                foreach($agenda[$dia] as $hora => $igrejas){
                  if(count($igrejas) >0 ) {
                    echo $this->element('list_church', [ 'hora' => $hora, 'igrejas' => $igrejas ]);
                  }
                }
                ?>

                </ul>
              </div>


            <?php endforeach; ?>
          </ul>

          </div>
        </div>
      </div>

      <!-- ENSAIOS LOCAIS -->
      <div class="accordion-item">
        <?= $this->element('accordion_category', [ 'name' => 'ENSAIOS LOCAIS', 'target' => 'collapse2' ]); ?>

        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
          <div class="accordion-body p-2">
            <?= $this->element('list_event', [ 'dados' => $ensaio, 'msg' => 'Nenhuma ensaio local registrado' ]); ?>
          </div>
        </div>
      </div>

      <!-- ENSAIOS REGIONAIS -->
      <div class="accordion-item">
        <?= $this->element('accordion_category', [ 'name' => 'ENSAIOS REGIONAIS', 'target' => 'collapse3' ]); ?>

        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
          <div class="accordion-body p-2">
            <?= $this->element('list_event', [ 'dados' => $regional, 'msg' => 'Nenhum ensaio regional registrado' ]); ?>
          </div>
        </div>
      </div>

      <!-- BATISMOS -->
      <div class="accordion-item">
        <?= $this->element('accordion_category', [ 'name' => 'BATISMOS', 'target' => 'collapse4' ]); ?>

        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
          <div class="accordion-body p-2">
            <?= $this->element('list_event', [ 'dados' => $batismo, 'msg' => 'Nenhum batismo registrado' ]); ?>
          </div>
        </div>
      </div>

      <!-- REUNIÕES -->
      <div class="accordion-item">
        <?= $this->element('accordion_category', [ 'name' => 'REUNIÕES', 'target' => 'collapse5' ]); ?>

        <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
          <div class="accordion-body p-2">
            <?= $this->element('list_event', [ 'dados' => $reunioes, 'msg' => 'Nenhuma reunião registrada' ]); ?>
          </div>
        </div>
      </div>

      <!-- ORDENAÇÕES -->
      <div class="accordion-item">
        <?= $this->element('accordion_category', [ 'name' => 'ORDENAÇÕES', 'target' => 'collapse6' ]); ?>

        <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
          <div class="accordion-body p-2">
            <?= $this->element('list_event', [ 'dados' => $ordenacoes, 'msg' => 'Nenhuma ordenação registrada' ]); ?>
          </div>
        </div>
      </div>

    </div>
  </main>

  <div class="col-xxl-4 offset-xxl-4 col-xl-6 offset-xl-3 col-md-8 offset-md-2 -col-sm-12 -col-xs-12 col-12 fixed">
    <div class="py-3 bg-dark">
      <div class="text-white text-center">Copyright &copy; smport 2024</div>
    </div>
  </div>

</div>




<!-- Modal Filtro -->
<?= $this->Form->create("", array('class' => 'form-inline p-0', 'type' => 'post')) ?>

    <div class="modal fade" id="modalCidade" tabindex="-1" role="dialog" aria-labelledby="modalCidadeLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content no-radius">
              <div class="modal-header bg-secondary text-white no-radius">
                  <h5 class="modal-title" id="modalCidadeLabel">
                    <i class="fa-solid fa-location-dot"></i>
                    Municipios
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

                <div class="modal-body">
                  <div class="form-row normal mt-2">
                      <div class="col-12">
                        <ul class="list-group list-group-choice-municipios p-1 no-radius">
                          <?php
                          # echo "<li class='list-group-item list-group-item-action active'><strong>DEFINIR:</strong></li>";
                          foreach($municipios as $key => $municipio){


                            echo "<li class='list-group-item list-group-item-action text-center text-dark'>
                              <a href='#' onclick='changeId({$key})' class='text-dark' style='text-decoration: none'>
                                <strong class='text-uppercase'>{$municipio}</strong>
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
                    <button type="button" class="btn btn-light no-link text-secondary no-radius" data-bs-dismiss="modal">Cancelar</button>

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