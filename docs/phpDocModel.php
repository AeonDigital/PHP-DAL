<?php
declare (strict_types = 1);

namespace AeonDigital\Traits;










/**
 * Modelo de uma classe documentada com PHPDocs.
 *
 * @see         http://reference-to-see.com
 * @see         http://reference-to-see.com
 * 
 * @package     AeonDigital\Namespace
 * @version     0.9.0 [alpha]
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   GNUv3
 */
class phpDocModel
{





    /**
     * Primeira propriedade estática.
     * 
     * <pre>
     * $arr = [ 
     *  // int      "key"       Numeral inteiro positivo. 
     *  // string   "value"     Valor correspondente 
     *  "key" => "value"
     * ];
     * </pre>
     *
     * @var         array
     */
    static public $firstAssocArray = [
        1 => "value 1",
        2 => "value 2"
    ];
    /**
     * Segunda propriedade estática.
     * 
     * <pre>
     * $arr = [ 
     *  // string   "key"       Nome da posição. 
     *  // string   "value"     Valor correspondente 
     *  "key" => "value"
     * ];
     * </pre>
     *
     * @var         array
     */
    static public $secondAssocArray = [
        "key1" => 1,
        "key2" => 2
    ];





    /**
     * Propriedade privada.
     *
     * @var         string
     */
    private $privateProperty = null;
    /**
     * Propriedade protegida.
     *
     * @var         string
     */
    protected $protectedProperty = null;
    /**
     * Propriedade pública.
     *
     * @var         string
     */
    public $publicProperty = null;










    /**
     * Construtor do documento modelo.
     *
     * @param       string $param1
     *              Descrição do parametro do construtor
     */
    function __construct(string $param1) {
        // ...
    }





    /**
     * Sumário é sempre o primeiro bloco de informações e pode ser formado
     * por uma ou mais linhas contanto que não possuam espaço entre elas.
     * 
     * A descrição deve ser um texto mais completo podendo ter qualquer quantidade
     * de linhas e aceita até mesmo espaços entre elas só terminando quando encontrar
     * o fim do bloco de documentação ou alguma tag.
     *
     * @param       string $param1
     *              Descrição do parametro.
     * 
     * @param       integer $param2
     *              Descrição do parametro.
     * 
     * @return      array
     *              O objeto de retorno pode ou não ter uma descrição.
     */
    public function methodDoc(string $param1, int $param2) : array
    {
        // ...
    }





    /**
     * Demonstração de como pode ser feita a documentação dos diferentes
     * tipos de parametros
     *
     * @param       mixed $mixedParam
     *              Parametro do tipo "mixed".
     * 
     * @param       string|int $multiTypeParam
     *              Parametro limitado a 2 tipos.
     * 
     * @param       bool $monoType
     *              Parametro de um tipo específico.
     * 
     * @param       null|int $nullableParam
     *              Parametro de um tipo específico, porem, "nulable".
     * 
     * @param       array $commomArray
     *              Array comum.
     * 
     * @param       DateTime $arrayWithType
     *              Array de um tipo específico.
     * 
     * @throws      InvalidArgumentException 
     *              Exception que será lançada caso ocorra algum erro relacionado a
     *              algum parametro inválido.
     *              
     * @return      bool|int
     *              Retorno variável, podendo retornar um tipo ou outro.
     */
    public function parameterDescriptionTypes(
        $mixedParam, 
        $multiTypeParam,
        bool $monoType,
        ?int $nullableParam,
        array $commomArray,
        array $arrayWithType        
        )
    {
        // ...
    }




    /**
     * Demonstração de uso de parametros de casos especiais.
     *
     * @param       DateTime[] $dateTimeCollection
     *              Coleção de datas.
     * 
     * @param       array $associativeArrayWithOneLevel
     *              Array associativo em 1 único nível:
     * <pre>
     * $arr = [
     *  // string   "key"       Descrição do valor "chave".
     *  // string   "value"     Descrição do tipo de valor esperado.
     *  "key" => "value"
     * ];
     * </pre>
     * 
     * @return      void
     */
    public function specialParameters(
        array $dateTimeCollection
    ) : void
    {
        // ..
    }
}
