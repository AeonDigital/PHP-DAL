<?php
declare (strict_types=1);

namespace AeonDigital\DataModel\Abstracts;

use AeonDigital\Interfaces\DataModel\iModel as iModel;
use AeonDigital\Interfaces\DataModel\iField as iField;







/**
 * Classe abstrata que implementa ``iModel``.
 *
 * @package     AeonDigital\DataModel
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2019, Rianna Cantarelli
 * @license     ADPL-v1.0
 */
abstract class aModel implements iModel
{





    /**
     * Verifica se o valor passado é uma instância ``iModel``.
     *
     * @param       mixed $v
     *              Objeto que será testado.
     *
     * @return      bool
     */
    private function checkIfValueIsModel($v) : bool
    {
        $r = false;
        if (\is_object($v) === true) {
            $interfaces = \class_implements($v);
            $r = (\in_array(iModel::class, $interfaces) === true);
        }
        return $r;
    }




    /**
     * Nome do modelo de dados.
     *
     * @var         string
     */
    private string $name = "";
    /**
     * Define o nome do modelo de dados.
     * O nome de um modelo de dados apenas pode aceitar caracteres ``a-zA-Z0-9_``.
     *
     * @param       string $n
     *              Nome do modelo de dados.
     *
     * @throws      \InvalidArgumentException
     *              Caso o nome indicado seja inválido.
     */
    private function setName(string $n) : void
    {
        if ($n === "") {
            $msg = "Invalid configuration. The attribute \"name\" is required.";
            throw new \InvalidArgumentException($msg);
        } else {
            // Se forem encontrados caracteres inválidos para o nome do modelo de dados
            \preg_match("/^[a-zA-Z0-9_]+$/", $n, $fnd);
            if (\count($fnd) === 0) {
                $msg = "Invalid given field name [\"" . $n . "\"].";
                throw new \InvalidArgumentException($msg);
            }
            $this->name = $n;
        }
    }
    /**
     * Retorna o nome do modelo de dados.
     *
     * @return      string
     */
    public function getName() : string
    {
        return $this->name;
    }





    /**
     * Descrição de uso/funcionalidade do modelo de dados.
     *
     * @var         string
     */
    private string $description = "";
    /**
     * Define a descrição de uso/funcionalidade do modelo de dados.
     *
     * @param       string $d
     *              Descrição do modelo de dados.
     *
     * @return      void
     */
    private function setDescription(string $d) : void
    {
        $this->description = $d;
    }
    /**
     * Retorna a descrição de uso/funcionalidade do modelo de dados.
     *
     * @return      string
     */
    public function getDescription() : string
    {
        return $this->description;
    }










    /**
     * Causa uma exception padrão para quando o campo de nome indicado não existir.
     *
     * @param       string $f
     *              Nome do campo.
     *
     * @return      void
     */
    protected function throwErrorIfFieldDoesNotExists(string $f) : void
    {
        if ($this->hasField($f) === false) {
            $msg = "Non-existent field name [\"$f\"].";
            throw new \InvalidArgumentException($msg);
        }
    }





    /**
     * Coleção de campos que compõe este modelo de dados.
     * Os campos são armazenados em um array associativo conforme o modelo:
     *
     * ``` php
     *      // "key"    string      Nome do campo [armazenado em lowercase].
     *      // value    "iField"    Instância do campo.
     *      $arr = [
     *          "fname" => iFieldInstance
     *      ];
     * ```
     *
     * @var         array
     */
    private array $fieldsCollection = [];
    /**
     * Permite adicionar um novo campo na coleção deste modelo de dados.
     *
     * @param       iField $field
     *              Instância do campo que será adicionado neste modelo de dados.
     *
     * @return      void
     *
     * @throws      \InvalidArgumentException
     */
    private function addField(iField $field) : void
    {
        $name = \strtolower($field->getName());
        if (isset($this->fieldsCollection[$name]) === true) {
            $msg = "Field name duplicated [\"" . $field->getName() . "\"].";
            throw new \InvalidArgumentException($msg);
        } else {
            $this->fieldsCollection[$name] = $field;
        }
    }



    /**
     * Retorna o objeto ``iField`` referente ao campo de nome indicado.
     *
     * @param       string $f
     *              Nome do campo que será retornado.
     *
     * @return      ?iField
     */
    protected function getField(string $f) : ?iField
    {
        $r = null;
        if ($this->hasField($f)) {
            $r = $this->fieldsCollection[\strtolower($f)];
        }
        return $r;
    }



    /**
     * Identifica se o campo com o nome indicado existe neste modelo de dados.
     *
     * @param       string $f
     *              Nome do campo que será verificado.
     *
     * @return      bool
     */
    public function hasField(string $f) : bool
    {
        return (isset($this->fieldsCollection[\strtolower($f)]) === true);
    }



    /**
     * Retorna a contagem total dos campos existentes para este modelo de dados.
     *
     * @return      int
     */
    public function countFields() : int
    {
        return \count($this->fieldsCollection);
    }



    /**
     * Retorna um ``array`` contendo o nome de cada um dos campos existentes neste
     * modelo de dados.
     *
     * @return      array
     */
    public function getFieldNames() : array
    {
        $r = [];
        foreach ($this->fieldsCollection as $fieldName => $field) {
            $r[] = $field->getName();
        }
        return $r;
    }



    /**
     * Retorna um ``array`` associativo contendo todos os campos definidos para o
     * modelo atual e seus respectivos valores iniciais.
     *
     * @return      array
     */
    public function getInitialDataModel() : array
    {
        $r = [];
        foreach ($this->fieldsCollection as $fieldName => $field) {
            $r[$field->getName()] = $field->getDefault();
        }
        return $r;
    }










    /**
     * Identifica se já houve qualquer alteração em qualquer dos campos deste modelo de dados.
     *
     * @var         bool
     */
    private bool $modelState_InitialState = true;
    /**
     * Verifica se algum valor já foi definido para algum campo deste modelo de dados.
     *
     * A partir do acionamento de qualquer método de alteração de campos e obter sucesso
     * ao defini-lo, o resultado deste método será sempre ``false``.
     *
     * @return      bool
     */
    public function isInitial() : bool
    {
        return $this->modelState_InitialState;
    }



    /**
     * Informa se o modelo de dados tem no momento valores que satisfazem os critérios de
     * validação para todos os seus campos.
     *
     * @return      bool
     */
    public function isValid() : bool
    {
        $validFields = [];
        foreach ($this->fieldsCollection as $fieldName => $field) {
            $validFields[] = $field->isValid();
        }
        return (\in_array(false, $validFields) === false);
    }



    /**
     * Retorna o código do estado atual deste modelo de dados.
     * Se todos seus campos estão com valores válidos será retornado ``valid``.
     *
     * Caso contrário, será retornado um ``array`` associativo com o estado de cada um dos
     * campos.
     *
     * Campos *collection* trarão um ``array`` associativo conforme o modelo:
     *
     * ```php
     *      $arr = [
     *          // string   Estado geral da coleção como um todo.
     *          "collection" => "",
     *
     *          // string[] Estado individual de cada um dos itens.
     *          "itens" => []
     *      ];
     * ```
     *
     * @return      string|array
     */
    public function getState()
    {
        if ($this->isValid() === true) {
            return "valid";
        } else {
            $state = [];
            foreach ($this->fieldsCollection as $fieldName => $field) {
                $fieldName = $field->getName();

                if ($field->isCollection() === true) {
                    $state[$fieldName] = [
                        "collection"    => $field->collectionGetState(),
                        "itens"         => $field->getState()
                    ];
                } else {
                    $state[$fieldName] = $field->getState();
                }
            }
            return $state;
        }
    }



    /**
     * Mantêm os códigos referentes a última validação executada.
     *
     * Este valor é sobrescrito sempre que um método que exige validação for acionado,
     * portanto, sempre conterá o valor da última validação realizada.
     *
     * @var         string|array
     */
    private $modelState_ValidateState = null;
    /**
     * Referente a última validação executada:
     * Se todos seus campos estão com valores válidos será retornado ``valid``.
     *
     * Caso contrário, será retornado um ``array`` associativo com o estado de cada um dos campos.
     *
     * Quando executado após o uso de ``setFieldValue()`` o resultado será equivalente ao uso de
     * ``iField->getLastValidateState()``.
     *
     * Campos *collection* trarão um ``array`` associativo conforme o modelo:
     *
     * ```php
     *      $arr = [
     *          // string   Estado geral da coleção como um todo.
     *          "collection" => "",
     *
     *          // string[] Estado individual de cada um dos itens.
     *          "itens" => []
     *      ];
     * ```
     *
     * @return      string|array
     */
    public function getLastValidateState()
    {
        return $this->modelState_ValidateState;
    }


    /**
     * Mantêm a última verificação ``canSet`` referente a última validação realizada
     * para este campo de dados.
     *
     * @var         bool
     */
    protected bool $modelState_ValidateStateCanSet = true;
    /**
     * Retornará ``true`` caso a última validação realizada permitir que o valor testado seja
     * definido para o modelo de dados usado.
     *
     * @return      bool
     */
    public function getLastValidateCanSet() : bool
    {
        return $this->modelState_ValidateStateCanSet;
    }


    /**
     * Verifica se o valor indicado satisfaz os critérios que de validação dos campos em comum
     * que ele tenha com o presente modelo de dados.
     *
     * A validação é feita seguindo os seguintes passos:
     * 1. Verifica se o valor passado é ``iterable``.
     * 2. Verifica se o valor passado possui alguma propriedade/campo que seja inexistênte
     *    para o modelo de dados desta instância.
     * 3. Verifica se nenhuma propriedade foi encontrada no objeto passado.
     * 4. Se ``checkAll`` for definido como ``true`` então irá verificar se restou ser
     *    apresentado algum campo obrigatorio. Campos que tenham configuração de valor default
     *    não invalidarão este tipo de teste.
     *
     *
     * **Método "getLastValidateState()"**
     * Após uma validação é possível usar este método para averiguar com precisão qual foi o
     * motivo da falha.
     * Para os passos **1** e **3** será retornado uma ``string`` única com o código do erro.
     * Para os passos **2** e **4** será retornado um ``array`` associativo contendo uma chave
     * para cada campo testado e seu respectivo código de validação.
     *
     *
     * **Método "getLastValidateCanSet()"**
     * Após uma validação é possível usar este método para averiguar se o valor passado,
     * passando ou não, pode ser efetivamente definido para o modelo de dados.
     *
     *
     * @param       mixed $objValues
     *              Objeto que traz os valores a serem testados.
     *
     * @param       bool $checkAll
     *              Quando ``true`` apenas confirmará a validade da coleção de valores se com os
     *              mesmos for possível preencher todos os campos obrigatórios deste modelo de
     *              dados. Campos não declarados mas que possuem um valor padrão definido **SEMPRE**
     *              passarão neste tipo de validação
     *
     * @return      bool
     *
     * @throws      \InvalidArgumentException
     *              Caso o objeto passado possua propriedades não correspondentes aos campos
     *              definidos.
     */
    public function validateValues($objValues, bool $checkAll = false) : bool
    {
        $validateResult = [];
        $validateState  = [];
        $validateCanSet = [];
        $fails          = 0;

        if (\is_iterable($objValues) === false) {
            $fails++;
            $validateState = "error.dm.model.expected.iterable.object";
            $validateCanSet[] = false;
        } else {
            $hasInvalidField = false;

            // Testa cada uma das propriedades definidas...
            foreach ($objValues as $fieldName => $fieldValue) {
                $field = $this->getField($fieldName);

                if ($field === null) {
                    $fails++;
                    $validateResult[$fieldName] = "error.dm.model.unespected.field.name";
                    $validateCanSet[] = false;
                    $hasInvalidField = true;
                } else {
                    if ($field->validateValue($fieldValue) === false) {
                        $fails++;
                    }
                    $validateResult[$field->getName()]  = $field->getLastValidateState();
                    $validateCanSet[] = $field->getLastValidateCanSet();
                }
            }


            if ($hasInvalidField === true) {
                $validateState = $validateResult;
            } else {
                if ($validateResult === []) {
                    $fails++;
                    $validateState      = "error.dm.model.value.not.allow.empty.object";
                    $validateCanSet[]   = false;
                } else {
                    $validateState = $validateResult;

                    if ($checkAll === true) {
                        $allFieldNames = $this->getFieldNames();

                        // Se o total de campos testados é diferente do total de campos existentes.
                        if (\count($allFieldNames) !== \count(\array_keys($validateResult))) {

                            // Verifica campo por campo para identificar os que
                            // não foram testados ainda.
                            foreach ($allFieldNames as $fieldName) {
                                if (\key_exists($fieldName, $validateResult) === true) {
                                    $validateState[$fieldName] = $validateResult[$fieldName];
                                } else {
                                    $field = $this->getField($fieldName);
                                    $r = $field->validateValue($field->getDefault());
                                    if ($r === false) {
                                        $fails++;
                                    }
                                    $validateState[$fieldName] = $field->getLastValidateState();
                                    $validateCanSet[] = $field->getLastValidateCanSet();
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->modelState_ValidateState         = (($fails === 0) ? "valid" : $validateState);
        $this->modelState_ValidateStateCanSet   = (\in_array(false, $validateCanSet) === false);
        return ($fails === 0);
    }











    /**
     * Define o valor do campo de nome indicado.
     * Internamente executa o método ``iField->setValue()``.
     *
     * @param       string $f
     *              Nome do campo cujo valor será definido.
     *
     * @param       mixed $v
     *              Valor a ser definido para o campo.
     *
     * @return      bool
     *              Retornará ``true`` se o valor tornou o campo válido ou ``false`` caso
     *              agora ele esteja inválido.
     *              Também retornará ``false`` caso o valor seja totalmente incompatível
     *              com o campo.
     *
     * @throws      \InvalidArgumentException
     *              Caso o nome do campo não seja válido.
     */
    public function setFieldValue(string $f, $v) : bool
    {
        $this->throwErrorIfFieldDoesNotExists($f);
        $this->modelState_InitialState = false;

        $field = $this->getField($f);
        $fName = $field->getName();

        $r = $field->setValue($v);

        $this->modelState_ValidateState         = $field->getLastValidateState();
        $this->modelState_ValidateStateCanSet   = $field->getLastValidateCanSet();
        return $r;
    }



    /**
     * Retorna o valor atual do campo de nome indicado.
     * Internamente executa o método ``iField->getValue()``.
     *
     * @param       string $f
     *              Nome do campo alvo.
     *
     * @return      mixed
     *
     * @throws      \InvalidArgumentException
     *              Caso o nome do campo não seja válido.
     */
    public function getFieldValue(string $f)
    {
        $this->throwErrorIfFieldDoesNotExists($f);
        return $this->getField($f)->getValue();
    }



    /**
     * Retorna o valor atual do campo de nome indicado.
     * Internamente executa o método ``iField->getStorageValue()``.
     *
     * @param       string $f
     *              Nome do campo alvo.
     *
     * @return      mixed
     *
     * @throws      \InvalidArgumentException
     *              Caso o nome do campo não seja válido.
     */
    public function getFieldStorageValue(string $f)
    {
        $this->throwErrorIfFieldDoesNotExists($f);
        return $this->getField($f)->getStorageValue();
    }



    /**
     * Retorna o valor atual do campo de nome indicado.
     * Internamente executa o método ``iField->getRawValue()``.
     *
     * @param       string $f
     *              Nome do campo alvo.
     *
     * @return      mixed
     *
     * @throws      \InvalidArgumentException
     *              Caso o nome do campo não seja válido.
     */
    public function getFieldRawValue(string $f)
    {
        $this->throwErrorIfFieldDoesNotExists($f);
        return $this->getField($f)->getRawValue();
    }










    /**
     * Permite definir o valor de inúmeros campos do modelo de dados a partir de um objeto
     * compatível.
     *
     * Se todos os valores passados forem possíveis de serem definidos para seus respectivos
     * campos de dados então isto será feito mesmo que isto  torne o modelo como um todo
     * inválido.
     *
     * @param       mixed $objValues
     *              Objeto que traz os valores a serem redefinidos para o atual modelo de
     *              dados.
     *
     * @param       bool $checkAll
     *              Quando ``true`` apenas irá definir os dados caso seja possível definir
     *              todos os campos do modelo de dados com os valores explicitados.
     *              Os campos não definidos devem poder serem definidos com seus valores
     *              padrão, caso contrário o *set* não será feito.
     *
     * @return      bool
     *              Retornará ``true`` caso os valores passados tornem o modelo válido.
     *
     * @throws      \InvalidArgumentException
     *              Caso o objeto passado possua propriedades não correspondentes aos campos
     *              definidos.
     */
    public function setValues($objValues, bool $checkAll = false) : bool
    {
        $r = $this->validateValues($objValues, $checkAll);
        if ($this->getLastValidateCanSet() === true) {
            foreach ($objValues as $fieldName => $fieldValue) {
                $this->getField($fieldName)->setValue($fieldValue);
            }
            $this->modelState_InitialState = false;
        }
        return $r;
    }





    /**
     * Retorna um ``array`` associativo contendo todos os campos do modelo de dados e seus
     * respectivos valores atualmente definidos.
     *
     * Internamente executa o método ``iField->getValue()`` para cada um dos campos de dados
     * existente.
     *
     * @return      array
     */
    public function getValues() : array
    {
        $r = [];
        foreach ($this->fieldsCollection as $fieldName => $field) {
            $r[$field->getName()] = $field->getValue();
        }
        return $r;
    }



    /**
     * Retorna um ``array`` associativo contendo todos os campos do modelo de dados e seus
     * respectivos valores atualmente definidos.
     *
     * Internamente executa o método ``iField->getStorageValue()`` para cada um dos campos
     * de dados existente.
     *
     * @return      array
     */
    public function getStorageValues() : array
    {
        $r = [];
        foreach ($this->fieldsCollection as $fieldName => $field) {
            $r[$field->getName()] = $field->getStorageValue();
        }
        return $r;
    }


    /**
     * Retorna um ``array`` associativo contendo todos os campos do modelo de dados e seus
     * respectivos valores atualmente definidos.
     *
     * Internamente executa o método ``iField->getRawValue()`` para cada um dos campos de
     * dados existente.
     *
     * @return      array
     */
    public function getRawValues() : array
    {
        $r = [];
        foreach ($this->fieldsCollection as $fieldName => $field) {
            $val = $field->getRawValue();

            /*if ($field->isReference() === true) {
                if ($field->isCollection() === true) {
                    $useVal = [];
                    foreach ($val as $inst) {
                        $useVal[] = $inst->getRawValue();
                    }
                    $val = $useVal;
                } else {
                    $val = $val->getRawValue();
                }
            }*/

            $r[$field->getName()] = $val;
        }
        return $r;
    }









    /**
     * Inicia um novo modelo de dados.
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
     *          // iField[]         Array contendo as instâncias dos campos que devem compor este
     *          //                  modelo de dados.
     *          "fields" => ,
     *      ];
     * ```
     * @param       array $config
     *              Array associativo com as configurações para este modelo de dados.
     *
     * @throws      \InvalidArgumentException
     *              Caso algum valor passado não seja válido.
     */
    function __construct(array $config)
    {

        // Resgata as propriedades definidas
        $name               = ((isset($config["name"]))                 ? $config["name"]               : "");
        $description        = ((isset($config["description"]))          ? $config["description"]        : "");
        $fields             = ((isset($config["fields"]))               ? $config["fields"]             : null);



        if (\is_array($fields) === false) {
            $msg = "Invalid given fields. Must be an array of \"iField\" objects.";
            throw new \InvalidArgumentException($msg);
        } elseif (\count($fields) === 0) {
            $msg = "At least one field must be defined.";
            throw new \InvalidArgumentException($msg);
        }


        // Seta propriedades definidas
        $this->setName($name);
        $this->setDescription($description);

        foreach ($fields as $field) {
            $this->addField($field);
        }
    }









    /**
     * Indica se ao acessar ``_call`` é para ativar o método principal
     * (definido nesta classe abstrata).
     *
     * @var         bool
     */
    protected bool $useMainCall = true;
    /**
     * Método que deve ser definido nas classes concretas e que permitem expandir o uso
     * do método ``__call``.
     *
     * @param       string $name
     *              Nome do método.
     *              É preciso ter o prefixo ``new`` e o nome do campo que será
     *              automaticamente definido.
     *
     * @param       array $arguments
     *              Opcionalmente pode ser definido uma coleção de valores a serem
     *              definidos para a nova instância.
     *
     * @return      mixed
     */
    abstract protected function extendCall($name, $arguments);
    /**
     * Permite efetuar o auto-set de um dos campos quando este for do tipo *reference*.
     *
     * @param       string $name
     *              Nome do método.
     *              É preciso ter o prefixo ``new`` e o nome do campo que será
     *              automaticamente definido.
     *
     * @param       array $arguments
     *              Opcionalmente pode ser definido uma coleção de valores a serem
     *              definidos para a nova instância.
     *
     * @return      mixed
     */
    public function __call($name, $arguments)
    {
        $r = $this->extendCall($name, $arguments);
        if ($this->useMainCall === false) {
            return $r;
        }
        else {
            $field      = null;
            $useName    = null;
            $action     = null;


            if (\mb_str_starts_with($name, "new") === true) {
                $useName    = \substr($name, 3);
                $action     = "new";
            } elseif (\mb_str_starts_with($name, "add") === true) {
                $useName    = \substr($name, 3);
                $action     = "add";
            } else {
                $useName    = $name;
                $action     = "get";
            }


            $this->throwErrorIfFieldDoesNotExists($useName);
            $field  = $this->getField($useName);
            $ref    = $field->isReference();
            $col    = $field->isCollection();


            if ($ref === true && $col === false) {
                // Define o campo de nome passado com uma nova instância
                // do modelo de dados que ele utiliza.
                if ($action === "new") {
                    $inst = $field->getModel();
                    if (\count($arguments) > 0) {
                        $inst->setValues($arguments[0]);
                    }
                    $field->setValue($inst);
                } elseif ($action === "get") {
                    return $field->getInstanceValue();
                }
            } elseif ($ref === true && $col === true) {
                // Adiciona um número determinado de novas instâncias
                // à coleção atualmente definida.
                // Se nenhuma quantidade de itens for explicitado, apenas 1 item
                // será adicionado
                if ($action === "add") {
                    $n = ((\count($arguments) > 0) ? $arguments[0] : 1);

                    if (\is_int($n) === false) {
                        $msg = "The argument must be an integer.";
                        throw new \InvalidArgumentException($msg);
                    } else {
                        $field = $this->getField($useName);

                        for ($i = 0; $i < $n; $i++) {
                            $field->collectionAddValue($field->getModel());
                        }
                    }
                } elseif($action === "get") {
                    $index = ((\count($arguments) > 0) ? $arguments[0] : null);

                    if ($index === null) {
                        return $field->getInstanceValue();
                    }
                    elseif (\is_int($index) === false || $index >= $field->collectionCount() || $index < 0) {
                        return null;
                    }
                    else {
                        return $field->getInstanceValue()[$index];
                    }
                }
            }
        }
    }




    /**
     * Permite efetuar o SET do valor de um campo utilizando uma notação amigável.
     *
     * Internamente executa o método ``setFieldValue()``.
     * Não retorna nenhum valor, e, caso o valor passado não seja válido para este campo,
     * nenhuma alteração será feita sobre o valor pré-existente.
     *
     * @param       string $name
     *              Nome do campo.
     *
     * @param       mixed $value
     *              Valor a ser definido.
     */
    public function __set($name, $value)
    {
        $this->throwErrorIfFieldDoesNotExists($name);
        $this->setFieldValue($name, $value);
    }



    /**
     * Permite efetuar o GET do valor de um campo utilizando uma notação amigável.
     *
     * Internamente executa o método ``getFieldValue()``.
     *
     * @param       string $name
     *              Nome do campo.
     *
     * @return      mixed
     */
    public function __get($name)
    {
        $useName = $name;
        $isField = false;

        if (\mb_str_starts_with($name, "_") === true) {
            $useName = \substr($name, 1);
            $isField = true;
        }

        $this->throwErrorIfFieldDoesNotExists($useName);
        $field = $this->getField($useName);

        if ($isField === true) {
            return $field;
        } else {
            if ($field->isReference() === true) {
                return $field->getInstanceValue();
            } else {
                return $field->getValue();
            }
        }
    }



    /**
     * Método que permite a iteração sobre os valores armazenados na coleção de dados da
     * instância usando ``foreach()`` do PHP.
     *
     * ```php
     *     $oModel = new iModel();
     *     ...
     *     foreach($oModel as $fieldName => $fieldValue) { ... }
     * ```
     *
     * @link        http://php.net/manual/pt_BR/iteratoraggregate.getiterator.php
     *
     * @return      \Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getValues());
    }
}
