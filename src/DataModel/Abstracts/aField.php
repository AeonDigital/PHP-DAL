<?php
declare (strict_types=1);

namespace AeonDigital\DataModel\Abstracts;

use AeonDigital\Interfaces\DataModel\iField as iField;
use AeonDigital\Interfaces\DataModel\iModel as iModel;
use AeonDigital\Interfaces\DataModel\iModelFactory as iModelFactory;






/**
 * Classe abstrata que implementa ``iField``.
 *
 * @package     AeonDigital\DataModel
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2019, Rianna Cantarelli
 * @license     ADPL-v1.0
 */
abstract class aField implements iField
{






    /**
     * Reflection da classe definida para o tipo deste campo.
     *
     * @var         \ReflectionClass
     */
    private \ReflectionClass $typeReflection;



    /**
     * Identifica se o nome da classe passada para o ``simpleType`` corresponde a uma
     * classe ``iSimpleType`` válida.
     *
     * @return      bool
     */
    private function isValidSimpleType() : bool
    {
        $ns = "AeonDigital\\Interfaces\\SimpleType\\iSimpleType";
        return ($this->typeReflection->implementsInterface($ns) === true);
    }
    /**
     * Identifica se a classe passada para o ``simpleType`` implementa a namespace ``iBool``.
     *
     * @return      bool
     */
    private function isSimpleTypeBool() : bool
    {
        $ns = "AeonDigital\\Interfaces\\SimpleType\\iBool";
        return ($this->typeReflection->implementsInterface($ns) === true);
    }
    /**
     * Identifica se a classe passada para o ``simpleType`` implementa a namespace ``iNumeric``.
     *
     * @return      bool
     */
    private function isSimpleTypeNumeric() : bool
    {
        $ns = "AeonDigital\\Interfaces\\SimpleType\\iNumeric";
        return ($this->typeReflection->implementsInterface($ns) === true);
    }
    /**
     * Identifica se a classe passada para o ``simpleType``` implementa a namespace ``iReal``.
     *
     * @return      bool
     */
    private function isSimpleTypeReal() : bool
    {
        $ns = "AeonDigital\\Interfaces\\SimpleType\\iReal";
        return ($this->typeReflection->implementsInterface($ns) === true);
    }
    /**
     * Identifica se a classe passada para o ``simpleType`` implementa a namespace ``iDateTime``.
     *
     * @return      bool
     */
    private function isSimpleTypeDateTime() : bool
    {
        $ns = "AeonDigital\\Interfaces\\SimpleType\\iDateTime";
        return ($this->typeReflection->implementsInterface($ns) === true);
    }
    /**
     * Identifica se a classe passada para o ``simpleType`` implementa a namespace ``iString``.
     *
     * @return      bool
     */
    private function isSimpleTypeString() : bool
    {
        $ns = "AeonDigital\\Interfaces\\SimpleType\\iString";
        return ($this->typeReflection->implementsInterface($ns) === true);
    }



    /**
     * Retorna uma ``string`` simples que permite identificar o tipo de dado que este campo
     * está apto a armazenar.
     *
     * @return      string
     *              Os valores a serem retornados podem ser:
     *              ``bool``, ``numeric``, ``real``, ``DateTime``, ``string``, ``reference``
     */
    protected function identifySimpleType() : string
    {
        $str = null;

        if ($this->isReference() === true)              { $str = "reference"; }
        elseif ($this->isSimpleTypeBool() === true)     { $str = "Bool"; }
        elseif ($this->isSimpleTypeNumeric() === true)  { $str = "Numeric"; }
        elseif ($this->isSimpleTypeReal() === true)     { $str = "Real"; }
        elseif ($this->isSimpleTypeDateTime() === true) { $str = "DateTime"; }
        elseif ($this->isSimpleTypeString() === true)   { $str = "String"; }

        return $str;
    }





    /**
     * Reflection da classe definida para o ``inputFormat``.
     *
     * @var         \ReflectionClass
     */
    private \ReflectionClass $inputFormatReflection;



    /**
     * Identifica se o nome da classe passada para o ``inputFormat`` corresponde a uma classe
     * ``iFormat`` válida.
     *
     * @return      bool
     */
    private function isValidInputFormat() : bool
    {
        $ns = "AeonDigital\\Interfaces\\DataFormat\\iFormat";
        return ($this->inputFormatReflection->implementsInterface($ns) === true);
    }
    /**
     * Indica se o valor passado, se for uma instrução ``NOW()`` pode ou não ser usada para
     * este campo.
     *
     * @param       mixed $v
     *              Valor que será testado.
     *
     * @return      bool
     */
    private function isValidNowInstruction($v) : bool
    {
        return ($this->isSimpleTypeDateTime() === true &&
                ($v !== null && \is_string($v) === true) &&
                (\strtoupper($v) === "NOW()" || \strtoupper($v) === "NOW"));
    }










    /**
     * Nome do campo.
     *
     * @var         string
     */
    private string $name = "";
    /**
     * Define o nome do campo.
     * O nome de um campo apenas pode aceitar caracteres ``a-zA-Z0-9_``.
     *
     * @param       string $n
     *              Nome do campo.
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
            // Se forem encontrados caracteres inválidos para o nome do campo
            \preg_match("/^[a-zA-Z0-9_]+$/", $n, $fnd);
            if (\count($fnd) === 0) {
                $msg = "Invalid given field name [\"" . $n . "\"].";
                throw new \InvalidArgumentException($msg);
            }
            $this->name = $n;
        }
    }
    /**
     * Retorna o nome do campo.
     *
     * @return      string
     */
    public function getName() : string
    {
        return $this->name;
    }





    /**
     * Descrição de uso/funcionalidade do campo.
     *
     * @var         string
     */
    private string $description = "";
    /**
     * Define a descrição de uso/funcionalidade do campo.
     *
     * @param       string $d
     *              Descrição do campo.
     *
     * @return      void
     */
    private function setDescription(string $d) : void
    {
        $this->description = $d;
    }
    /**
     * Retorna a descrição de uso/funcionalidade do campo.
     *
     * @return      string
     */
    public function getDescription() : string
    {
        return $this->description;
    }





    /**
     * Nome completo da classe que determina o tipo deste campo.
     *
     * @var         string
     */
    private string $type = "";
    /**
     * Define o nome completo da classe que determina o tipo deste campo.
     *
     * A classe informada deve implementar a interface
     * ``AeonDigital\Interfaces\SimpleType\iSimpleType``.
     *
     * @param       string $t
     *              Nome completo da classe a ser usada.
     *
     * @return      void
     *
     * @throws      \InvalidArgumentException
     *              Caso a classe indicada não seja válida.
     */
    private function setType(string $t) : void
    {
        if ($t === "") {
            $msg = "Invalid configuration. The attribute \"type\" is required.";
            throw new \InvalidArgumentException($msg);
        } else {
            if (\class_exists($t) === false || $t === "DateTime") {
                $t = "AeonDigital\\SimpleType\\st" . $t;
            }

            if (\class_exists($t) === false) {
                $msg = "The given \"type\" class does not exists.";
                throw new \InvalidArgumentException($msg);
            } else {
                $this->typeReflection = new \ReflectionClass($t);

                if ($this->isValidSimpleType() === false) {
                    $msg = "The given \"type\" class does not implements the interface \"AeonDigital\\Interfaces\\SimpleType\\iSimpleType\".";
                    throw new \InvalidArgumentException($msg);
                }
                $this->type = $t;
            }
        }
    }
    /**
     * Retorna o nome completo da classe que determina o tipo deste campo.
     *
     * @return      string
     */
    public function getType() : string
    {
        return (($this->isReference() === true) ? "reference" : $this->type);
    }





    /**
     * Array associativo que armazena as principais funções que uma definição de formato
     * de entrada deve ter.
     *
     * @var         ?array
     */
    private ?array $inputFormat = null;
    /**
     * Define um formato para a informação armazenada neste campo.
     *
     * A classe informada deve implementar a interface
     * ``AeonDigital\DataFormat\Interfaces\iFormat``
     * **OU**
     * pode ser passado um ``array`` conforme as definições especificadas abaixo:
     *
     * ``` php
     *      $arr = [
     *          // string   Nome deste tipo de transformação.
     *          "name" => ,
     *
     *          // int      Tamanho máximo que uma string pode ter para ser aceita por este formato.
     *          "length" => ,
     *
     *          // callable Função que valida a string para o tipo de formatação a ser definida.
     *          "check" => ,
     *
     *          // callable Função que remove a formatação padrão.
     *          "removeFormat" => ,
     *
     *          // callable Função que efetivamente formata a string para seu formato final.
     *          "format" => ,
     *
     *          // callable Função que converte o valor para seu formato de armazenamento.
     *          "storageFormat" =>
     *      ];
     * ```
     *
     * @param       ?array|?string $if
     *              Nome completo da classe a ser usada.
     *
     * @return      void
     *
     * @throws      \InvalidArgumentException
     *              Caso a classe indicada não seja válida.
     */
    private function setInputFormat($if) : void
    {
        if ($if !== null) {
            if (\is_array($if) === true) {
                $requiredKeys = ["name", "length", "check", "removeFormat", "format", "storageFormat"];

                foreach ($requiredKeys as $key) {
                    if (\array_key_exists($key, $if) === false) {
                        $msg = "Lost required key in the given input format rule.";
                        throw new \InvalidArgumentException($msg);
                    } else {
                        $msg = null;
                        $kVal = $if[$key];
                        switch ($key) {
                            case "name":
                                if (\is_string($kVal) === false || \strlen($kVal) === 0) {
                                    $msg = "Invalid given \"$key\" of input format. Expected a not empty string.";
                                }
                                break;

                            case "length":
                                if (\is_int($kVal) === false && $kVal !== null) {
                                    $msg = "Invalid given \"$key\" of input format. Expected integer or null.";
                                }
                                break;

                            case "check":
                            case "removeFormat":
                            case "format":
                            case "storageFormat":
                                if (\is_callable($kVal) === false) {
                                    $msg = "Invalid given \"$key\" of input format. Expected callable.";
                                }
                                break;
                        }

                        if ($msg !== null) {
                            throw new \InvalidArgumentException($msg);
                        }
                    }
                }

                $this->inputFormat = [
                    "name"          => \strtoupper($if["name"]),
                    "length"        => (($if["length"] === null) ? null : (int)$if["length"]),
                    "check"         => $if["check"],
                    "removeFormat"  => $if["removeFormat"],
                    "format"        => $if["format"],
                    "storageFormat" => $if["storageFormat"]
                ];
            } else {
                if (\class_exists($if) === false) {
                    $if = "AeonDigital\\DataFormat\\Patterns\\" . \str_replace(".", "\\", $if);
                }

                if (\class_exists($if) === false) {
                    $msg = "The given \"inputFormat\" class does not exists [\"$if\"].";
                    throw new \InvalidArgumentException($msg);
                } else {
                    $this->inputFormatReflection = new \ReflectionClass($if);

                    if ($this->isValidInputFormat($if) === false) {
                        $msg = "The given \"inputFormat\" class does not implements the interface \"AeonDigital\\Interfaces\\DataFormat\\iFormat\".";
                        throw new \InvalidArgumentException($msg);
                    }

                    $this->inputFormat = [
                        "name"          => $if,
                        "length"        => $if::MaxLength,
                        "check"         => $if . "::check",
                        "removeFormat"  => $if . "::removeFormat",
                        "format"        => $if . "::format",
                        "storageFormat" => $if . "::storageFormat"
                    ];
                }
            }
        }
    }
    /**
     * Retorna o nome da classe que determina o formato de entrada que o valor a ser
     * armazenado pode assumir
     * **OU**
     * retorna o nome de uma instrução especial de transformação de caracteres para
     * campos do tipo ``string``.
     *
     * @return      ?string
     */
    public function getInputFormat() : ?string
    {
        return (($this->inputFormat === null) ? null : $this->inputFormat["name"]);
    }





    /**
     * Tamanho máximo (em caracteres) aceitos por este campo.
     *
     * @var     ?int
     */
    private ?int $length = null;
    /**
     * Retorna o tamanho máximo (em caracteres) aceitos por este campo.
     *
     * Apenas poderá ser definido para campos que armazenam ``strings``. Se este campo usa um
     * ``inputFormat``, então esta regra deve ser controlada pela definição que o formato impõe.
     *
     * @param       ?int $l
     *              Número máximo de caracteres aceitos para valores que este campo possa
     *              assumir.
     *
     * @return      void
     */
    private function setLength(?int $l) : void
    {
        if ($this->isSimpleTypeString() === true && $l !== null && $l > 0) {
            $this->length = $l;
        }
    }
    /**
     * Retorna o tamanho máximo (em caracteres) aceitos por este campo.
     * Deve retornar ``null`` quando não há um limite definido.
     *
     * @return      ?int
     */
    public function getLength() : ?int
    {
        return $this->length;
    }





    /**
     * Menor valor possível para um tipo simples numérico ou ``DateTime``.
     *
     * @var         ?int|?\AeonDigital\Numbers\RealNumber|?\DateTime
     */
    private $min = null;
    /**
     * Define o menor valor possível para um tipo numérico ou ``DateTime``.
     *
     * Apenas poderá ser definido para campos cujo tipo simples implemente a interface
     * ``AeonDigital\Interfaces\SimpleType\iNumeric`` ou
     * ``AeonDigital\Interfaces\SimpleType\iDateTime``.
     *
     * Por padrão, herdará este valor da definição de seu ``type`` quando isto for aplicável.
     *
     * Se for explicitamente definido, o valor deverá estar dentro dos limites definidos
     * pelo ``type``.
     *
     * @param       ?int|?\AeonDigital\Numbers\RealNumber|?\DateTime $m
     *              Valor a ser definido.
     *
     * @return      void
     *
     * @throws      \InvalidArgumentException
     *              Caso o valor a ser definido não seja válido.
     */
    private function setMin($m) : void
    {
        if ($this->isSimpleTypeNumeric() === true || $this->isSimpleTypeDateTime() === true) {
            if ($m === null) {
                $this->min = $this->type::min();
            } else {
                // Verifica se o valor passado é válido
                $err = null;
                $m = $this->type::parseIfValidate($m, $err);

                if ($err !== null) {
                    $msg = "Invalid min value.";
                    throw new \InvalidArgumentException($msg);
                } else {
                    $this->min = $m;
                }
            }
        }
    }
    /**
     * Retorna o menor valor possível para um tipo numérico ou ``DateTime``.
     * Por padrão, herdará este valor da definição de seu ``type`` quando isto for aplicável.
     *
     * @return      ?int|?\AeonDigital\Numbers\RealNumber|?\DateTime
     */
    public function getMin()
    {
        return $this->min;
    }





    /**
     * Maior valor possível para um tipo numérico ou ``DateTime``.
     *
     * @var         ?int|?\AeonDigital\Numbers\RealNumber|?\DateTime
     */
    private $max = null;
    /**
     * Define o maior valor possível para um tipo numérico ou ``DateTime``.
     *
     * Apenas poderá ser definido para campos cujo tipo simples implemente a interface
     * ``AeonDigital\SimpleType\Interfaces\iNumeric`` ou
     * ``AeonDigital\SimpleType\Interfaces\iDateTime``.
     *
     * Por padrão, herdará este valor da definição de seu ``type`` quando isto for aplicável.
     *
     * Se for explicitamente definido, o valor deverá estar dentro dos limites definidos
     * pelo ``type``.
     *
     * @param       ?int|?\AeonDigital\Numbers\RealNumber|?\DateTime $m
     *              Valor a ser definido.
     *
     * @return      void
     *
     * @throws      \InvalidArgumentException
     *              Caso o valor a ser definido não seja válido.
     */
    private function setMax($m) : void
    {
        if ($this->isSimpleTypeNumeric() === true || $this->isSimpleTypeDateTime() === true) {
            if ($m === null) {
                $this->max = $this->type::max();
            } else {
                // Verifica se o valor passado é válido
                $err = null;
                $m = $this->type::parseIfValidate($m, $err);

                if ($err !== null) {
                    $msg = "Invalid max value.";
                    throw new \InvalidArgumentException($msg);
                } else {
                    $this->max = $m;
                }
            }
        }
    }
    /**
     * Retorna o maior valor possível para um tipo numérico ou ``DateTime``.
     * Por padrão, herdará este valor da definição de seu ``type`` quando isto for aplicável.
     *
     * @return      ?int|?\AeonDigital\Numbers\RealNumber|?\DateTime
     */
    public function getMax()
    {
        return $this->max;
    }










    /**
     * Propriedade que define se é ou não permitido atribuir ``null`` como um valor
     * válido para este campo.
     *
     * @var         bool
     */
    private bool $allowNull = true;
    /**
     * Define se é ou não permitido atribuir ``null`` como um valor válido para este campo.
     *
     * Por padrão este valor deve ser ``true``.
     *
     * @param       bool $is
     *              Valor a ser definido para esta propriedade.
     *
     * @return      void
     */
    private function setIsAllowNull(bool $is) : void
    {
        $this->allowNull = $is;
    }
    /**
     * Indica se é ou não permitido atribuir ``null`` como um valor válido para este campo.
     *
     * @return      bool
     */
    public function isAllowNull() : bool
    {
        return $this->allowNull;
    }





    /**
     * Propriedade que define se é ou não permitido atribuir ``''`` como um valor válido
     * para este campo.
     *
     * @var         bool
     */
    private bool $allowEmpty = true;
    /**
     * Define se é ou não permitido atribuir ``''`` como um valor válido para este campo.
     *
     * Por padrão este valor deve ser ``true``.
     *
     * @param       bool $is
     *              Valor a ser definido para esta propriedade.
     *
     * @return      void
     */
    private function setIsAllowEmpty(bool $is) : void
    {
        $this->allowEmpty = $is;
    }
    /**
     * Indica se é ou não permitido atribuir ``''`` como um valor válido para este campo.
     *
     * @return      ?bool
     */
    public function isAllowEmpty() : ?bool
    {
        return $this->allowEmpty;
    }





    /**
     * Propriedade que define se, ao receber um valor ``''``, este deverá ser convertido
     * para ``null``.
     *
     * @var         bool
     */
    private bool $convertEmptyToNull = false;
    /**
     * Define se, ao receber um valor ``''``, este deverá ser convertido para ``null``.
     *
     * Por padrão este valor deve ser ``false``.
     *
     * @param       bool $is
     *              Valor a ser definido para esta propriedade.
     *
     * @return      void
     */
    private function setIsConvertEmptyToNull(bool $is) : void
    {
        $this->convertEmptyToNull = $is;
    }
    /**
     * Define se, ao receber um valor ``''``, este deverá ser convertido para ``null``.
     *
     * @return      bool
     */
    public function isConvertEmptyToNull() : bool
    {
        return $this->convertEmptyToNull;
    }










    /**
     * Propriedade que define se este campo é ou não ``readonly``.
     *
     * @var         bool
     */
    private bool $readOnly = false;
    /**
     * Indica se este campo é ``readonly``.
     * Quando ``true`` permitirá que o valor do campo seja atribuido, apenas 1 vez, e após,
     * tal valor não poderá mais ser alterado.
     *
     * Por padrão este valor deve ser ``false``.
     *
     * @param       bool $is
     *              Valor a ser definido para esta propriedade.
     *
     * @return      void
     */
    private function setIsReadOnly(bool $is) : void
    {
        $this->readOnly = $is;
    }
    /**
     * Indica se este campo é ou não ``readonly``.
     *
     * @return      bool
     */
    public function isReadOnly() : bool
    {
        return $this->readOnly;
    }










    /**
     * Guarda o nome do modelo de dados que este campo representa.
     *
     * Usado apenas em campos do tipo *reference*.
     *
     * @var         ?string
     */
    protected ?string $modelName = null;
    /**
     * Fábrica de modelo de dados a ser usada internamente para suprir modelos que apontam
     * para outros modelos de dados de forma dinâmica.
     *
     * Usado apenas em campos do tipo *reference*.
     *
     * @var         ?iModelFactory
     */
    protected ?iModelFactory $modelFactory = null;
    /**
     * Instância do modelo de dados que está sendo usado para validar os dados que podem ser
     * inseridos neste campo.
     *
     * Usado apenas em campos do tipo *reference*.
     *
     * @var         ?iModel
     */
    protected ?iModel $modelValidate = null;
    /**
     * Indica quando este campo é do tipo *reference*, ou seja, seu valor é um
     * modelo de dados.
     *
     * @return      bool
     */
    public function isReference() : bool
    {
        $ns = "AeonDigital\\Interfaces\\DataModel\\iFieldModel";
        return (\in_array($ns, \class_implements(\get_class($this))) === true);
    }





    /**
     * Indica quando trata-se de um campo capaz de conter uma coleção de valores.
     *
     * @return      bool
     */
    public function isCollection() : bool
    {
        $ns = "AeonDigital\\Interfaces\\DataModel\\iFieldCollection";
        return (\in_array($ns, \class_implements(\get_class($this))) === true);
    }



















    /**
     * Armazena o estado atual de validade do campo com relação a seu valor definido e
     * seus critérios de funcionamento.
     *
     * @var         bool
     */
    protected bool $fieldState_IsValid = true;
    /**
     * Informa se o campo tem no momento um valor que satisfaz os critérios de validação
     * para o mesmo.
     *
     * @return      bool
     */
    public function isValid() : bool
    {
        return $this->fieldState_IsValid;
    }


    /**
     * Armazena o registro da validação referente ao valor que está atualmente definido
     * para este campo.
     *
     * @var         string|array
     */
    protected $fieldState_CurrentState = "valid";
    /**
     * Retorna o código do estado atual deste campo.
     *
     * **Campos Simples**
     * Retornará ``valid`` caso o valor definido seja válido, ou o código da validação
     * que caracteríza a invalidez deste valor.
     *
     * **Campos "reference"**
     * Retornará ``valid`` se **TODOS** os campos estiverem com valores válidos. Caso
     * contrário retornará um ``array`` associativo contendo o estado de cada um dos campos
     * existêntes.
     *
     * **Campos "collection"**
     * Retornará ``valid`` caso **TODOS** os valores estejam de acordo com os critérios de
     * validação ou um ``array`` contendo a validação individual de cada ítem membro da
     * coleção.
     *
     * @return      string|array
     */
    public function getState()
    {
        return $this->fieldState_CurrentState;
    }


    /**
     * Mantêm os códigos referentes a última validação executada.
     *
     * Este valor é sobrescrito sempre que um método que exige validação for acionado,
     * portanto, sempre conterá o valor da última validação realizada.
     *
     * @var         string|array
     */
    protected $fieldState_ValidateState = "valid";
    /**
     * Retornará o resultado da validação conforme o tipo de campo testado.
     *
     * **Campos Simples**
     * Retornará ``valid`` caso o valor definido seja válido, ou o código da validação
     * que caracteríza a invalidez deste valor.
     *
     * **Campos "reference"**
     * Retornará ``valid`` se **TODOS** os campos estiverem com valores válidos. Caso
     * contrário retornará um ``array`` associativo contendo o estado de cada um dos campos
     * existêntes.
     *
     * **Campos "collection"**
     * Retornará ``valid`` caso **TODOS** os valores estejam de acordo com os critérios de
     * validação ou um ``array`` contendo a validação individual de cada ítem membro da
     * coleção.
     *
     * @return      string|array
     */
    public function getLastValidateState()
    {
        return $this->fieldState_ValidateState;
    }


    /**
     * Mantêm a última verificação ``canSet`` referente a última validação realizada para
     * este campo de dados.
     *
     * @var         bool
     */
    protected bool $fieldState_ValidateStateCanSet = true;
    /**
     * Retornará ``true`` caso a última validação realizada permitir que o valor testado
     * seja definido para este campo.
     *
     * **Campos Simples**
     * Valores inválidos podem ser definidos quando eles forem do mesmo ``type`` deste campo.
     *
     * **Campos "reference"**
     * Se **TODOS** os valores passados para um modelo de dados puderem ser assumidos por seus
     * respectivos campos, então tais dados poderão ser utilizados para preencher a instância.
     *
     * **Campos "collection"**
     * Se **TODOS** os valores membros para uma coleção de dados puderem ser setados,
     * independente de serem válidos, então, a coleção poderá assumir aquele grupo de dados.
     *
     * @return      bool
     */
    public function getLastValidateCanSet() : bool
    {
        return $this->fieldState_ValidateStateCanSet;
    }


    /**
     * Mantêm os códigos referentes a última validação executada para um campo do tipo
     * *collection*.
     *
     * Este valor é sobrescrito sempre que um método que exige validação for acionado,
     * portanto, sempre conterá o valor da última validação realizada.
     *
     * @var         string|string[]
     */
    protected $fieldState_CollectionValidateState = null;
    /**
     * Armazena o registro da validação referente à toda coleção de dados armazenada no
     * momento.
     *
     * É usado apenas em campos *collection* mas está aqui definido para simplificar o
     * controle de estados das instâncias independente de seus tipos concretos.
     *
     * @var         ?string
     */
    protected ?string $fieldState_CollectionState = null;


    /**
     * Verifica se o valor indicado satisfaz os critérios que permitem dizer que o valor
     * passado é válido.
     *
     * **Valores especiais e seus efeitos**
     *  ``undefined``
     *  Sempre falhará na validação.
     *
     *  ``null``
     *  Falhará se o campo não permitir este valor [ veja propriedade ``allowNull`` ].
     *
     *  ``''``
     *  Falhará se o campo não permitir este valor e estiver com a conversão de ``''`` em
     *  ``null`` desabilitada [ veja as propriedades ``allowEmpty`` e ``convertEmptyToNull`` ].
     *
     *  ``[]``
     *  Falhará SEMPRE para campos que não forem ``collection``.
     *
     *
     * **Validação dos Campos Simples**
     *  A validação é feita seguindo os seguintes passos:
     *
     *  1. Verifica se o campo está apto a receber um valor ou se ele é do tipo ``readOnly``.
     *  2. Verifica se o valor cai em algum dos valores especiais citados no tópico anterior.
     *  3. Verifica se o valor não é um objeto de um tipo não aceito.
     *    Os tipos aceitos para campos simples são:
     *    ``bool``, ``int``, ``float``, ``RealNumber``, ``DateTime``, ``string``
     *  4. Validação de tipo:
     *  4.1. Havendo um ``inputFormat`` definido, identifica se o valor passa em sua
     *    respectiva validação.
     *  4.2. Verifica se o valor passado é um representante válido do tipo base do campo.
     *  5. Verificação de adequação:
     *  5.1. Enumerador, se houver, verifica se o valor está entre os itens válidos.
     *  5.2. Sendo um campo ``string`` e existindo uma definição de tamanho máximo
     *   [ propriedade ``length`` ] verifica se o valor não excede seu limite.
     *  5.3. Sendo um campo numérico ou de data e existindo limites definidos para seus
     *   valores mínimos e máximos, identifica se o valor passado não excede algum destes
     *   limites.
     *
     * **Valores aceitáveis**
     * ``null``, ``bool``, ``int``, ``float``, ``RealNumber``, ``DateTime``, ``string``
     *
     *
     * **Regras de aceitação**
     *  No passo 4.1, caso falhe na validação de ``inputFormat`` mas tanto o valor passado
     *  quanto o próprio campo são do tipo ``string`` ocorrerá que a validação não impedirá
     *  que tal valor seja definido para este campo, mas ele ficará com o estado inválido.
     *
     *  Com excessão da regra especificada acima, falhas ocorridas até o passo 5 invalida
     *  totalmente o valor para poder ser definido como o valor do campo atual.
     *
     *  Falhas ocorridas no passo 5, apesar de falhar na validação, indica que o valor poderá
     *  passar a representar o valor atual do campo mas seu estado passará a ser "inválido".
     *
     *
     * **Validação de Campos "reference"**
     *  A validação é feita tentando usar o conjunto de valores passado para que ele preencha
     *  os campos de um modelo de dados do mesmo tipo que este campo está apto a representar.
     *  É preciso que **TODAS** as respectivas chaves de dados compatíveis com o modelo de
     *  dados representado pelo campo possam ser aceitos (independente de serem válidos) para
     *  que o objeto seja validado.
     *
     * **Valores aceitáveis**
     *  ``null``, ``iterable``, ``array``, ``iModel``
     *
     *
     * **Validação de Campos "collection"**
     *  A validação é feita submetendo cada um dos membros da coleção indicada a seu
     *  respectivo tipo de validação. Os dados serão utilizados pelo campo se todos os membros
     *  apresentados puderem ser definidos.
     *
     * **Valores aceitáveis**
     * ``null``, ``array``
     *
     *
     * @param       mixed $v
     *              Valor que será testado.
     *
     * @return      bool
     */
    public function validateValue($v) : bool
    {
        $iCV = $this->internal_CheckValue($v);

        $this->fieldState_ValidateState             = $iCV["state"];
        $this->fieldState_ValidateStateCanSet       = $iCV["canSet"];
        $this->fieldState_CollectionValidateState   = $iCV["cState"];

        return ($iCV["canSet"] === true && $iCV["valid"] === true);
    }




















    /**
     * Retorna um valor individual em seu formato de armazenamento.
     *
     * O formato de armazenamento é sempre aquele que preserva o ``type`` do valor e remove
     * quaisquer formatação definida.
     *
     * Este método não efetua a validação do valor.
     *
     * @param       mixed $v
     *              Valor que será convertido.
     *
     * @return      mixed
     */
    protected function individualValue_RetrieveInStorageFormat($v)
    {
        if ($v !== null && $v !== "") {
            if ($this->inputFormat !== null && \is_string($v) === true) {
                $v = $this->inputFormat["storageFormat"]($v);
            }

            // Mantém o tipo simples para strings que podem representar
            // outros formatos.
            if ($this->isSimpleTypeString() === true && \is_string($v) === false && $this->inputFormat !== null) {
                $ifDateMask = $this->inputFormat["name"] . "::DateMask";

                if (\defined($ifDateMask) === true) {
                    $v = $this->inputFormat["format"]($v, [$this->inputFormat["name"]::DateMask]);
                }
                else {
                    $v = $this->inputFormat["format"]($v);
                }
            } else {
                $v = $this->type::parseIfValidate($v);
            }
        }

        return $v;
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
    protected function individualValue_CheckValue($v) : array
    {
        $canSet = true;
        $state  = "valid";


        // Primeira validação é a de possibilidade de
        // redefinir o valor do campo.
        if ($this->isReadOnly() === true && $this->value !== undefined) {
            $canSet = false;
            $state  = "error.dm.field.value.constraint.read.only";
        }


        // Sendo possível redefinir o campo, prossegue com a validação
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
                if ($this->isAllowEmpty() === false) {
                    $canSet = false;
                    $state = "error.dm.field.value.not.allow.empty";

                    // Verifica casos onde "" será convertido em "null"
                    if ($this->isConvertEmptyToNull() === true && $this->isAllowNull() === true) {
                        $canSet = true;
                        $state = "valid";
                    }
                }
            } elseif (\is_array($v) === true) {
                $canSet = false;
                $state = "error.dm.field.value.not.allow.array";
            } else {

                // Verifica se o valor passado é de um tipo válido.
                $isValidType = (
                    \is_bool($v) === true ||
                    \is_int($v) === true ||
                    \is_float($v) === true ||
                    \is_string($v) === true ||
                    \is_a($v, "\DateTime") === true ||
                    \is_a($v, "AeonDigital\\Numbers\\RealNumber") === true);

                if ($isValidType === false) {
                    $canSet = false;
                    $state  = "error.dm.field.value.invalid.type";
                } else {
                    $sVal = $v;

                    // Havendo um "inputFormat" definido e o valor passado seja uma string
                    if ($this->inputFormat !== null && \is_string($v) === true) {
                        // Verifica se é um valor válido para o "inputFormat" definido,
                        if ($this->inputFormat["check"]($v) === false) {
                            // Se o campo está esperando um valor de um tipo que NÃO
                            // seja uma string, impede o valor de ser definido.
                            if ($this->isSimpleTypeString() === false) {
                                $canSet = false;
                            }
                            $state = "error.dm.field.value.invalid.input.format";
                        }
                        // Caso passe na validação do tipo, efetuará a conversão
                        // para o type original ou removerá completamente toda formatação
                        // usada até o momento.
                        else {
                            $sVal = $this->inputFormat["storageFormat"]($v);

                            // Se o campo está definido para armazenar strings mas o "inputType"
                            // permite representar outros tipos, reverte a conversão do valor para
                            // o tipo nativo deste campo.
                            if ($this->isSimpleTypeString() === true && \is_string($sVal) === false) {
                                $ifDateMask = $this->inputFormat["name"] . "::DateMask";

                                if (\defined($ifDateMask) === true) {
                                    $sVal = $this->inputFormat["format"]($sVal, [$this->inputFormat["name"]::DateMask]);
                                }
                                else {
                                    $sVal = $this->inputFormat["format"]($sVal);
                                }
                            }
                        }
                    } else {
                        // Força o valor passado para que ele retorne ao
                        // tipo original.
                        $stErr = null;
                        $sVal = $this->type::parseIfValidate($v, $stErr);

                        // Caso não tenha sido possível retomar o tipo original
                        // do valor...
                        if ($stErr !== null) {
                            $canSet = false;
                            $state  = $stErr;
                        }
                    }



                    // Chegando aqui e ainda podendo definir o campo significa
                    // que é possível reverter o valor passado para seu tipo original
                    // ou que ele já é um objeto do tipo esperado por "type".
                    // Em todo caso é esperado um valor totalmente "limpo" de qualquer
                    // formatação.
                    if ($canSet === true) {

                        // Havendo um enumerador definido, testa o valor conforme
                        // a coleção válida.
                        if ($this->enumerator !== null) {
                            $r = false;

                            foreach ($this->enumerator as $enum) {
                                $eVal = ((\is_array($enum) === true) ? $enum[0] : $enum);
                                if ($sVal === $eVal) { $r = true; }
                            }

                            if ($r === false) {
                                $state = "error.dm.field.value.constraint.enumerator.violated";
                            }
                        } else {

                            // Se tratar-se de uma string, verifica se a mesma
                            // extrapolou o limite de tamanho definido.
                            if ($this->isSimpleTypeString() === true && $this->getLength() !== null && \mb_strlen($sVal) > $this->getLength()) {
                                $state = "error.dm.field.value.constraint.length.violated";
                            }
                            // Tratando-se de um tipo numérico, ou de uma data
                            elseif ($this->isSimpleTypeNumeric() === true || $this->isSimpleTypeDateTime() === true) {
                                $min = $this->getMin();
                                $max = $this->getMax();

                                // Para casos de números reais "RealNumber"
                                if ($this->isSimpleTypeReal() === true) {
                                    if (($min !== null && $sVal->isLessThan($min) === true) ||
                                        ($max !== null && $sVal->isGreaterThan($max) === true)) {
                                        $state = "error.dm.field.value.constraint.range.violated";
                                    }
                                } else {
                                    if (($min !== null && $sVal < $min) ||
                                        ($max !== null && $sVal > $max)) {
                                        $state = "error.dm.field.value.constraint.range.violated";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        return [
            "canSet"    => $canSet,
            "valid"     => ($state === "valid"),
            "state"     => $state,
            "cState"    => null
        ];
    }
    /**
     * Processa o valor passado e retorna um ``array`` contendo as informações
     * necessárias para o *SET* deste valor neste campo.
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
    protected function individualValue_ProccessSet($v) : array
    {
        $ivCV = $this->individualValue_CheckValue($v);

        if ($ivCV["canSet"] === false) {
            $ivCV["rawValue"]   = undefined;
            $ivCV["value"]      = undefined;
        } else {
            $useVal = $v;

            // Verifica necessidade de converter "" para "null".
            if ($v === "" || $v === null) {
                if ($this->isConvertEmptyToNull() === true) { $useVal = null; }
            } else {
                $useVal = $this->individualValue_RetrieveInStorageFormat($useVal);
                if ($useVal === null && $v !== null) {
                    $useVal = $v;
                }
            }

            $ivCV["rawValue"]   = $v;
            $ivCV["value"]      = $useVal;
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
    protected function individualValue_ProccessGet($val, bool $formated = false)
    {
        $r = (($val === undefined) ? $this->getDefault() : $val);

        if ($r !== undefined &&
            $r !== null &&
            $formated === true &&
            $this->inputFormat !== null)
        {
            $useR = (
                ($this->isSimpleTypeDateTime() === false) ?
                $this->inputFormat["format"]($r) :
                $this->inputFormat["format"]($r, ["Y-m-d H:i:s"])
            );

            if ($useR !== null) {
                $r = $useR;
            }
        }

        return $r;
    }










    /**
     * Método de interface geral para ``RetrieveInStorageFormat``.
     *
     * Deve ser substituído dentro de cada classe especialista de forma a apontar para o
     * devido processo compatível com os critérios definidos.
     *
     * @param       mixed $v
     *              Valor que será convertido.
     *
     * @return      mixed
     */
    protected function internal_RetrieveInStorageFormat($v)
    {
        return $this->individualValue_RetrieveInStorageFormat($v);
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
        return $this->individualValue_CheckValue($v);
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
        return $this->individualValue_ProccessSet($v);
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
        return $this->individualValue_ProccessGet($val, $formated);
    }




















    /**
     * Valor padrão que este campo deve ter caso nenhum outro seja definido.
     *
     * @var         mixed
     */
    protected $default = undefined;
    /**
     * Define o valor padrão que este campo deve ter caso nenhum outro seja definido.
     *
     * @param       mixed $v
     *              Valor a ser definido para esta propriedade.
     *
     * @return      void
     *
     * @throws      \InvalidArgumentException
     *              Caso o argumento passado não seja válido.
     */
    protected function setDefault($v) : void
    {
        if ($this->isValidNowInstruction($v) === true) {
            $this->default = "NOW()";
        } else {
            $iCV = $this->internal_CheckValue($v);

            if ($iCV["valid"] === false) {
                $msg = "The given \"default\" value is invalid.";
                throw new \InvalidArgumentException($msg);
            } else {
                $this->default = $this->internal_RetrieveInStorageFormat($v);
            }
        }
    }
    /**
     * Retorna o valor padrão que este campo deve ter caso nenhum outro seja definido.
     * Se ``default`` não for definido, ``undefined`` será retornado.
     *
     * @param       bool $getInstruction
     *              Quando ``true``, retorna o nome da instrução especial que define o
     *              valor padrão.
     *
     * @return      mixed
     */
    public function getDefault(bool $getInstruction = false)
    {
        if ($this->default === "NOW()" && $getInstruction === false) {
            return new \DateTime();
        } else {
            return $this->default;
        }
    }





    /**
     * Coleção de valores que este campo está apto a assumir.
     *
     * @var         ?array
     */
    private ?array $enumerator = null;
    /**
     * Define a coleção de valores que este campo está apto a assumir.
     *
     * O ``array`` pode ser unidimensional ou multidimensional, no caso de ser
     * multidimensional, cada entrada deverá ser um novo ``array`` com 2 posições onde a
     * primeira será o valor real do campo e o segundo, um ``label`` para o mesmo.
     *
     * Os valores aqui pré-definidos devem seguir as mesmas regras de validação para o campo.
     *
     * ``` php
     *      // Exemplo de definição
     *      $arr = [
     *          ["RS", "Rio Grande do Sul"],
     *          ["SC", "Santa Catarina"],
     *          ["PR", "Paraná"]
     *      ];
     * ```
     *
     * @param       string|array $enum
     *              Array a ser definido como enumerador.
     *
     * @return      void
     *
     * @throws      \InvalidArgumentException
     *              Caso o argumento passado não seja válido.
     */
    private function setEnumerator($enum) : void
    {
        if (\is_string($enum) === true) {
            if (\file_exists($enum) === false) {
                $msg = "The target enumerator file description does not exist.";
                throw new \InvalidArgumentException($msg);
            } else {
                $enum = include $enum;

                if (\is_array($enum) === false) {
                    $msg = "The target enumerator file does not have a valid array.";
                    throw new \InvalidArgumentException($msg);
                }
            }
        }



        if (\count($enum) === 0) {
            $msg = "Invalid enumerator value. The given array is empty.";
            throw new \InvalidArgumentException($msg);
        } else {
            if (\array_is_assoc($enum) === true) {
                $msg = "Invalid enumerator value. Can not be an assoc array.";
                throw new \InvalidArgumentException($msg);
            } else {
                $rEnum = [];

                foreach ($enum as $val) {
                    $v = $val;

                    if (\is_array($v) === true) {
                        if (\count($v) !== 2) {
                            $msg = "Invalid enumerator value. Multidimensional arrays must have 2 values defined.";
                            throw new \InvalidArgumentException($msg);
                        } else {
                            $v = $val[0];
                        }
                    }


                    $ivCV = $this->individualValue_CheckValue($v);
                    if ($ivCV["valid"] === false) {
                        $msg = "Invalid enumerator value.";
                        throw new \InvalidArgumentException($msg);
                    } else {
                        $v = $this->individualValue_RetrieveInStorageFormat($v);

                        if (\is_array($val) === true) {
                            $rEnum[] = [$v, $val[1]];
                        } else {
                            $rEnum[] = $v;
                        }
                    }
                }

                $this->enumerator = $rEnum;
            }
        }
    }
    /**
     * Retorna um ``array`` com a coleção de valores que este campo está apto a assumir.
     * Os valores aqui pré-definidos devem seguir as mesmas regras de validade especificadas
     * nas demais propriedades.
     *
     * @param       bool $getOnlyValues
     *              Quando ``true``, retorna um array unidimensional contendo apenas os
     *              valores válidos de serem selecionados sem seus respectivos ``labels``.
     *
     * @return      ?array
     */
    public function getEnumerator(bool $getOnlyValues = false) : ?array
    {
        $enum = $this->enumerator;

        if ($enum !== null) {
            if (\is_array($this->enumerator[0]) === true && $getOnlyValues === true) {
                $enum = [];
                foreach ($this->enumerator as $i => $v) {
                    $enum[] = $v[0];
                }
            }
        }

        return $enum;
    }










    /**
     * Valor atual do campo.
     *
     * @var         mixed
     */
    protected $value = undefined;
    /**
     * Valor que foi passado pelo método ``setValue`` armazenado tal qual sem qualquer
     * alteração.
     *
     * @var         mixed
     */
    protected $rawValue = undefined;
    /**
     * Define um novo valor para este campo.
     *
     * O valor passado será validado e será definido caso seu valor seja condizente com as
     * regras de aplicação especificadas na descrição do método ``validateValue()``.
     *
     *
     * Define um novo valor para este campo.
     *
     * **undefined**
     * Este valor **NUNCA** será aceito por nenhum tipo de campo e em qualquer circunstância.
     *
     *
     * **Campos Simples**
     * Para que o campo assuma o novo valor ele precisa ser compatível com o ``type`` definido.
     * Caso contrário o campo ficará com o valor ``null``.
     *
     * **Valores aceitáveis**
     * ``null``, ``bool``, ``int``, ``float``, ``RealNumber``, ``DateTime``, ``string``
     *
     *
     * **Campos "reference"**
     * Campos deste tipo apenas aceitarão valores capazes de preencher os campos do modelo
     * de dados ao qual eles se referenciam. Independente de tornar o modelo de dados válido
     * ou não, os valores serão definidos exceto se o valor passado for incompatível com o
     * modelo de dados configurado.
     *
     * **Valores aceitáveis**
     * ``null``, ``iterable``, ``array``, ``iModel``
     *
     *
     * **Campos "collection"**
     * Uma coleção de dados sempre será definida como o valor de um campo que aceite este
     * tipo de valor.
     * Os membros da coleção serão convertidos para o tipo ``type`` definido. Membros que
     * não possam ser convertidos serão substituidos por ``null`` e a coleção será inválida
     * até que estes membros sejam removidos ou substituídos.
     *
     * Coleções do tipo *reference* apenas serão redefinidos se **TODOS** seus itens forem
     * capazes de tornarem-se objetos ``iModel`` do tipo definido para este campo.
     *
     * **Valores aceitáveis**
     * ``null``, ``array``
     *
     *
     * **Estado e validação**
     * Independente de o valor vir a ser efetivamente definido para o campo o estado da
     * validação pode ser verificado usando ``getLastValidateState()``.
     *
     * Uma vez que o valor seja definido, o campo passa a assumir o estado herdado da
     * validação e poderá ser verificado em ``getState()``.
     *
     *
     * @param       mixed $v
     *              Valor a ser definido para o campo.
     *
     * @return      bool
     *              Retornará ``true`` se o valor tornou o campo válido ou ``false`` caso
     *              agora ele esteja inválido. Também retornará ``false`` caso o valor seja
     *              totalmente incompatível com o campo.
     */
    public function setValue($v) : bool
    {
        $iPS = $this->internal_ProccessSet($v);

        if ($iPS["canSet"] === true) {
            $this->value                        = $iPS["value"];
            $this->rawValue                     = $iPS["rawValue"];
            $this->fieldState_IsValid           = $iPS["valid"];
            $this->fieldState_CurrentState      = $iPS["state"];
            $this->fieldState_CollectionState   = $iPS["cState"];
        }
        $this->fieldState_ValidateState             = $iPS["state"];
        $this->fieldState_ValidateStateCanSet       = $iPS["canSet"];
        $this->fieldState_CollectionValidateState   = $iPS["cState"];

        return ($iPS["canSet"] === true && $iPS["valid"] === true);
    }
    /**
     * Retorna o valor atual deste campo.
     *
     * **undefined**
     * Este valor será retornado **ENQUANTO** o campo **AINDA** não foi redefinido com qualquer
     * outro valor. Esta regra se aplica para campos simples e *reference*.
     *
     *
     * **Campos Simples**
     * O valor retornado estará sempre no mesmo ``type`` que aquele que o campo está
     * configurado para assumir. Havendo alguma formatação indicada em ``inputFormat``, esta
     * será usada sobrepondo-se ao ``type``.
     *
     *
     * **Campos "reference"**
     * Estes campos apenas são capazes de retornar valores ``undefined``, ``null`` ou um ``array``
     * associativo representando o respectivo modelo de dados que ele está configurado para
     * receber.
     *
     *
     * **Campos "collection"**
     * O valor retornado será **SEMPRE** um ``array`` contendo os itens atualmente definidos.
     * Estes itens serão retornados conforme as regras definidas acima para *campos simples*.
     *
     * Coleções do tipo *reference* apenas retornarão um ``array`` de arrays associativos
     * representando a coleção de modelos de dados que o campo está apto a utilizar.
     *
     * Um *collection* em seu estado inicial retornará sempre um ``array`` vazio.
     *
     * @return      mixed
     */
    public function getValue()
    {
        return $this->internal_ProccessGet($this->value, true);
    }
    /**
     * Retorna o valor atual deste campo em seu formato de armazenamento.
     *
     * **undefined**
     * O valor ``null`` será retornado no lugar de ``undefined`` para campos simples e
     * *reference*.
     *
     *
     * **Campos Simples**
     * O valor retornado estará sempre no mesmo ``type`` que aquele que o campo está
     * configurado para assumir. Qualquer regra para **REMOÇÃO** de formatação será aplicada
     * caso exista.
     *
     *
     * **Campos "reference"**
     * Estes campos apenas são capazes de retornar valores ``null`` ou arrays associativos
     * representando o respectivo modelo de dados que ele está configurado para receber.
     *
     *
     * **Campos "collection"**
     * O valor retornado será **SEMPRE** um ``array`` contendo os itens atualmente definidos.
     * Estes itens serão retornados conforme as regras definidas acima para *campos simples*.
     *
     * Coleções do tipo *reference* apenas retornarão um ``array`` de arrays associativos
     * representando a coleção de modelos de dados que o campo está apto a utilizar.
     *
     * Campos do tipo *collection* em seu estado inicial retornarsão sempre um ``array`` vazio.
     * Coleções que possuam valores inválidos entre seus membros também retornarão um ``array``
     * vazio.
     *
     * @return      mixed
     */
    public function getStorageValue()
    {
        if ($this->isValid() === true && ($this->value !== undefined || $this->default !== undefined)) {
            return $this->internal_ProccessGet($this->value, false);
        } else {
            if ($this->isCollection() === true) {
                return [];
            } else {
                return null;
            }
        }
    }
    /**
     * Retorna o valor que está definido para este campo assim como ele foi passado em
     * ``setValue()``.
     *
     * @return      mixed
     */
    public function getRawValue()
    {
        return $this->rawValue;
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
     *          // string           Nome completo de uma classe que implemente a interface "iSimpleType".
     *          //                  OU "ref" para identificar que este campo referencia-se a um outro modelo
     *          //                  de dados.
     *          "type" => ,
     *
     *          // string           Nome completo de uma classe que implemente a interface "iFormat". (opcional)
     *          "inputFormat" => ,
     *
     *          // int              Tamanho máximo do campo em caracteres. (opcional)
     *          //                  Se não for definido explicitamente poderá herdar das informações
     *          //                  indicadas em "inputFormat".
     *          "length" => ,
     *
     *          // mixed            Valor mínimo aceito para este campo. (opcional)
     *          //                  Use apenas para casos de campos numéricos ou data/hora.
     *          "min" => ,
     *
     *          // mixed            Valor máximo aceito para este campo. (opcional)
     *          //                  Use apenas para casos de campos numéricos ou data/hora.
     *          "max" => ,
     *
     *          // bool             Indica se "null" é um valor aceito para este campo. (opcional)
     *          "allowNull" => ,
     *
     *          // bool             Indica se "" é um valor aceito para este campo. (opcional)
     *          "allowEmpty" => ,
     *
     *          // bool             Indica se, ao receber um valor "", este deve ser convertido para "null". (opcional)
     *          "convertEmptyToNull" => ,
     *
     *          // bool             Indica se o campo é apenas de leitura.
     *          //                  Neste caso ele poderá ser definido apenas 1 vez e após
     *          //                  isto seu valor não poderá ser alterado. (opcional)
     *          "readOnly" => ,
     *
     *          // mixed            Valor padrão para este campo. (opcional)
     *          "default" => ,
     *
     *          // array|string     Coleção de valores válidos para este campo. (opcional)
     *          //                  Se for definido uma string, deve ser o caminho completo até um arquivo php
     *          //                  que contêm o array a ser utilizado como enumerador.
     *          "enumerator" => ,
     *
     *          // mixed            Valor que inicia com o campo.
     *          "value" => ,
     *      ];
     * ```
     *
     * @param       array $config
     *              ``array`` associativo com as configurações para este campo.
     *
     * @throws      \InvalidArgumentException
     *              Caso algum valor passado não seja válido.
     */
    function __construct(array $config)
    {
        // Resgata as propriedades definidas
        $name               = ((isset($config["name"]))                 ? $config["name"]               : "");
        $description        = ((isset($config["description"]))          ? $config["description"]        : "");
        $type               = ((isset($config["type"]))                 ? $config["type"]               : "");
        $inputFormat        = ((isset($config["inputFormat"]))          ? $config["inputFormat"]        : null);

        $length             = ((isset($config["length"]))               ? $config["length"]             : null);
        $min                = ((isset($config["min"]))                  ? $config["min"]                : null);
        $max                = ((isset($config["max"]))                  ? $config["max"]                : null);

        $allowNull          = ((isset($config["allowNull"]))            ? $config["allowNull"]          : true);
        $allowEmpty         = ((isset($config["allowEmpty"]))           ? $config["allowEmpty"]         : true);
        $convertEmptyToNull = ((isset($config["convertEmptyToNull"]))   ? $config["convertEmptyToNull"] : false);

        $readOnly           = ((isset($config["readOnly"]))             ? $config["readOnly"]           : false);

        $default            = ((isset($config["default"]))              ? $config["default"]            : undefined);
        $enumerator         = ((isset($config["enumerator"]))           ? $config["enumerator"]         : undefined);
        $value              = ((isset($config["value"]))                ? $config["value"]              : undefined);



        // Seta propriedades definidas
        $this->setName($name);
        $this->setDescription($description);
        $this->setType($type);
        $this->setInputFormat($inputFormat);
        if ($this->inputFormat !== null && $this->inputFormat["length"] !== null) {
            $length = $this->inputFormat["length"];
        }

        $this->setLength($length);
        $this->setMin($min);
        $this->setMax($max);

        $this->setIsAllowNull($allowNull);
        $this->setIsAllowEmpty($allowEmpty);
        $this->setIsConvertEmptyToNull($convertEmptyToNull);

        $this->setIsReadOnly($readOnly);

        if ($default !== undefined) {
            $this->setDefault($default);
        }
        if ($enumerator !== undefined) {
            $this->setEnumerator($enumerator);
        }
        if ($value !== undefined) {
            $this->setValue($value);
        } else {
            // Verifica o estado inicial deste campo
            $d = $this->getDefault();
            if ($d === undefined && $allowNull === true) {
                $this->fieldState_IsValid       = true;
                $this->fieldState_CurrentState  = "valid";
            } else {
                $this->fieldState_IsValid       = $this->validateValue($d);
                $this->fieldState_CurrentState  = $this->getLastValidateState();
            }
        }
    }
}
