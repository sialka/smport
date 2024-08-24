<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * CakePHP Component
 * @author Matheus
 */

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\Network\Response;
use Cake\Controller\Exception;

class ConditionsComponent extends Component {

    public $settings                = array();

    private $prefixSession          = '';
    private $objController          = null;
    private $currentController      = null;
    private $currentAction          = null;
    private $activeState            = true;
    private $delimiter              = null;
    private $listenRequestClear     = null;
    private $listenRequestPiped     = null;
    private $pkPiped                = null;
    private $fixPipedUrl            = false;
    private $pipe                   = '|';
    private $defaultCharCase        = null;
    private $tablesNames            = [];
    private $tryResolveFields       = true;
    private $keepAjax               = false;

    public function beforeRedirect(Event $event, $url, Response $response) {
        //parent::beforeRedirect($event, $url, $response);
        if (!$this->activeState) {
            return;
        }
    }

    public function beforeRender(Event $event) {
        //parent::beforeRender($event);
        if (!$this->activeState) {
            return;
        }
    }

    public function initialize(array $config) {
        parent::initialize($config);
        if (!$this->activeState) {
            return;
        }

        $controller                 = $this->_registry->getController();
        $this->settings             = $config;
        $this->prefixSession        = $config['prefixSession'];

        if (!isset($this->prefixSession) || (!is_string($this->prefixSession)) || (trim($this->prefixSession) === '') || strlen($this->prefixSession) > 20) {
            throw new Exception(__('Informe o prefixo de sessão.'), 897);
        }

        $this->currentAction        = mb_strtolower($controller->request->params['action']);
        $this->currentController    = mb_strtolower($controller->request->params['controller']);
        $this->objController        = $controller;
        $this->delimiter            = ((isset($this->settings['delimiter']))          && (is_string($this->settings['delimiter']))        && (trim($this->settings['delimiter']) != ''))      ? $this->settings['delimiter']      : null;
        $this->pipe                 = ((isset($this->settings['pipe']))               && (is_string($this->settings['pipe']))             && (trim($this->settings['delimiter']) != ''))      ? $this->settings['pipe']           : $this->pipe;
        $this->defaultCharCase      = ((isset($this->settings['char_case']))          && (is_numeric($this->settings['char_case']))       && ($this->settings['char_case'] <= 2))             ? $this->settings['char_case']      : null;
        $this->tablesNames          = ((isset($this->settings['tables_names']))       && (is_array($this->settings['tables_names']))      && (sizeof($this->settings['tables_names']) > 0))   ? $this->settings['tables_names']   : [];
        $this->tryResolveFields     = ((isset($this->settings['try_resolve_fields'])) && (is_bool($this->settings['try_resolve_fields'])))                                                    ? $this->settings['try_resolve_fields'] : true;
        $this->keepAjax             = ((isset($this->settings['keepAjax']))        && (is_bool($this->settings['keepAjax'])))           ? $this->settings['keepAjax']           : $this->keepAjax;

        if ((isset($this->settings['listenRequestClear'])) && (is_array($this->settings['listenRequestClear']))) {
            $this->listenRequestClear = $this->settings['listenRequestClear'];
        }

        if ((isset($this->settings['listenRequestPiped'])) && (is_array($this->settings['listenRequestPiped']))) {
            $this->listenRequestPiped = $this->settings['listenRequestPiped'];
        }

        if ($controller->request->data) {
            $this->UnsetSession('Conditions.' . $this->currentController . '.' . $this->currentAction . '.config.pkPiped');
        } else {
            $this->pkPiped  = $this->GetSession('Conditions.' . $this->currentController . '.' . $this->currentAction . '.config.pkPiped');
            $this->UnsetSession('Conditions.' . $this->currentController . '.' . $this->currentAction . '.config.pkPiped');
        }
    }

    public function beforeFilter(Event $event) {
        $redirected     = $this->GetSession('Conditions.' . $this->currentController . '.' . $this->currentAction . '.config.registerRedirect');

        if ((isset($redirected)) && ($redirected === true)) {
            $this->UnRegisterRedirect();
        } else {
            $redirect = $this->CheckForClear();
            if ($redirect !== false) {
                $arrayRedirect = array(
                    'controller'    => $this->objController->request->params['controller'],//$this->currentController,
                    'action'        => $this->objController->request->params['action']//$this->currentAction
                );

                if (is_array($redirect)) {
                    $arrayRedirect = $arrayRedirect + $redirect;
                }

                $this->RegisterRedirect();

                return $this->objController->redirect(
                        $arrayRedirect
                );
            } else {
                $this->CheckForPiped();
            }
        }
    }

    public function shutdown(Event $event) {
        //parent::shutdown($event);
        if (!$this->activeState) {
            return;
        }
    }

    public function startup(Event $event) {

        if (!$this->activeState) {
            return;
        }
    }

    /**
     * Usado para criar conditions no padr�o cakephp 2.x a partir de uma lista de campos chave x valor
     *
     * Na controller $this->CreateConditions($conversion, $postData, $delimiter);
     *
     * @param array $conversion Array de associa��o com todos os campos poss�veis na pesquuisa
     *
     * @param array $postData Array de campos chave e valor que veio da pesquisa
     *
     * @param string $delimiter Se informado ser� utilizado como delimitador para verificar varia��es de opera��es
     * dentro de um determinado campo. Ex. slote_wepd__LIKE, se o delimitador estiver configurado como "__"
     * ir� buscar em $conversion um n� com nome slote_wepd, caso localize ir� copiar suas configura��es e trocar
     * a opera��o de busca para LIKE

     * @param string $masterBooleanOperationField Modificador para NOT(nega��o de toda a express�o que ser� gerada)
     * ou OR(agrega��o de todas as condi��es que ser�o geradas)
     * @return array('conditions', 'filters', 'stringFilter')

     */
    public function createConditions($conversion, $postData, $delimiter=null, $masterBooleanOperationField = null) {
        $objTables = [];
        if (sizeof($this->tablesNames) > 0) {
            foreach ($this->tablesNames as $key => $_table) {
                $table = is_array($_table) ? $key : $_table;
                if (isset($objTables[$table])) {
                    continue;
                }
                $objTables[$table] = \Cake\ORM\TableRegistry::get($table);
            }
        }

        $delimiterOperations    = array('=', '!=', '<', '>', '<=', '>=', '<>', 'LIKE', 'RLIKE', 'LLIKE', 'NLIKE', 'BETWEEN');
        $delimiter              = ((isset($delimiter)) && (is_string($delimiter)) && (trim($delimiter) !== '')) ? $delimiter : $this->delimiter;

        if ((isset($delimiter)) && (is_string($delimiter)) && (trim($delimiter) !== '')) {
            foreach ($postData as $postField => $value) {
                $splitedField = explode($delimiter, $postField);
                if ((sizeof($splitedField) != 2) || (!is_string($splitedField[0])) || (!is_string($splitedField[1]))) {
                    continue;
                }

                foreach ($conversion as $model => $fields) {
                    if ((isset($conversion[$model][$splitedField[0]])) && (in_array(mb_strtoupper($splitedField[1]), $delimiterOperations))) {
                        if ((!isset($conversion[$model][$splitedField[0]]['name'])) || (is_string($conversion[$model][$splitedField[0]]['name']))) {
                            $conversion[$model][$postField]                 = $conversion[$model][$splitedField[0]];
                            $conversion[$model][$postField]['operation']    = $splitedField[1];
                        }
                    }
                }
            }
        }

        $conditions = array();
        $filters    = array();
        foreach ($postData as $fixedField => $fixedValue) {
            foreach ($conversion as $model => $fields) {
                foreach ($fields as $field => $options) {
                    if ($fixedField == $field) {
                        $names = array();
                        if ((isset($options['name'])) && ($options['name'] !== false)) {
                            if (is_array($options['name'])) {
                                $names = $options['name'];
                            } else {
                                $names[] = $options['name'];
                            }
                        } else {
                            $names[] = $field;
                        }

                        $setOr = false;
                        if (sizeof($names) > 1) {
                            $setOr = true;
                        }

                        $orConditions = array();

                        if ((isset($options['list'])) && ($options['list'] !== false) && (is_array($options['list']))) {
                            foreach ($options['ignore'] as $ignoreVal) {
                                unset($options['list'][$ignoreVal]);
                            }
                        }

                        foreach ($names as $ordName => $name) {
                            $_fieldTypeValidate = '';
                            if (!is_array($fixedValue)) {
                                if (in_array(trim($fixedValue), $options['ignore'])) {
                                    if ((isset($options['default'])) && ($options['default'] !== false)) {
                                        $fixedValue = $options['default'];
                                    } else {
                                        if ((!isset($options['forceList'])) || ($options['forceList'] === false)) {
                                            continue;
                                        }
                                    }
                                }
                            }

                            if (sizeof($names) > 1) {
                                if ((isset($options['operations'])) && (is_array($options['operations']))) {
                                    $operation              = (isset($options['operations'][$ordName])) ? $options['operations'][$ordName] : $options['operations'][sizeof($options['operations'])-1];
                                    $options['operation']   = $operation;
                                }
                            }

                            $node = (strpos($name, '.') === false) ? $model . '.' . trim($name) : $name;
                            list($_modelValidate, $_fieldValidate) = explode('.', $node);
                            if (sizeof($objTables) > 0) {
                                if (isset($objTables[$_modelValidate])) {
                                    $_fieldTypeValidate = $objTables[$_modelValidate]->schema()->columnType($_fieldValidate);
                                } else {
                                    foreach ($this->tablesNames as $tkey => $tname) {
                                        if (!is_array($tname)) {
                                            continue;
                                        }

                                        if (in_array($_modelValidate, $tname)) {
                                            $_fieldTypeValidate = $objTables[$tkey]->schema()->columnType($_fieldValidate);
                                        }
                                    }
                                }
                            }

                            if ($_fieldTypeValidate == '' && $this->tryResolveFields === true) {
                                $objTables[$_modelValidate] = \Cake\ORM\TableRegistry::get($_modelValidate);
                                $_fieldTypeValidate         = $objTables[$_modelValidate]->schema()->columnType($_fieldValidate);
                            }

                            $fixedValue = (!is_array($fixedValue)) ? mb_strtoupper($fixedValue) : $fixedValue;

                            if (isset($this->defaultCharCase) && is_numeric($this->defaultCharCase)) {
                                $options['char_case'] = $this->defaultCharCase;
                            }

                            if ((isset($options['char_case'])) && ($options['char_case'] !== false)) {
                                if ($_fieldTypeValidate == '' || $_fieldTypeValidate == 'string' || $_fieldTypeValidate == 'text') {
                                    if ($options['char_case'] === 1) {
                                        $node       = 'UPPER('.$node.')';
                                        $fixedValue = (!is_array($fixedValue)) ? mb_strtoupper($fixedValue) : $fixedValue;
                                    } else {
                                        $node       = 'LOWER('.$node.')';
                                        $fixedValue = (!is_array($fixedValue)) ? mb_strtolower($fixedValue) : $fixedValue;
                                    }
                                }
                            }
                            $node       = ((isset($options['coalesce']))    && ($options['coalesce'] !== false))    ? "COALESCE(".$node.", '".$options['coalesce']."')"   : $node;

                            if (($_fieldTypeValidate == 'integer' || $_fieldTypeValidate == 'float' || $_fieldTypeValidate == 'decimal')) {
                                if (!is_array($fixedValue)) {
                                    if (!is_numeric($fixedValue)) {
                                        continue;
                                    }

                                    if ($_fieldTypeValidate == 'integer' && !is_int($fixedValue)) {
                                        if ($fixedValue < -2147483648 || $fixedValue > 2147483647) {
                                            continue;
                                        }
                                    }
                                }
                            }

                            if (($_fieldTypeValidate == 'date')) {
                                $tracoOUbarra = false;
                                $checkSizeDate = explode('/',$fixedValue);
                                if (sizeof($checkSizeDate) != 3) {
                                    $checkSizeDate = explode('-',$fixedValue);
                                    if (sizeof($checkSizeDate) != 3) {
                                        continue;
                                    }
                                    $tracoOUbarra = true;
                                }

                                if ( $tracoOUbarra ) {
                                    list($yyyy,$mm,$dd) = explode('-',$fixedValue);
                                } else {
                                    list($dd,$mm,$yyyy) = explode('/',$fixedValue);
                                }

                                if (!checkdate($mm,$dd,$yyyy)) {
                                    continue;
                                }
                            }

                            $aliasValue = $fixedValue;
                            if ($options['operation'] ==='BETWEEN') {
                                $check_between = substr($fixedField, -6);
                                if (strlen($fixedField) > 6 && $check_between === '_start') {
                                    $end = str_replace($check_between, '_end', $fixedField);
                                    if (isset($postData[$end]) && trim($postData[$end]) != '') {
                                        $fixedValue = array($fixedValue, $postData[$end]);
                                        $aliasValue = $fixedValue;
                                    }
                                }
                            } else {
                                if (is_array($aliasValue) && $options['operation'] !=='BETWEEN') {
                                    $aliasValue = implode(', ', $aliasValue);
                                }
                            }


                            if (((isset($options['date']))        && ($options['date'] !== false)) || (($_fieldTypeValidate == 'date'))) {
                                $node = 'DATE('.$node.')';
                                if (is_array($fixedValue)) {
                                    $keys = array_keys($fixedValue);
                                    foreach ($keys as $key) {
                                        $fixedValue[$key] = date('Y-m-d', strtotime(str_replace('/', '-', $fixedValue[$key])));
                                    }
                                } else {
                                    $fixedValue = date('Y-m-d', strtotime(str_replace('/', '-', $fixedValue)));
                                }
                            }

                            if ((isset($options['list'])) && ($options['list'] !== false)) {
                                if ((((is_string($fixedValue)) || (is_numeric($fixedValue))) && (isset($options['list'][$fixedValue]))) || ($this->CheckValuesForList($fixedValue, $options['list']))) {
                                    if (is_array($fixedValue)) {
                                        $aliasValue = '';
                                        foreach ($options['list'] as $key => $item) {
                                            if (in_array($key, $fixedValue)) {
                                                $aliasValue .= ($aliasValue == '') ? $item : ', '.$item;
                                            }
                                        }
                                    } else {
                                        $aliasValue = $options['list'][$fixedValue];
                                    }
                                } else {
                                    if ((isset($options['forceList'])) && ($options['forceList'] !== false)) {
                                        $fixedValue = array_keys($options['list']);

                                        $aliasValue = '';
                                        foreach ($options['list'] as $item) {
                                            $aliasValue .= ($aliasValue == '') ? $item : ', '.$item;
                                        }
                                    } else {
                                        continue;
                                    }
                                }
                            }

                            if ((isset($options['replace'])) && ($options['replace'] !== false)) {
                                if (isset($options['replace'][$fixedValue])) {
                                    if (isset($options['replace'][$fixedValue]['conditions'])) {
                                        $conditionsReplace = $options['replace'][$fixedValue]['conditions'];
                                        foreach ($conditionsReplace as $key => $value) {
                                            (!$setOr)
                                                ? $conditions[$key]     = $value
                                                : $orConditions[$key]   = $value;
                                        }
                                    }

                                    if (isset($options['replace'][$fixedValue]['values'])) {
                                        $fixedValue = $options['replace'][$fixedValue]['values'];
                                    } else {
                                        $filters[]  = $options['alias'].' = '.$aliasValue;
                                        continue;
                                    }
                                }
                            }

                            $filter = $options['alias'];

                            if (is_array($fixedValue) && ($options['operation'] !== 'BETWEEN')) {
                                $node .= ' IN';
                            }

                            if ((!isset($options['operation'])) || ($options['operation'] === '')) {
                                (!$setOr)
                                    ? $conditions[$node]            = $fixedValue
                                    : $orConditions[$node]          = $fixedValue;
                                $filters[$filter.' = '.$aliasValue] = $filter.' = '.$aliasValue;
                            } else {
                                if (in_array($options['operation'], array('<', '<=', '!=', '<>', '>=' , '>', '='))) {
                                    if (is_array($fixedValue)) {
                                        if (in_array($options['operation'], array('!=', '<>'))) {
                                            (!$setOr)
                                                ? $conditions[]['NOT'][$node]                           = $fixedValue
                                                : $orConditions[]['NOT'][$node]                         = $fixedValue;
                                            $filters[$filter.' '.$options['operation'].' '.$aliasValue] = $filter.' '.$options['operation'].' '.$aliasValue;
                                        }
                                    } else {
                                        (!$setOr)
                                            ? $conditions[$node.' '.$options['operation']]          = $fixedValue
                                            : $orConditions[$node.' '.$options['operation']]        = $fixedValue;
                                        $filters[$filter.' '.$options['operation'].' '.$aliasValue] = $filter.' '.$options['operation'].' '.$aliasValue;
                                    }
                                } else {
                                    if (($_fieldTypeValidate != '' && ($_fieldTypeValidate != 'string' && $_fieldTypeValidate != 'text')) && in_array($options['operation'], array('LIKE', 'LLIKE', 'RLIKE', 'NLIKE')))  {
                                        if ($options['operation'] === 'NLIKE') {
                                            (!$setOr)
                                                ? $conditions[$node.' !=']          = $fixedValue
                                                : $orConditions[$node.' !=']        = $fixedValue;
                                            $filters[$filter.' != '.$aliasValue] = $filter.' != '.$aliasValue;
                                        } else {
                                            (!$setOr)
                                                ? $conditions[$node]          = $fixedValue
                                                : $orConditions[$node]        = $fixedValue;
                                            $filters[$filter.' = '.$aliasValue] = $filter.' = '.$aliasValue;
                                        }
                                    } else {
                                        switch ($options['operation']) {
                                            case 'LIKE':
                                                (!$setOr)
                                                    ? $conditions[$node.' LIKE']        = "%{$fixedValue}%"
                                                    : $orConditions[$node.' LIKE']      = "%{$fixedValue}%";
                                                $filters[$filter.' = '.$aliasValue]     = $filter.' +- '.$aliasValue;
                                                break;

                                            case 'LLIKE':
                                                (!$setOr)
                                                    ? $conditions[$node.' LIKE']    = "%{$fixedValue}"
                                                    : $orConditions[$node.' LIKE']  = "%{$fixedValue}";
                                                $filters[$filter.' = '.$aliasValue] = $filter.' +-= '.$aliasValue;
                                                break;

                                            case 'RLIKE':
                                                (!$setOr)
                                                    ? $conditions[$node.' LIKE']    = "{$fixedValue}%"
                                                    : $orConditions[$node.' LIKE']  = "{$fixedValue}%";
                                                $filters[$filter.' = '.$aliasValue] = $filter.' =+- '.$aliasValue;
                                                break;

                                            case 'NLIKE':
                                                (!$setOr)
                                                    ? $conditions[$node.' NOT LIKE']    = "%{$fixedValue}%"
                                                    : $orConditions[$node.' NOT LIKE']  = "%{$fixedValue}%";
                                                $filters[$filter.' = '.$aliasValue] = $filter.' <>+- '.$aliasValue;
                                                break;

                                            case 'BETWEEN':
                                                if ((is_array($fixedValue)) && (sizeof($fixedValue) == 2)) {
                                                    if (!$setOr) {
                                                        $conditions[$node.' >=']     = current($fixedValue);
                                                        $conditions[$node.' <=']     = next($fixedValue);
                                                    } else {
                                                        $orConditions[$node.' >=']     = current($fixedValue);
                                                        $orConditions[$node.' <=']     = next($fixedValue);
                                                    }
    //                                                (!$setOr)
    //                                                    ? $conditions[$node.' BETWEEN ? AND ?']     = $fixedValue
    //                                                    : $orConditions[$node.' BETWEEN ? AND ?']   = $fixedValue;
                                                    $_curr = current($aliasValue);
                                                    $_next = next($aliasValue);
                                                    $filters[$filter.' '.__('Inicial').' = '.  $_curr.' '.__('e').' '.$filter.' '.__('Final').' = '. $_next] = $filter.' '.__('Inicial').' = '. $_curr.' '.__('e').' '.$filter.' '.__('Final').' = '. $_next;
                                                }
                                                break;
                                            default:
                                                break;
                                        }
                                    }
                                }
                            }
                        }

                        if (($setOr) && (sizeof($orConditions) >= 1)) {
                            $conditions[]['AND']['OR'] = $orConditions;
                            $orConditions = array();
                        }
                    }
                }
            }
        }

        if (isset($this->pkPiped)) {
            $valuesPiped = current($this->pkPiped['conditions']);
            if (is_array($valuesPiped)) {
                $valuesPiped = implode(', ', $valuesPiped);
            }

            if ($this->pkPiped['keepData'] === false) {
                $conditions = array();
                $filters    = array();
            }

            $conditions[key($this->pkPiped['conditions'])]  = current($this->pkPiped['conditions']);
            $filters[$this->pkPiped['alias'] . ' = ' . $valuesPiped]      = $this->pkPiped['alias'] . ' = ' . $valuesPiped;

        }

        $filtersString = '';
        foreach ($filters as $key => $value) {
            $filtersString = ($filtersString === '') ? '['.$value.']' : $filtersString . ' - ' . '['.$value.']';
        }

        if (sizeof($conditions) >= 1) {
            if ((isset($masterBooleanOperationField)) && (is_string($masterBooleanOperationField)) && (trim($masterBooleanOperationField) !== '')) {
                if ((isset($postData[$masterBooleanOperationField])) && (in_array($postData[$masterBooleanOperationField], array('OR', 'NOT')))) {
                    $masterOperator = array('OR' => __('QUALQUER DESSES'), 'NOT' => __('N�O CONTENHA'));

                    $conditions     = array($postData[$masterBooleanOperationField] => $conditions);
                    $filters        = array($postData[$masterBooleanOperationField] => $filters);
                    $filtersString  = $masterOperator[$postData[$masterBooleanOperationField]] .' ('. $filtersString . ')';
                }
            }
        }

        return array('conditions' => $conditions, 'filters' => $filters, 'stringFilter' => $filtersString);
    }

    public function clear() {
        $this->UnsetSession('Conditions.' . $this->currentController . '.' . $this->currentAction);

        if (isset($this->objController->AdvancedSearch)) {
            $this->objController->AdvancedSearch->reset();
        }
    }

    public function clearAll() {
        $this->UnsetSession('Conditions');

        if (isset($this->objController->AdvancedSearch)) {
            $this->objController->AdvancedSearch->reset();
        }
    }

    public function filter($model = '', $conditions = array(), $defaultValues = array(), $delimiter=null, $masterBooleanOperationField = null) {
        $controller = $this->currentController;
        $action     = $this->currentAction;
        $inSession  = $this->GetSession('Conditions.' . $controller . '.' . $action . '.config.baseConditions');

        if (($this->objController->request->data)) {
            $this->SetSession('Conditions.' . $controller . '.' . $action . '.config.baseConditions', $this->objController->request->data[$model]);
        } else {
            if (isset($inSession)) {
                $this->objController->request->data[$model] = $inSession;
            } else {
                $this->objController->request->data = null;
                $this->objController->request->data = $defaultValues;
            }
        }

        $resultFilters = array('conditions' => array(), 'filters' => array(), 'stringFilter' => '');
        if (isset($this->objController->request->data[$model])) {
            $resultFilters = $this->createConditions($conditions, $this->objController->request->data[$model], $delimiter, $masterBooleanOperationField);
        }


        $delimiter = ((isset($delimiter)) && (is_string($delimiter)) && (trim($delimiter) !== '')) ? $delimiter : $this->delimiter;
        if ((isset($delimiter)) && (is_string($delimiter)) && (trim($delimiter) !== '')) {
            if ((!isset($this->objController->request->data[$model])) || (!is_array($this->objController->request->data[$model]))) {

            } else {
                foreach ($this->objController->request->data[$model] as $postField => $value) {
                    if (is_array($value)) {
                        continue;
                    }

                    $splitedField = explode($delimiter, $postField);
                    if (sizeof($splitedField) != 2) {
                        continue;
                    }

                    if (!isset($this->objController->request->data[$model][$splitedField[0]])) {
                        $this->objController->request->data[$model][$splitedField[0]] = $value;
                    }
                }
            }
        }

        if ($this->fixPipedUrl) {
            $arrayRedirect = array(
                'controller'    => $this->objController->request->params['controller'],//$this->currentController,
                'action'        => $this->objController->request->params['action']//$this->currentAction
            );

            if (is_array($this->fixPipedUrl)) {
                $arrayRedirect = $arrayRedirect + $this->fixPipedUrl;
            }

            $this->RegisterRedirect();
        }

        return $resultFilters;
    }

    private function CheckForClear() {
        if (isset($this->listenRequestClear[$this->currentAction])) {
            $configClear            = $this->listenRequestClear[$this->currentAction];
            $configClear['named']   = (isset($this->objController->request->params['named'][$configClear['param']])) ? true : false;

            if ($configClear['named'] === true) {
                if ((isset($this->objController->request->params['named'][$configClear['param']])) && ($this->objController->request->params['named'][$configClear['param']] === 'true')) {
                    $this->clear();
                    return false;
                }
            } else {
                $checkOperator  = $configClear['param'];
                $urlParams      = (isset($this->objController->request->params['pass'])) ? $this->objController->request->params['pass'] : null;
                if (sizeof($urlParams) >= 1) {
                    if ($urlParams[0] == $checkOperator) {
                        unset($urlParams[0]);
                        $this->clear();
                        return (sizeof($urlParams)) === 0 ? true : $urlParams;
                    }
                }
            }
        }

        return false;
    }

    private function CheckForPiped() {
        if (isset($this->listenRequestPiped[$this->currentAction])) {
            $configPiped    = $this->listenRequestPiped[$this->currentAction];
            if ((!isset($configPiped['model'])) || (!is_string($configPiped['model'])) || (trim($configPiped['model']) == '')) {
                return false;
            }

            $configPiped['named'] = (
                                    (isset($this->objController->request->params['named']['piped'])) &&
                                    (isset($this->objController->request->params['named']['field'])) &&
                                    (isset($this->objController->request->params['named']['values']))
                                    )
                                    ? true
                                    : (isset($this->objController->request->params['named']['keys'])) ? true : false;

            $model          = $configPiped['model'];
            $validPipedUrl  = false;
            $piped          = false;
            if ($configPiped['named'] === true) {
                if ((isset($this->objController->request->params['named']['piped'])) && ($this->objController->request->params['named']['piped'] === 'true')) {
                    $piped = true;

                    if ((!isset($this->objController->request->params['named']['field'])) || (trim($this->objController->request->params['named']['field']) === '')) {
                        return false;
                    }

                    if ((!isset($this->objController->request->params['named']['values'])) || (trim($this->objController->request->params['named']['values']) === '')) {
                        return false;
                    }

                    if ((isset($configPiped['fields'])) && (is_array($configPiped['fields'])) && (sizeof($configPiped['fields']) >= 1)) {
                        if (in_array(mb_strtolower($this->objController->request->params['named']['field']), $configPiped['fields'])) {
                            $field          = $this->objController->request->params['named']['field'];
                            $values         = $this->objController->request->params['named']['values'];
                            $validPipedUrl  = 2;
                        }
                    } else {
                        return false;
                    }
                } else {
                    if ((isset($configPiped['blockPkPiped'])) && ($configPiped['blockPkPiped'] === true)) {
                        return false;
                    }

                    if (!isset($this->objController->request->params['named']['keys'])) {
                        return false;
                    }

                    $values         = $this->objController->request->params['named']['keys'];
                    $validPipedUrl  = 1;
                }
            } else {
                $urlParams      = (isset($this->objController->request->params['pass'])) ? $this->objController->request->params['pass'] : null;
                if (sizeof($urlParams) >= 1) {
                    if ((mb_strtolower($urlParams[0]) === 'piped')) {
                        $piped = true;
                        if ((sizeof($urlParams) > 2) && (isset($configPiped['fields'])) && (is_array($configPiped['fields'])) && (sizeof($configPiped['fields']) >= 1)) {
                            if (in_array(mb_strtolower($urlParams[1]), $configPiped['fields'])) {
                                $field          = mb_strtolower($urlParams[1]);
                                $values         = $urlParams[2];
                                $validPipedUrl  = 2;
                            }
                        } else {
                            return false;
                        }
                    } else {
                        if ((isset($configPiped['blockPkPiped'])) && ($configPiped['blockPkPiped'] === true)) {
                            return false;
                        }

                        $checkUrl1 = (strpos(mb_strtolower($urlParams[0]), 'keys=') !== false);
                        $checkUrl2 = (substr(mb_strtolower($urlParams[0]), 0, 5) === 'keys=');
                        $checkUrl3 = (strlen($urlParams[0]) > 5);
                        if ((!$checkUrl1) || (!$checkUrl2) || (!$checkUrl3)) {
                            return false;
                        }

                        $urlParams[0]   = trim(substr($urlParams[0], 5));
                        $values         = $urlParams[0];
                        $validPipedUrl  = 1;
                    }
                } else {
                    return false;
                }
            }

            if ($validPipedUrl === false) {
                return false;
            } else {
                $checkNumber = isset($configPiped['notCheckNumber']) && in_array($field, $configPiped['notCheckNumber']) ? false : true;
                if (!$this->CheckIfValidPipedValues($values, $checkNumber, $configPiped['limit'])) {
                    return false;
                }

                if (!$piped) {
                    if ((isset($configPiped['pkAlias'])) && (is_string($configPiped['pkAlias'])) && (trim($configPiped['pkAlias']) !== '')) {
                        $aliasPkPiped = $configPiped['pkAlias'];
                    } else {
                        $aliasPkPiped = __('_ID');
                    }

                    $this->objController->loadModel($model);
                    if ((!isset($this->objController->{$model})) || (!isset($this->objController->{$model}->primaryKey))) {
                        return false;
                    }

                    $this->pkPiped = array(
                        'alias'         => $aliasPkPiped,
                        'keepData'      => (isset($configPiped['keepData'])) && ($configPiped['keepData'] === true) ? true : false,
                        'conditions'    => array(
                            $model . '.' . $this->objController->{$model}->primaryKey => $values
                        )
                    );

                    $this->SetSession('Conditions.' . $this->currentController . '.' . $this->currentAction . '.config.pkPiped', $this->pkPiped);
                }

                if ((!isset($configPiped['keepData'])) || ($configPiped['keepData'] === false)) {
                    unset($this->objController->request->data[$model]);
                }

                if ((isset($model)) && (isset($field)) && (isset($values))) {
                    $this->objController->request->data[$model][$field] = $values;
                } else {
                    $this->objController->request->data = ((is_array($this->objController->request->data)) && (sizeof($this->objController->request->data) >= 1))
                            ? $this->objController->request->data
                            : array(
                                $model => array(
                                    $this->objController->{$model}->primaryKey => $values
                                )
                            );
                }

                if ((!isset($configPiped['named'])) || ($configPiped['named'] === false)) {
                    switch ($validPipedUrl) {
                        case 1:
                            unset($urlParams[0]);
                            break;

                        case 2:
                            unset($urlParams[0], $urlParams[1], $urlParams[2]);
                            break;
                    }

                    if ((isset($configPiped['fixUrl'])) && ($configPiped['fixUrl'] === true)) {
                        $this->fixPipedUrl = (sizeof($urlParams)) === 0 ? true : $urlParams;
                    }
                }

                $this->objController->Security->config('unlockedActions', [$this->currentAction]);
                return true;
            }
        }

        return false;
    }

    private function CheckIfValidPipedValues(&$pipedValues, $checkIfNumber = true, $limit = null) {
        try {
            if ((isset($limit)) && ((!is_numeric($limit)) || ($limit <= 0))) {
                $limit = null;
            }

            $vals        = array();
            $values   = (isset($limit)) ? explode($this->pipe, $pipedValues, $limit+1) : explode($this->pipe, $pipedValues);
            if (sizeof($values) > ($limit)) {
                unset($values[sizeof($values)-1]);
            }

            if ((!isset($values)) || (!is_array($values)) || (sizeof($values) <= 0)) {
                return false;
            }

            foreach ($values as $value) {
                if (trim($value) === '') {
                    return false;
                }

                if ($checkIfNumber) {
                    if (!is_numeric($value)) {
                        return false;
                    }
                }

                $vals[] = $value;
            }

            $pipedValues = $vals;

            return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    private function CheckValuesForList(&$values, $list) {
        try {
            if (!is_array($values)) {
                return false;
            }


            $removes = [];
            foreach ($values as $key => $value) {
                if (!isset($list[$value])) {
                    $removes[$key] = $key;
                }
            }

            foreach ($removes as $remove) {
                unset($values[$remove]);
            }

            if (sizeof($values) >= 1) {
                return true;
            }

            return false;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    private function RegisterRedirect() {
        $controller = $this->currentController;
        $action     = $this->currentAction;
        $this->SetSession('Conditions.' . $controller . '.' . $action . '.config.registerRedirect', true);
    }

    private function UnRegisterRedirect() {
        $controller = $this->currentController;
        $action     = $this->currentAction;

        $this->UnsetSession('Conditions.' . $controller . '.' . $action . '.config.registerRedirect');
    }

    public function _getSession () {
        return $this->GetSession('Conditions.' . $this->currentController . '.' . $this->currentAction);
    }

    private function SetSession($sessionNode, $value) {
        $isAjax = $this->objController->request->is('ajax');
        if ($isAjax === false || ($this->keepAjax === true)) {
            $this->request->session()->write($this->prefixSession . '.' . $sessionNode, $value);
        } else {
            if ($this->request->is('ajax') && in_array('text/html', $this->request->accepts()) && $this->request->header('SPA_FORMAT') === 'true') {
                $this->request->session()->write($this->prefixSession . '.' . $sessionNode, $value);
            }
        }
    }

    private function UnsetSession($sessionNode) {
        $this->request->session()->delete($this->prefixSession . '.' . $sessionNode);
    }

    private function GetSession($sessionNode) {
        return $this->request->session()->read($this->prefixSession . '.' . $sessionNode);
    }
}
