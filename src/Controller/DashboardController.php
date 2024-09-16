<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\I18n\Date;
use Cake\I18n\Time;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class DashboardController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->request->session()->write('layout', 'default');
    }

    public function index()
    {

        $municipiosTable = TableRegistry::get('Municipios');
        $aevMunicipio = $municipiosTable->aevOptions();
        $municipios = $aevMunicipio['municipio'];

        $horariosTable = TableRegistry::get('Horarios');
        $aevHorario = $horariosTable->aevOptions();
        $horarios   = $aevHorario['hora'];

        $utils = $this->utils();

        # --- Municipio Start --- #

        $favorito = $this->municipio_favorito();

        if ($this->request->data == null) {
            $municipio_id = $favorito;
        } else {
            $municipio_id = $this->request->data['municipio_id'];
        }

        # --- Agenda --- #
        $semana     = $utils['semana_id_longo'];
        $agenda = $this->cultos($municipio_id, $semana, $horarios);

        $btn_local_title = $municipios[$municipio_id];

        # --- Batismo Start --- #
        $batismo = $this->batismo($municipio_id, $horarios);

        # --- Ensaio Regional Start --- #
        $regional = $this->regional($municipio_id, $horarios);

        # --- Ensaio Local Start --- #
        $ensaio = $this->ensaio($municipio_id, $horarios);

        $semana     = $utils['semana_longo'];
        $semana_us_br = $utils['semana_us_longo'];

        # --- Reunião --- #
        $reunioes = $this->reuniao($municipio_id, $horarios);

        # --- Ordenação --- #
        $ordenacoes = $this->ordenacao($municipio_id, $horarios);

        $this->set('municipios', $municipios);
        $this->set('btn_local_title', $btn_local_title);
        $this->set('semana', $semana);
        $this->set('semana_us_br', $semana_us_br);
        $this->set('agenda', $agenda);
        $this->set('ensaio', $ensaio);
        $this->set('batismo', $batismo);
        $this->set('regional', $regional);
        $this->set('horarios', $horarios);
        $this->set('reunioes', $reunioes);
        $this->set('ordenacoes', $ordenacoes);
    }

    private function cultos($tipo, $semana, $horarios)
    {

        $cultosTable = TableRegistry::get('Cultos');

        $cultos = $cultosTable->find()->where(['Localidades.municipio_id' => $tipo])->contain(['Localidades'])->toArray();

        $schema_hs = [];
        foreach ($horarios as $hora) {
            $schema_hs += [$hora => []];
        }

        $schema_semana = [];
        foreach ($semana as $dia) {
            $schema_semana += [$dia => $schema_hs];
        }

        $data = $schema_semana;

        foreach ($cultos as $culto) {

            $dia = $semana[$culto->dia];
            $hora = $horarios[$culto->hora];

            $localidade = [$culto->Localidades->nome, $culto->Localidades->anciaes, $culto->Localidades->rota];

            //array_push($data[$dia][$hora], $culto->Localidades->nome);
            array_push($data[$dia][$hora], $localidade);
        }

        return $data;
    }

    private function municipio_favorito()
    {
        $municipiosTable = TableRegistry::get('Municipios');
        $favoritoEnt = $municipiosTable->find()->where(['favorito' => '1'])->first();
        $favorito = $favoritoEnt == null ? '1' : $favoritoEnt->id;

        return $favorito;
    }

    private function batismo($municipio_id, $horarios)
    {

        $utils = $this->utils();
        $batismo = [];
        $hoje = Time::now()->format('Y-m-d');
        $condicao = [
            'data >=' => $hoje,
            'Localidades.municipio_id' => $municipio_id,
        ];
        $batismoTable = TableRegistry::get('Batismo');
        $query = $batismoTable->find()->contain(['Localidades.Municipios', 'Horarios'])->where($condicao)->order(['data' => 'asc', 'Horarios.hora' => 'asc'])->toArray();

        foreach ($query as $info) {

            $data = $info->data->format('d/m/Y');
            # $hora = $info->horario_id;

            $dia_br = $utils['semana_id_longo'][$info->dia_semana];

            $batismo += ["{$data} - {$dia_br}" => []];
        }

        foreach ($query as $info) {
            $igreja = $info->Localidades->nome;
            $cidade = $info->Localidades->Municipios->nome;
            $data = $info->data->format('d/m/Y');
            $hora = $info->horario_id;

            # Dia da Semana
            $dia_br = $utils['semana_id_longo'][$info->dia_semana];

            array_push($batismo["{$data} - {$dia_br}"], "{$horarios[$hora]} - {$cidade} - {$igreja}");
        }

        return $batismo;
    }

    private function ordenacao($municipio_id, $horarios)
    {

        $utils = $this->utils();
        $ordenacao = [];
        $hoje = Time::now()->format('Y-m-d');
        $condicao = [
            'data >=' => $hoje,
            'Localidades.municipio_id' => $municipio_id,
        ];
        $ordenacaoTable = TableRegistry::get('Ordenacao');
        $query = $ordenacaoTable->find()->contain(['Localidades.Municipios', 'Horarios'])->where($condicao)->order(['data' => 'asc', 'Horarios.hora' => 'asc'])->toArray();

        foreach ($query as $info) {

            $data = $info->data->format('d/m/Y');
            # $hora = $info->horario_id;

            $dia_br = $utils['semana_id_longo'][$info->dia_semana];

            $ordenacao += ["{$data} - {$dia_br}" => []];
        }

        foreach ($query as $info) {
            $igreja = $info->Localidades->nome;
            $cidade = $info->Localidades->Municipios->nome;
            $data = $info->data->format('d/m/Y');
            $hora = $info->horario_id;

            # Dia da Semana
            $dia_br = $utils['semana_id_longo'][$info->dia_semana];

            array_push($ordenacao["{$data} - {$dia_br}"], "{$horarios[$hora]} - {$cidade} - {$igreja}");
        }

        return $ordenacao;
    }

    private function reuniao($municipio_id, $horarios)
    {

        $utils = $this->utils();
        $reuniao = [];
        $hoje = Time::now()->format('Y-m-d');
        $condicao = [
            'data >=' => $hoje,
            'Localidades.municipio_id' => $municipio_id,
        ];
        $reuniaoTable = TableRegistry::get('Reuniao');
        $query = $reuniaoTable->find()->contain(['Localidades.Municipios', 'Horarios'])->where($condicao)->order(['data' => 'asc', 'Horarios.hora' => 'asc'])->toArray();

        foreach ($query as $info) {

            $data = $info->data->format('d/m/Y');

            $dia_br = $utils['semana_id_longo'][$info->dia_semana];

            $reuniao += ["{$data} - {$dia_br}" => []];
        }

        foreach ($query as $info) {
            $igreja = $info->Localidades->nome;
            $cidade = $info->Localidades->Municipios->nome;
            $data = $info->data->format('d/m/Y');
            $hora = $info->horario_id;

            # Dia da Semana
            $dia_br = $utils['semana_id_longo'][$info->dia_semana];

            array_push($reuniao["{$data} - {$dia_br}"], "{$horarios[$hora]} - {$cidade} - {$igreja}");
        }

        return $reuniao;
    }

    private function regional($municipio_id, $horarios)
    {

        $utils = $this->utils();
        $regional = [];
        $hoje = Time::now()->format('Y-m-d');
        $condicao = [
            'data >=' => $hoje,
            'Localidades.municipio_id' => $municipio_id,
        ];
        $regionalTable = TableRegistry::get('Regional');
        $query = $regionalTable->find()->contain(['Localidades.Municipios', 'Horarios'])->where($condicao)->order(['data' => 'asc', 'Horarios.hora' => 'asc'])->toArray();

        foreach ($query as $info) {
            $data = $info->data->format('d/m/Y');
            // $hora = $info->horario_id;

            # Dia da Semana
            $dia_br = $utils['semana_id_longo'][$info->dia_semana];

            $regional += ["{$data} - {$dia_br}" => []];
        }

        foreach ($query as $info) {
            $igreja = $info->Localidades->nome;
            $cidade = $info->Localidades->Municipios->nome;
            $data = $info->data->format('d/m/Y');
            $hora = $info->horario_id;

            # Dia da Semana
            $dia_br = $utils['semana_id_longo'][$info->dia_semana];

            array_push($regional["{$data} - {$dia_br}"], "{$horarios[$hora]} - {$cidade} - {$igreja}");
        }

        return $regional;
    }

    private function ensaio($municipio_id, $horarios)
    {

        $schema = $this->semana_atual()['schema'];
        $semana = $this->semana_atual()['semana'];

        $utils = $this->utils();
        $ensaio = [];
        $semana_atual = null;
        $hoje = Time::now()->format('d');

        foreach ($schema as $key => $value) {
            if (in_array($hoje, $value)) {
                $semana_atual = $key;
            }
        }

        $qtd_sab = $semana-1;
        $sab_cond = ($semana_atual == 4 && $qtd_sab == 4) ? ">=" : "";
        $condicao = [
            "semana {$sab_cond}" => $semana_atual,
            "Localidades.municipio_id" => $municipio_id,
        ];

        $ensaioTable = TableRegistry::get('Ensaio');
        $query = $ensaioTable->find()->contain(['Localidades.Municipios', 'Horarios'])->where($condicao)->order(['dia_semana' => 'asc', 'Horarios.hora' => 'asc'])->toArray();

        // Gerar o schema: Dia_Mes Dia_Semana - Hora
        foreach ($query as $local) {

            $hora = $horarios[$local->horario_id];
            $dia_semana = $utils['semana_id_longo'][$local->dia_semana];
            $dia = $schema[$semana_atual][$local->dia_semana-1];

            if($dia >= $hoje){
                $ensaio += ["{$dia} {$dia_semana} - {$hora}" => []];
            }
        }

        // Atribui os ensaios ao schema
        foreach ($query as $local) {
            $igreja = $local->Localidades->nome;
            $hora = $horarios[$local->horario_id];
            $dia_semana = $utils['semana_id_longo'][$local->dia_semana];
            $dia = $schema[$semana_atual][$local->dia_semana-1];

            if($dia >= $hoje){
                array_push($ensaio["{$dia} {$dia_semana} - {$hora}"], "{$igreja}");
            }

        }

        $this->set('semana_atual', $semana_atual);
        return $ensaio;
    }

    private function semana_atual(){

        $ano = Time::now()->format('Y');
        $mes = Time::now()->format('m');
        $mes_3031 = cal_days_in_month(CAL_GREGORIAN, $mes, $ano); // 31

        $schema = [
            1 => [],
            2 => [],
            3 => [],
            4 => [],
            5 => [],
            6 => [],
        ];

        $semana = 1;

        for ($i = 1; $i <= $mes_3031; $i++) {

            $now = Time::parse("{$ano}-{$mes}-{$i}");
            $detalhes = getdate(strtotime($now->format('d-m-Y')));
            $dia_semana = $detalhes['weekday'];

            if ($dia_semana == "Saturday") {
                array_push($schema[$semana], $i);
                $semana++;
            } else {
                array_push($schema[$semana], $i);
            }
        }

        $this->set('schema', $schema);
        return ['semana'=>$semana, 'schema'=>$schema];
    }
}
