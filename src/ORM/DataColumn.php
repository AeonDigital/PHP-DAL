<?php
declare (strict_types=1);

namespace AeonDigital\ORM;

use AeonDigital\Interfaces\ORM\iColumn as iColumn;
use AeonDigital\DataModel\Abstracts\aField as aField;
use AeonDigital\ORM\Traits\ColumnProperties as ColumnProperties;
use AeonDigital\ORM\Traits\DataColumnCommomMethods as DataColumnCommomMethods;





/**
 * Representação de uma coluna de dados comum.
 *
 * @package     AeonDigital\ORM
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2020, Rianna Cantarelli
 * @license     ADPL-v1.0
 */
class DataColumn extends aField implements iColumn
{
    use ColumnProperties;
    use DataColumnCommomMethods;




    /**
     * Inicia um novo campo de dados.
     *
     * @param       array $config
     *              Array associativo com as configurações para este campo.
     *
     * ``` php
     *      $arr = [
     *          string          "name"
     *          Nome do campo.
     *
     *          string          "description"
     *          Descrição do campo. (opcional)
     *
     *          string          "type"
     *          Nome completo de uma classe que implemente a interface "iSimpleType".
     *          OU "ref" para identificar que este campo referencia-se a um outro modelo
     *          de dados.
     *
     *          string          "inputFormat"
     *          Nome completo de uma classe que implemente a interface "iFormat". (opcional)
     *
     *          int             "length"
     *          Tamanho máximo do campo em caracteres. (opcional)
     *          Se não for definido explicitamente poderá herdar das informações
     *          indicadas em "inputFormat".
     *
     *          mixed           "min"
     *          Valor mínimo aceito para este campo. (opcional)
     *          Use apenas para casos de campos numéricos ou data/hora.
     *
     *          mixed           "max"
     *          Valor máximo aceito para este campo. (opcional)
     *          Use apenas para casos de campos numéricos ou data/hora.
     *
     *          bool            "allowNull"
     *          Indica se "null" é um valor aceito para este campo. (opcional)
     *
     *          bool            "allowEmpty"
     *          Indica se "" é um valor aceito para este campo. (opcional)
     *
     *          bool            "convertEmptyToNull"
     *          Indica se, ao receber um valor "", este deve ser convertido para "null". (opcional)
     *
     *          bool            "readOnly"
     *          Indica se o campo é apenas de leitura.
     *          Neste caso ele poderá ser definido apenas 1 vez e após
     *          isto seu valor não poderá ser alterado. (opcional)
     *
     *          mixed           "default"
     *          Valor padrão para este campo. (opcional)
     *
     *          array|string    "enumerator"
     *          Coleção de valores válidos para este campo. (opcional)
     *          Se for definido uma string, deve ser o caminho completo até um arquivo php
     *          que contêm o array a ser utilizado como enumerador.
     *
     *          mixed           "value"
     *          Valor que inicia com o campo.
     *
     *          bool            "unique"
     *          Indica quando esta coluna de dados deve ser a única dentro da coleção
     *          de registros da tabela de dados a possuir o valor atual.
     *
     *          bool            "autoIncrement"
     *          Indica quando esta coluna de dados deve ter seu valor definido pelo próprio
     *          SGDB usando assim o controle de auto-incremento.
     *
     *          bool            "primaryKey"
     *          Indica quando esta coluna de dados é a chave primária da tabela de dados.
     *
     *          bool            "index"
     *          Indica quando esta coluna de dados deve ser indexada.
     *      ];
     * ```
     *
     * @throws      \InvalidArgumentException
     *              Caso algum valor passado não seja válido.
     */
    function __construct(array $config)
    {
        // Verifica o uso de um inputFormat especial
        if (isset($config["inputFormat"]) === true) {
            $config["inputFormat"] = $this->selectInputFormat($config["inputFormat"]);
        }

        parent::__construct($config);


        // Resgata as propriedades típicas deste tipo de campo
        $this->unique           = ((isset($config["unique"]))           ? $config["unique"]         : false);
        $this->autoIncrement    = ((isset($config["autoIncrement"]))    ? $config["autoIncrement"]  : false);
        $this->primaryKey       = ((isset($config["primaryKey"]))       ? $config["primaryKey"]     : false);
        $this->index            = ((isset($config["index"]))            ? $config["index"]          : false);
    }
}
