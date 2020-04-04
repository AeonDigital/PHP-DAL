<?php
declare (strict_types=1);

namespace AeonDigital\DataModel\Abstracts;

use AeonDigital\Interfaces\DataModel\iModel as iModel;
use AeonDigital\Interfaces\DataModel\iFieldModel as iFieldModel;
use AeonDigital\Interfaces\DataModel\iModelFactory as iModelFactory;
use AeonDigital\DataModel\Abstracts\aField as aField;





/**
 * Classe abstrata que extende ``aField`` para implementar ``iFieldModel`` dando a ela
 * capacidade de possuir como valor instâncias de modelos de dados (``iModel``).
 *
 * @package     AeonDigital\DataModel
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2019, Rianna Cantarelli
 * @license     ADPL-v1.0
 */
abstract class aFieldModel extends aField implements iFieldModel
{





    /**
     * Verifica se o valor passado é uma instância ``iModel`` de mesmo nome que o nome
     * passado.
     *
     * @param       mixed $v
     *              Objeto que será testado.
     *
     * @param       string $modelName
     *              Nome do modelo de dados alvo.
     *
     * @return      bool
     */
    private function checkIfValueIsModelOf($v, string $modelName) : bool
    {
        $r = false;
        if (\is_object($v) === true) {
            $interfaces = \class_implements($v);
            $r = (\in_array(iModel::class, $interfaces) === true && $v->getName() === $modelName);
        }
        return $r;
    }




    /**
     * Retorna um valor individual em seu formato de armazenamento.
     *
     * Este método não efetua a validação do valor.
     *
     * @param       mixed $v
     *              Valor que será convertido.
     *
     * @return      mixed
     */
    protected function modelIndividualValue_RetrieveInStorageFormat($v)
    {
        $nInst = $this->modelFactory->createDataModel($this->modelName);
        $nInst->setValues($v);
        return $nInst;
    }
    /**
     * Verifica um valor de forma individual e retorna um ``array`` com informações sobre
     * sua validação.
     *
     * O ``array`` retornado terá a seguinte definição:
     *
     * ``` php
     *      $arr = [
     *          // bool             Indica se o valor pode ou não ser definido para o campo.
     *          "canSet" => ,
     *
     *          // bool             Indica se o valor pode ser aceito por este campo.
     *          "valid" => ,
     *
     *          // string|string[]  Código/s do estado da validação.
     *          "state" => ,
     *
     *          // ?string          Código do estado da coleção validada.
     *          "cState" => null
     *      ];
     * ```
     *
     * @param       mixed $v
     *              Valor que será testado.
     *
     * @return      array
     */
    protected function modelIndividualValue_CheckValue($v) : array
    {
        $canSet = true;
        $state  = "valid";


        // Tratando-se de um objeto "iModel" do mesmo tipo, aceita-o
        // sempre, mesmo que ele próprio esteja inválido.
        if ($this->modelName !== null && $this->checkIfValueIsModelOf($v, $this->modelName) === true) {
            return [
                "canSet"    => true,
                "valid"     => $v->isValid(),
                "state"     => $v->getState(),
                "cState"    => null
            ];
        } else {

            // Primeira validação é a de possibilidade de
            // redefinir o valor do campo.
            if ($this->isReadOnly() === true && $this->value !== undefined) {
                $canSet = false;
                $state  = "error.dm.field.value.constraint.read.only";
            }


            if ($canSet === true) {
                if ($v === undefined) {
                    $canSet = false;
                    $state  = "error.dm.field.value.not.allow.undefined";
                } elseif ($v === null) {
                    if ($this->isAllowNull() === false) {
                        $canSet = false;
                        $state = "error.dm.field.value.not.allow.null";
                    }
                } elseif ($v === "") {
                    $canSet = false;
                    $state = "error.dm.field.value.not.allow.empty";
                } else {
                    if ($this->modelValidate === null) {
                        $this->modelValidate = $this->modelFactory->createDataModel($this->modelName);
                    }

                    $this->modelValidate->validateValues($v);
                    $state  = $this->modelValidate->getLastValidateState();
                    $canSet = $this->modelValidate->getLastValidateCanSet();
                }
            }

            return [
                "canSet"    => $canSet,
                "valid"     => ($state === "valid"),
                "state"     => $state,
                "cState"    => null
            ];
        }
    }
    /**
     * Processa o valor passado e retorna um ``array`` contendo as informações necessárias
     * para o SET deste valor neste campo.
     *
     * O ``array`` retornado terá a seguinte definição:
     *
     * ``` php
     *      $arr = [
     *          // mixed            O valor passado de forma bruta, sem qualquer tratamento.
     *          "rawValue" => ,
     *
     *          // mixed            O valor passado transformado para o formato de armazenamento.
     *          "value" => ,
     *
     *          // bool             Indica se o valor pode ou não ser definido para o campo.
     *          "canSet" => ,
     *
     *          // bool             Indica se o valor pode ser aceito por este campo.
     *          "valid" => ,
     *
     *          // string|string[]  Código/s do estado da validação.
     *          "state" => ,
     *
     *          // ?string          Código do estado da coleção validada.
     *          "cState" => null
     *      ];
     * ```
     *
     * @param       mixed $v
     *              Valor que será testado.
     *
     * @return      array
     */
    protected function modelIndividualValue_ProccessSet($v) : array
    {
        $ivCV = $this->modelIndividualValue_CheckValue($v);

        if ($ivCV["canSet"] === false) {
            $ivCV["rawValue"]   = undefined;
            $ivCV["value"]      = undefined;
        } else {
            $ivCV["rawValue"]   = $v;
            $ivCV["value"]      = $this->modelIndividualValue_RetrieveInStorageFormat($v);
        }

        return $ivCV;
    }
    /**
     * Retorna o valor indicado conforme as definições de formatação.
     *
     * @param       mixed $val
     *              Valor que será tratado.
     *
     * @param       bool $formated
     *              Este parametro só surte efeto se houver um ``inputFormat`` definido.
     *              Se ``true``, retornará o valor conforme o padrão ``inputFormat`` define.
     *
     * @return      mixed
     */
    protected function modelIndividualValue_ProccessGet($val, bool $formated = false)
    {
        $r = $val;
        if ($val !== undefined && $val !== null) {
            if ($formated === true) {
                $r = $val->getValues();
            } else {
                $r = $val->getStorageValues();
            }
        }
        return $r;
    }










    /**
     * Método de interface geral para ``CheckValue``.
     *
     * Deve ser substituído dentro de cada classe especialista de forma a apontar para o
     * devido processo compatível com os critérios definidos.
     *
     * O ``array`` retornado terá a seguinte definição:
     *
     * ``` php
     *      $arr = [
     *          // bool             Indica se o valor pode ou não ser definido para o campo.
     *          "canSet" => ,
     *
     *          // bool             Indica se o valor pode ser aceito por este campo.
     *          "valid" => ,
     *
     *          // string|string[]  Código/s do estado da validação.
     *          "state" => ,
     *
     *          // ?string          Código do estado da coleção validada.
     *          "cState" => null
     *      ];
     * ```
     *
     * @param       mixed $v
     *              Valor que será testado.
     *
     * @return      array
     */
    protected function internal_CheckValue($v) : array
    {
        return $this->modelIndividualValue_CheckValue($v);
    }
    /**
     * Método de interface geral para ``ProccessSet``.
     *
     * Deve ser substituído dentro de cada classe especialista de forma a apontar para o
     * devido processo compatível com os critérios definidos.
     *
     * O ``array`` retornado terá a seguinte definição:
     *
     * ``` php
     *      $arr = [
     *          // mixed            O valor passado de forma bruta, sem qualquer tratamento.
     *          "rawValue" => ,
     *
     *          // mixed            O valor passado transformado para o formato de armazenamento.
     *          "value" => ,
     *
     *          // bool             Indica se o valor pode ou não ser definido para o campo.
     *          "canSet" => ,
     *
     *          // bool             Indica se o valor pode ser aceito por este campo.
     *          "valid" => ,
     *
     *          // string|string[]  Código/s do estado da validação.
     *          "state" => ,
     *
     *          // ?string          Código do estado da coleção validada.
     *          "cState" => null
     *      ];
     * ```
     *
     * @param       mixed $v
     *              Valor que será testado.
     *
     * @return      array
     */
    protected function internal_ProccessSet($v) : array
    {
        return $this->modelIndividualValue_ProccessSet($v);
    }
    /**
     * Método de interface geral para ``ProccessGet``.
     *
     * Deve ser substituído dentro de cada classe especialista de forma a apontar para o
     * devido processo compatível com os critérios definidos.
     *
     * @param       mixed $val
     *              Valor que será tratado.
     *
     * @param       bool $formated
     *              Este parametro só surte efeto se houver um ``inputFormat`` definido.
     *              Se ``true``, retornará o valor conforme o padrão ``inputFormat`` define.
     *
     * @return      mixed
     */
    protected function internal_ProccessGet($val, bool $formated = false)
    {
        return $this->modelIndividualValue_ProccessGet($val, $formated);
    }




















    /**
     * Verifica se algum valor já foi definido para algum campo deste modelo de dados.
     * Internamente executa o método ``iModel->isInitial()``.
     *
     * A partir do acionamento de qualquer método de alteração de campos e obter sucesso
     * ao defini-lo, o resultado deste método será sempre ``false``.
     *
     * @return      bool
     */
    public function isInitial() : bool
    {
        return ($this->value === undefined || ($this->value !== null && $this->value->isInitial() === true));
    }





    /**
     * Retorna uma instância do modelo de dados usada por este campo.
     *
     * @return      iModel
     */
    public function getModel() : iModel
    {
        return $this->modelFactory->createDataModel($this->modelName);
    }
    /**
     * Retorna o nome do modelo de dados usado.
     *
     * @return      string
     */
    public function getModelName() : string
    {
        return $this->modelName;
    }
    /**
     * Retornará a instância do valor que está definida para o campo.
     *
     * Em campos *collection* será retornado o ``array`` contendo as instâncias que
     * compõe a coleção atual.
     *
     * @return      iModel|iModel[]
     */
    public function getInstanceValue()
    {
        return $this->value;
    }












    /**
     * Inicia um novo campo de dados.
     *
     * O ``array`` de configuração deve ter a seguinte definição:
     *
     * ``` php
     *      $arr = [
     *          // string           Nome do campo.
     *          "name" => ,
     *
     *          // string           Descrição do campo. (opcional)
     *          "description" => ,
     *
     *          // string           Nome do modelo de dados a ser usado por este campo. Uma vez definido,
     *          //                  irá anular qualquer definição de propriedades incompatíveis com esta e, a
     *          //                  propriedade "type" será definida como "reference". (opcional)
     *          "modelName" => ,
     *
     *          // bool             Indica se "null" é um valor aceito para este campo. (opcional)
     *          "allowNull" => ,
     *
     *          // bool             Indica se o campo é apenas de leitura.
     *          //                  Neste caso ele poderá ser definido apenas 1 vez e após
     *          //                  isto seu valor não poderá ser alterado. (opcional)
     *          "readOnly" => ,
     *
     *          // mixed            Valor que inicia com o campo.
     *          "value" => ,
     *      ];
     * ```
     *
     * @param       array $config
     *              ``array`` associativo com as configurações para este campo.
     *
     * @param       iModelFactory $factory
     *              Instância de uma fábrica de modelos para ser usada internamente caso a
     *              nova instância represente um campo que utiliza modelos de dados.
     *
     * @throws      \InvalidArgumentException
     *              Caso algum valor passado não seja válido.
     */
    function __construct(array $config, iModelFactory $factory)
    {
        $config["type"]                 = "String";
        $config["inputFormat"]          = null;
        $config["length"]               = null;
        $config["min"]                  = null;
        $config["max"]                  = null;
        $config["allowEmpty"]           = false;
        $config["convertEmptyToNull"]   = false;
        $config["default"]              = undefined;
        $config["enumerator"]           = undefined;

        parent::__construct($config);

        // Resgata as propriedades definidas
        $modelName = ((isset($config["modelName"])) ? $config["modelName"] : null);

        if ($factory->hasDataModel($modelName) === false) {
            $msg = "The data model to be used is not provided by the \"iModelFactory\" instance [\"$modelName\"].";
            throw new \InvalidArgumentException($msg);
        }

        $this->modelName        = $modelName;
        $this->modelFactory     = $factory;

        if ($this->value === undefined) {
            $this->fieldState_IsValid       = $this->validateValue(null);
            $this->fieldState_CurrentState  = $this->getLastValidateState();
        }
    }
}
