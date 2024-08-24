<?php  

  $titulo = [
    1 => "Conferência de Fichas",
    2 => "Reserva de Roupas",
    3 => "Conferência de Envelopes",
    4 => "Atendimento",
  ];

  $recupera_session = $this->request->session()->read('painel-senha'); 
  
  if($recupera_session != []){
    $topo = $recupera_session[0];
    $senha_topo = explode(",",$topo);    
    // fala, senha, tipo    
    $voz = $senha_topo[0];
    $senha = $senha_topo[1];
    $tipo = $senha_topo[2];
  }else{
    $voz = 0;
    $senha = 0;
    $tipo = null;
  } 
  
  $novo = array_slice($recupera_session, 1, count($recupera_session));
  $this->request->session()->write('painel-senha', $novo);  
  
  $panel_normal = $this->request->session()->read('panel-normal'); 
?>
<div class="row bg-black">   

  <div class="col">   

    <?= $this->element('mobile-panel'); ?>

    <div class="card border-dark m-1 mobile-panel-hide">      
      
      <div class="card-header text-white bg-dark painel ">
        <a href="/" class="text-white">CCB - SETOR 4 - PAINEL</a>
      </div>
        <div class="card-body" style="height: 92vh">        

        <?php           
          echo $this->Form->create(null, ['url' => ['action' => '?page='.$pagina_index.'']]);           
          echo $this->Form->button('submit', ['type' => 'submit', 'class' => 'hide', 'id' => 'submit']);
          echo $this->Form->end();
        ?>

          <!-- Fluxo 1  -->
          <div id="senha" class="<?php if( $senha == "0" ){ echo "hide"; } ?>">   
              <div class="" style="height: 88vh">                                
                  <div class="" style="height: 100%" >                    
                    <p class="titulo" style="height: 35%; padding-top: 14rem"><?= $titulo[$tipo]; ?></p>         

                    <?php $class = $tipo == 3 || $tipo == 4 ? 'call-senha-localidade' : 'call-senha'; ?>
                    <p id="chamar-senha" class="<?= $class ?> wobble-hor-bottom"><?= $senha; ?></p>                                  
                  </div>                  
              </div>
          </div>

          <!-- Fluxo 2 -->                      
          <div id="painel" class="<?php if( $senha != "0" || $panel_normal == 1) { echo "hide"; } ?>">      
          
            <table id="tableResults" class="table p-0 m-0" style="border: 0px solid white">
                <tbody class="">
                    <?php foreach ((object) $dados as $dado): ?>
                        <tr class="normal">
                          <th class="" style="border: 0px solid white">                            

                            <div class="row mt-1 mb-1 ml-2 mr-2">
                              <div class="col-2">
                                <div class="card border-dark center">
                                  <div id="row1-senha" class="card-body senha text-dark"><?= $dado->senha; ?></div>
                                  <div class="card-footer text-white bg-dark text-white">SENHA</div>
                                </div>
                              </div>
                              <div class="col-10">
                                <div id="row1-card-border" class="card border-dark">
                                  <div id="row1-localidade" class="card-body senha text-dark">
                                    <?= $dado->Localidades->nome; ?>
                                  </div>
                                  <div class="text-center m-0 font-weight-bold">
                                      <div id="row1-status-ficha" style="width:50%; padding: 0.8em; float: left" class="<?= $aevOptions['status_css_ficha'][$dado->status_ficha]; ?>">
                                        <?= $aevOptions['status_fichas'][$dado->status_ficha]; ?>
                                      </div>  
                                      <div id="row1-status-envelope" style="width:50%; padding: 0.8em; float: right" class="<?= $aevOptions['status_css_envelope'][$dado->status_envelope]; ?>">
                                        <?= $aevOptions['status_envelopes'][$dado->status_envelope]; ?>                                          
                                      </div>  
                                  </div>
                                </div>
                              </div>                      
                            </div>            
                          </th>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

          </div>

        </div>
      </div>
    </div>
  </div>
</div>            

<script>

// Voice

const synth = window.speechSynthesis;

//let voices = [];

function speak(texto) {

  let voices = synth.getVoices();  

  for (let i = 0; i < voices.length; i++) {
    if(voices[i].lang == "pt-BR") {    
      console.log(`${i} - ${voices[i].lang} - ${voices[i].name}`)
    }
  }

  if (synth.speaking) {
    console.error("speechSynthesis.speaking");
    return;
  }
  
  const utterThis = new SpeechSynthesisUtterance(texto);

  utterThis.onend = function (event) {
    console.log("SpeechSynthesisUtterance.onend");
  };

  utterThis.onerror = function (event) {
    console.error("SpeechSynthesisUtterance.onerror");
  };

  //console.log(synth);  
  //utterThis.voice = voices[0]; 
  utterThis.voice = voices[<?= $sintetizador; ?>]; 
  

  utterThis.pitch = 1; 
  utterThis.rate = 1.1; 
  synth.speak(utterThis);
  
}

// fim voice
const senha = "<?= $voz; ?>";
const tipo = "<?= $tipo; ?>";

function carregar() {
  console.log("carregar");  

  if (senha != 0) {
    // Fluxo 1 - Verifica se existe senhas
    console.log("Fluxo chamar Senhas !!!!");
    
    sleep(3000).then(() => {                     
        $("#submit").click();
    });         
      
  } else {
    // Fluxo 2 - Exibe painel
    console.log("Exibir Senhas identificadas !!!");

    sleep(5000).then(() => {           
      $("#submit").click();
    }); 
  }
}

// builtin

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

const panel = document.querySelector('#chamar-senha')

panel.addEventListener('animationstart', event => {      
      
    let fala = "";

    switch (tipo) {
      case '1':
        fala = " Conferência de Fichas!";    
        frase = 'senha ' + senha + fala;    
        break;
      case '2':
        fala = " Reserva de roupas!";
        frase = 'senha ' + senha + fala;    
        break;
      case '3': 
        fala = " Conferência de envelopes!";    
        frase = senha + fala;    
        break;    
      case '4':         
        frase = senha;    
        break;    
    }   
    
    console.log(`Fala: ${frase}`);
    speak(frase);
});

carregar();

</script>