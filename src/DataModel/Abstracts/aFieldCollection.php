<?php
declare (strict_types=1);

namespace AeonDigital\DataModel\Abstracts;

use AeonDigital\Interfaces\DataModel\iFieldCollection as iFieldCollection;
use AeonDigital\DataModel\Abstracts\aField as aField;
use AeonDigital\DataModel\Traits\FieldCollectionCommomMethods as FieldCollectionCommomMethods;






/**
 * Classe abstrata que extende ``aField`` para implementar ``iFieldCollection`` dando a
 * ela capacidade de lidar com coleções de dados.
 *
 * @package     AeonDigital\DataModel
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2019, Rianna Cantarelli
 * @license     ADPL-v1.0
 */
abstract class aFieldCollection extends aField implements iFieldCollection
{
    use FieldCollectionCommomMethods;










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
     *          // bool             Indica se a coleção permite receber valores repetidos. (opcional)
     *          //                  Usado apenas se o campo é mesmo uma coleção.
     *          "distinct" => ,
     *
     *          // string           Regras para validação da contagem de valores que devem/podem estar presentes
     *          //                  em uma coleção. (opcional)
     *          //                  Usado apenas se o campo é mesmo uma coleção.
     *          "acceptedCount" => ,
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
     *              Array associativo com as configurações para este campo.
     *
     * @throws      \InvalidArgumentException
     *              Caso algum valor passado não seja válido.
     */
    function __construct(array $config)
    {
        $config["allowNull"]            = false;
        $config["allowEmpty"]           = false;
        $config["convertEmptyToNull"]   = false;
        $config["readOnly"]             = false;

        if (isset($config["value"]) === false) { $config["value"] = []; }
        if (isset($config["default"]) === false) { $config["default"] = []; }

        parent::__construct($config);

        $distinct           = ((isset($config["distinct"]))             ? $config["distinct"]           : false);
        $acceptedCount      = ((isset($config["acceptedCount"]))        ? $config["acceptedCount"]      : null);

        $this->setCollectionIsDistinct($distinct);
        $this->collectionSetAcceptedCount($acceptedCount);
    }
}
