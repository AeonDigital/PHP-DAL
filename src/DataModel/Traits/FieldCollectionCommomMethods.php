<?php
declare (strict_types=1);

namespace AeonDigital\DataModel\Traits;

use AeonDigital\Tools as Tools;








/**
 * Métodos e propriedades comuns para uso de classes que implementam ``iFieldCollection``.
 *
 * @package     AeonDigital\DataModel
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2019, Rianna Cantarelli
 * @license     ADPL-v1.0
 */
trait FieldCollectionCommomMethods
{





    /**
     * Retorna o código de estado de uma coleção de dados.
     *
     * @return      string
     */
    public function collectionGetState() : string
    {
        return $this->fieldState_CollectionState;
    }
    /**
     * Retornará ``valid`` caso a última validação de uma coleção tenha ocorrido sem falhas.
     * Caso a validação tenha falhado, retornará o código que identifica a natureza do erro.
     *
     * @return      string
     */
    public function collectionGetLastValidateState() : string
    {
        return $this->fieldState_CollectionValidateState;
    }





    /**
     * Propriedade que define se esta coleção exige que cada um de seus valores seja único.
     *
     * @var         bool
     */
    private bool $collectionDistinct = false;
    /**
     * Define se esta coleção exige que cada um de seus valores seja único.
     *
     * Por padrão este valor deve ser ``false``.
     *
     * @param       bool $is
     *              Valor a ser definido para esta propriedade.
     *
     * @return      void
     */
    private function setCollectionIsDistinct(bool $is) : void
    {
        $this->collectionDistinct = $is;
    }
    /**
     * Indica se esta coleção exige que cada um de seus valores seja único.
     *
     * @return      bool
     */
    public function collectionIsDistinct() : bool
    {
        return $this->collectionDistinct;
    }





    /**
     * ``array`` com a coleção de chaves usadas para distinguir um modelo de dados de outro.
     *
     * Usado apenas para casos de coleções de modelos de dados ``iModel``.
     *
     * @var         ?array
     */
    private ?array $collectionDistinctKeys = null;
    /**
     * Define a coleção de nomes de campos (chaves) que permitem  avaliar quando uma coleção
     * de modelos de dados possui objetos iguais.
     *
     * Usado apenas para casos de coleções de modelos de dados ``iModel``.
     *
     * @param       ?array $keys
     *              Coleção de chaves usadas para distinguir as instâncias de modelos de dados.
     *
     * @return      void
     */
    private function setCollectionGetDistinctKeys(?array $keys) : void
    {
        $this->collectionDistinctKeys = $keys;
    }
    /**
     * Retorna a coleção de nomes de campos (chaves) que permitem avaliar quando uma coleção
     * de modelos de dados possui objetos iguais.
     *
     * Usado apenas para casos de coleções de modelos de dados ``iModel``.
     *
     * Se nenhuma coleção for definida para ``distinctKeys`` então deverá usar TODOS os
     * campos do modelo de dados para efetuar a comparação.
     *
     * @return      ?array
     */
    public function collectionGetDistinctKeys() : ?array
    {
        if ($this->collectionDistinctKeys === null &&
            $this->isReference() === true)
        {
            if ($this->modelValidate === null) {
                $this->modelValidate = $this->modelFactory->createDataModel($this->modelName);
            }
            $this->collectionDistinctKeys = $this->modelValidate->getFieldNames();
        }
        return $this->collectionDistinctKeys;
    }





    /**
     * Adiciona um novo valor para esta coleção.
     *
     * Para a aceitação do valor serão seguidas as mesmas regras especificadas para campos
     * simples e *reference*.
     *
     * @param       mixed $v
     *              Valor a ser adicionado na coleção.
     *
     * @return      bool
     *              Retornará ``true`` se o valor tornou o campo válido ou ``false`` caso
     *              agora ele esteja inválido. Também retornará ``false`` caso o valor seja
     *              totalmente incompatível com o campo.
     */
    public function collectionAddValue($v) : bool
    {
        return $this->setValue(\array_merge($this->rawValue, [$v]));
    }





    /**
     * Procura pelo valor indicado na coleção atualmente armazenada e retorna o índice do mesmo.
     * Valores que não estão aptos a serem armazenados neste campo irão sempre retornar ``null``.
     *
     * Havendo mais de 1 valor igual na coleção, retornará o índice da primeira ocorrência
     * encontrada.
     *
     * @param       mixed $v
     *              Valor que será verificado.
     *
     * @return      ?int
     */
    public function collectionGetIndexOfValue($v) : ?int
    {
        $r = null;
        $useCompare = $this->individualValue_RetrieveInStorageFormat($v);

        if ($useCompare !== null) {

            $sType = $this->identifySimpleType();
            if ($sType === "reference") {
                $useCompare = $this->createStringToCompareReferences($useCompare);
            } elseif ($sType === "Real") {
                $useCompare = (string)$useCompare;
            } elseif ($sType === "DateTime") {
                $useCompare = $useCompare->format("Y-m-d H:i:s");
            }


            foreach ($this->value as $i => $vv) {
                if ($r === null) {
                    $vvCompare = $vv;

                    if ($sType === "reference") {
                        $vvCompare = $this->createStringToCompareReferences($vv);
                    } elseif ($sType === "Real") {
                        $vvCompare = (string)$vv;
                    } elseif ($sType === "DateTime") {
                        $vvCompare = $vv->format("Y-m-d H:i:s");
                    }

                    if ($useCompare === $vvCompare) { $r = $i; }
                }
            }
        }
        return $r;
    }





    /**
     * Retorna a contagem de ocorrências do valor passado na coleção atualmente armazenada.
     *
     * @param       mixed $v
     *              Valor que será verificado.
     *
     * @return      int
     */
    public function collectionCountOccurrenciesOfValue($v) : int
    {
        $r = 0;
        $fV = $this->individualValue_RetrieveInStorageFormat($v);
        foreach ($this->value as $vv) {
            if ($fV === $vv) { $r++; }
        }
        return $r;
    }





    /**
     * Verifica se o valor informado existe na coleção de valores atuais deste campo.
     *
     * @param       mixed $v
     *              Valor que será verificado.
     *
     * @return      bool
     */
    public function collectionHasValue($v) : bool
    {
        return ($this->collectionGetIndexOfValue($v) !== null);
    }





    /**
     * Retorna a quantidade de valores que estão atualmente definidos na coleção do campo.
     *
     * @return      int
     */
    public function collectionCount() : int
    {
        return \count($this->value);
    }





    /**
     * Removerá da coleção de valores a primeira ocorrência do valor informado.
     *
     * @param       mixed $v
     *              Valor que será removido.
     *
     * @param       bool $all
     *              Quando ``true`` irá remover TODAS as ocorrências do valor indicado.
     *
     * @return      void
     */
    public function collectionUnsetValue($v, bool $all = false) : void
    {
        $i = $this->collectionGetIndexOfValue($v);
        if ($i !== null) {
            \array_splice($this->value, $i, 1);
            \array_splice($this->rawValue, $i, 1);

            if ($all === true) {
                $i = $this->collectionGetIndexOfValue($v);
                while ($i !== null) {
                    \array_splice($this->value, $i, 1);
                    \array_splice($this->rawValue, $i, 1);
                    $i = $this->collectionGetIndexOfValue($v);
                }
            }

            // Redefine a nova coleção e seu respectivo estado.
            $this->setValue($this->rawValue);
        }
    }





    /**
     * Removerá da coleção de valores o item na posição indicada.
     *
     * @param       int $i
     *              Índice que será removido.
     *
     * @return      void
     */
    public function collectionUnsetIndex(int $i) : void
    {
        if ($i >= 0 && isset($this->value[$i]) === true)
        {
            \array_splice($this->value, $i, 1);
            \array_splice($this->rawValue, $i, 1);

            // Redefine a nova coleção e seu respectivo estado.
            $this->setValue($this->rawValue);
        }
    }










    /**
     * Regras de aceitação para a contagem dos itens de uma coleção de dados.
     *
     * @var         ?array
     */
    private ?array $acceptedCount = null;
    /**
     * Se definido, permite informar uma composição de regras que especificam as contagens
     * de valores que serão válidas para a coleção de itens armazenados neste campo.
     *
     * As seguintes variáveis podem ser definidas:
     * - Número exato de de valores que torna a coleção válida.
     * - Indicação de um número múltiplo que, se observado, torna a coleção válida.
     * - Número mínimo de valores para a coleção.
     * - Número máximo de valores para a coleção.
     *
     * As regras podem ser definidas em conjunto e em qualquer ordem bastando seguir as
     * orientações:
     * - Números Exatos [podem ser definidos vários].
     *   Números inteiros separados por ``|``.
     *
     * - Número de Múltiplo [podem ser definidos vários].
     *   Números inteiros, precedidos por ``*``.
     *   Como regra especial, usando ``*0`` aceitará apenas números PARES e
     *   declarando ``*1`` aceitará apenas números ÍMPARES.
     *
     * - Número mínimo e máximo [apenas 1 pode ser definido].
     *   Par de números inteiros, sempre separados por ``,``.
     *   O primeiro é sempre o número mínimo a ser aceito e o segundo o número
     *   máximo de componentes que a coleção pode atinjir.
     *
     * **Exemplo**
     *  Regra: ``2``
     *  Indica que a coleção será válida somente se houverem exatos 2 itens na coleção.
     *
     *  Regra: ``2|5``
     *  Neste caso a coleção será válida somente se houverem exatos 2 ou 5 itens.
     *
     *  Regra: ``*3``
     *  Para este caso, a coleção será válida apenas se a quantidade total dos itens
     *  for um múltiplo de 3. Sendo ``0`` um valor aceito também pois não foi exigido
     *  um número mínimo de itens.
     *
     *  Regra: ``2,10``
     *  Com esta regra a coleção deverá ter ao menos 2 itens e no máximo 10 para ser
     *  considerada válida.
     *
     *  Regra: ``*3|3,12``
     *  Com estas 2 regras definidas juntamente a coleção será validada apenas se contiver
     *  3, 6, 9 ou 12 itens pois exige ao menos 3 itens e no máximo 12 sendo que
     *  necessariamente precisa ser um múltiplo de 3.
     *
     *  Regra: ``2|3|5|*3|*5|0,20``
     *  Com esta regra será considerado válido um conjunto que tenha exatos 2, 3 ou 5
     *  itens ou uma quantidade múltipla de 3 ou 5 dentro do limite de até 20 itens.
     *  Também é aceito um conjunto vazio, sem item algum.
     *
     *
     * @param       ?string $rules
     *              Regras que serão definidas conforme especificações abaixo.
     *
     * @throws      \InvalidArgumentException
     *              Caso algum valor passado não seja válido.
     */
    protected function collectionSetAcceptedCount(?string $rules)
    {
        if ($rules !== null && $rules !== "") {
            $acceptedCount = null;

            $rules = \explode("|", $rules);
            $exactValues    = [];
            $multiples      = [];
            $min            = 0;
            $max            = null;

            foreach ($rules as $rule) {
                $r = \trim($rule);
                $valid = false;

                // Se especifica um número exato de valores
                // que devem ser aceitos...
                if (\is_numeric($r) === true) {
                    $exactValues[] = (int)$r;
                    $valid = true;
                }
                // Se especifica um número múltiplo a
                // ser levado em consideração para as quantidades
                // de itens que podem ser adicionados na coleção.
                elseif (\strpos($r, "*") === 0) {
                    $multiples[] = ((int)(\str_replace("*", "", $r)));
                    $valid = true;
                }
                // Se especifica uma quantidade mínima e uma máxima
                // de itens que podem estar presentes na coleção de
                // valores..
                elseif (\strpos($r, ",") !== false) {
                    $mm = \explode(",", $r);
                    if (\count($mm) === 2 && $max === null &&
                        \is_numeric($mm[0]) === true && \is_numeric($mm[1]) === true)
                    {
                        $min = (int)$mm[0];
                        $max = (int)$mm[1];

                        if ($min <= $max) {
                            $valid = true;
                        }
                    }
                }


                if ($valid === false) {
                    $msg = "Invalid \"acceptedCount\".";
                    throw new \InvalidArgumentException($msg);
                }
            }


            \sort($exactValues);
            \sort($multiples);

            $acceptedCount = [
                "exactValues"   => $exactValues,
                "multiples"     => $multiples,
                "min"           => $min,
                "max"           => $max
            ];

            $this->acceptedCount = $acceptedCount;
        }
    }
    /**
     * Resgata as regras de aceitação para a contagem de itens em uma coleção de dados.
     *
     * O retorno deve ser um ``array`` associativo seguindo as seguintes orientações:
     *
     * ``` php
     *      $arr = [
     *          // int      Coleção de valores exatos que podem ser encontrados na contagem dos itens em uma coleção.
     *          "exactValues" => 0,
     *
     *          // int[]    Coleção que indica os múltiplos que a coleção pode possuir.
     *          "multiples" => [],
     *
     *          // int      Número mínimo de itens que a coleção deve ter.
     *          "min" => 0,
     *
     *          // int      Número máximo de itens que a coleção deve ter.
     *          "max" => 0
     *      ];
     * ```
     *
     * @return      ?array
     */
    public function collectionGetAcceptedCount() : ?array
    {
        return $this->acceptedCount;
    }





    /**
     * Retornará o número mínimo de itens que esta coleção pode possuir para ser considerada
     * válida.
     *
     * @return      ?int
     */
    public function collectionGetMin() : ?int
    {
        $r = null;
        if ($this->acceptedCount !== null) {
            $r = $this->acceptedCount["min"];
        }
        return $r;
    }





    /**
     * Retornará o número máximo de itens que esta coleção pode possuir para ser considerada
     * válida.
     *
     * @return      ?int
     */
    public function collectionGetMax() : ?int
    {
        $r = null;
        if ($this->acceptedCount !== null) {
            $r = $this->acceptedCount["max"];
        }
        return $r;
    }




















    /**
     * Prepara uma ``string`` que permite comparar valores *reference*.
     * A ``string`` é baseada nas chaves informadas em ``distinctKeys``.
     *
     * @param       mixed $v
     *              Objeto que será convertido em uma string para comparação.
     *
     * @return      string
     */
    private function createStringToCompareReferences($v) : string
    {
        $str = "";
        $distinctKeys = $this->collectionGetDistinctKeys();

        if (\is_array($v) === false && \is_iterable($v) === true) {
            foreach ($v as $key => $value) {
                if (\in_array($key, $distinctKeys) === true) {
                    $str .= Tools::toString($value);
                }
            }
        } else {
            foreach ($distinctKeys as $k) {
                if (isset($v[$k]) === true) {
                    $str .= Tools::toString($v[$k]);
                }
            }
        }

        return $str;
    }
    /**
     * Verifica se na coleção passada há itens iguais.
     *
     * Em coleções de modelos de dados será levado em conta as definições passadas
     * em ``distinctKeys``.
     *
     * @param       mixed $v
     *              Coleção que será verificada.
     *
     * @return      string
     */
    private function collectionCheckDistinct($v) : string
    {
        $r = "valid";

        if ($this->isReference() === true) {
            $strArr = [];

            foreach ($v as $i => $vv) {
                $strArr[] = $this->createStringToCompareReferences($vv);
            }

            // Se há  uma única chave para distinção dos campos
            // elimina objetos que são considerados inválidos para fins de comparação.
            if (\count($this->collectionGetDistinctKeys()) === 1) {
                $compareV = [];
                $compareS = [];

                foreach ($v as $i => $vv) {
                    if ($strArr[$i] !== undefined && $strArr[$i] !== "0" && $strArr[$i] !== "") {
                        $compareV[] = $vv;
                        $compareS[] = $strArr[$i];
                    }
                }

                $v      = $compareV;
                $strArr = $compareS;
            }
        } else {
            $strArr = [];

            foreach ($v as $i => $vv) {
                $tmpVal     = $this->individualValue_RetrieveInStorageFormat($vv);
                $strArr[]   = Tools::toString($tmpVal);
            }
        }

        if (\count($v) > \count(\array_unique($strArr))) {
            $r = "error.dm.field.collection.constraint.distinct.violated";
        }

        return $r;
    }
    /**
     * A partir da contagem total de uma coleção, identifica se o número de itens
     * está de acordo com as definições configuradas.
     *
     * @param       int $count
     *              Total de itens na coleção.
     *
     * @return      string
     */
    private function collectionCheckAcceptedCount(int $count) : string
    {
        $r = "valid";

        // Se a coleção de dados passou em todas as validações até aqui
        // E
        // se ela possui critérios de validação sobre a
        // contagem de seus elementos, efetua estas verificações
        if ($this->acceptedCount !== null) {
            $exactValues    = $this->acceptedCount["exactValues"];
            $multiples      = $this->acceptedCount["multiples"];
            $min            = $this->acceptedCount["min"];
            $max            = $this->acceptedCount["max"];

            if ($count < $min || $count > $max) {
                $r = "error.dm.field.collection.constraint.range.violated";
            }
            else {
                $checked = false;

                if (\count($exactValues) > 0 && \in_array($count, $exactValues) === true) {
                    $checked = true;
                }
                if (\count($multiples) > 0) {
                    foreach ($multiples as $mul) {
                        if (($count % $mul) === 0) {
                            $checked = true;
                        }
                    }
                }

                if ($checked === false) {
                    $r = "error.dm.field.collection.constraint.accepted.count.violated";
                }
            }
        }

        return $r;
    }




















    /**
     * Retorna toda a coleção de dados armazenada com seus valores convertidos em formato
     * de armazenamento.
     *
     * @param       array $v
     *              Coleção de valores que serão convertidos.
     *
     * @return      array
     */
    protected function collectionValue_RetrieveInStorageFormat(array $v) : array
    {
        $arr = [];
        foreach ($v as $vv) {
            if ($this->isReference() === true) {
                $arr[] = $this->modelIndividualValue_RetrieveInStorageFormat($vv);
            } else {
                $arr[] = $this->individualValue_RetrieveInStorageFormat($vv);
            }
        }
        return $arr;
    }
    /**
     * Verifica se a coleção é válida e retorna um ``array`` com informações sobre sua validação.
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
     *              Coleção que será testada.
     *
     * @return      array
     */
    protected function collectionValue_CheckValue($v) : array
    {
        $canSet = true;
        $state  = [];
        $cState = "valid";


        // Espera-se que uma coleção seja sempre um array simples.
        if (\is_array($v) === false || \array_is_assoc($v) === true) {
            $canSet = false;
            $cState = "error.dm.field.collection.expected.array";
        } else {

            // Para cada valor definido...
            foreach ($v as $i => $vv) {
                if ($vv === undefined) {
                    $canSet     = false;
                    $cState     = "error.dm.field.collection.invalid.member";
                    $state[]    = "error.dm.field.collection.member.not.allow.undefined";
                } elseif ($vv === null) {
                    $canSet     = false;
                    $cState     = "error.dm.field.collection.invalid.member";
                    $state[]    = "error.dm.field.collection.member.not.allow.null";
                } elseif ($vv === "") {
                    $canSet     = false;
                    $cState     = "error.dm.field.collection.invalid.member";
                    $state[]    = "error.dm.field.collection.member.not.allow.empty";
                } else {
                    if ($this->isReference() === true) {
                        $check = $this->modelIndividualValue_CheckValue($vv);
                    } else {
                        $check = $this->individualValue_CheckValue($vv);
                    }

                    $canSet     = ($canSet === true && $check["canSet"] === true);
                    $state[]    = $check["state"];

                    // Existindo na coleção qualquer valor inválido,
                    // OU,
                    // Tendo indicação de que a coleção não deva poder
                    // ser definida...
                    // Altera o estado da coleção para indicar este estado.
                    if ($check["valid"] === false || $canSet === false) {
                        $cState = "error.dm.field.collection.invalid.member";
                    }
                }
            }


            // Se após os testes com os valores individuais a coleção
            // ainda é considerada possível de ser definida
            // Passa a verificar se a coleção como um todo não quebra
            // alguma das regras definidas para sua constituição
            if ($canSet === true && $cState === "valid") {

                // Se a coleção permite apenas valores únicos e
                // ela possui mais que 1 valor...
                if ($this->collectionIsDistinct() === true && \count($v) > 1) {
                    $cState = $this->collectionCheckDistinct($v);
                }


                // Se a coleção de dados passou em todas as validações até aqui
                // E
                // se ela possui critérios de validação sobre a
                // contagem de seus elementos, efetua estas verificações
                if ($cState === "valid") {
                    $cState = $this->collectionCheckAcceptedCount(\count($v));
                }

            }
        }


        return [
            "canSet"    => $canSet,
            "valid"     => ($cState === "valid"),
            "state"     => $state,
            "cState"    => $cState
        ];
    }
    /**
     * Processa a coleção passada e retorna um array contendo as informações necessárias
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
    protected function collectionValue_ProccessSet($v) : array
    {
        $cvCV = $this->collectionValue_CheckValue($v);

        if ($cvCV["canSet"] === false) {
            $cvCV["rawValue"]   = [];
            $cvCV["value"]      = [];
        } else {
            $cvCV["rawValue"]   = $v;
            $cvCV["value"]      = [];

            // Para cada valor definido...
            foreach ($v as $i => $vv) {
                if ($this->isReference() === true) {
                    $cvCV["value"][] = $this->modelIndividualValue_RetrieveInStorageFormat($vv);
                } else {
                    $cvCV["value"][] = $this->individualValue_RetrieveInStorageFormat($vv);
                }
            }
        }

        return $cvCV;
    }
    /**
     * Retorna a coleção indicado conforme as definições de formatação.
     *
     * @param       array $val
     *              Valor que será tratado.
     *
     * @param       bool $formated
     *              Este parametro só surte efeto se houver um ``inputFormat`` definido.
     *              Se ``true``, retornará o valor conforme o padrão ``inputFormat`` define.
     *
     * @return      array
     */
    protected function collectionValue_ProccessGet(array $val, bool $formated = false) : array
    {
        $r = [];
        $arr = (($val === []) ? $this->getDefault() : $val);
        foreach ($arr as $vv) {
            if ($this->isReference() === true) {
                $r[] = $this->modelIndividualValue_ProccessGet($vv, $formated);
            } else {
                $r[] = $this->individualValue_ProccessGet($vv, $formated);
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
        return $this->collectionValue_RetrieveInStorageFormat($v);
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
        return $this->collectionValue_CheckValue($v);
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
        return $this->collectionValue_ProccessSet($v);
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
        return $this->collectionValue_ProccessGet($val, $formated);
    }
}
