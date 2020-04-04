<?php
declare (strict_types=1);

namespace AeonDigital\DataModel\Abstracts;

use AeonDigital\Interfaces\DataModel\iFieldCollection as iFieldCollection;
use AeonDigital\Interfaces\DataModel\iModelFactory as iModelFactory;
use AeonDigital\DataModel\Abstracts\aFieldModel as aFieldModel;
use AeonDigital\DataModel\Traits\FieldCollectionCommomMethods as FieldCollectionCommomMethods;





/**
 * Classe abstrata que extende ``aFieldModel`` para implementar ``iFieldCollection`` dando
 * a ela capacidade de lidar com coleções de modelos de dados.
 *
 * @package     AeonDigital\DataModel
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2019, Rianna Cantarelli
 * @license     ADPL-v1.0
 */
abstract class aFieldModelCollection extends aFieldModel implements iFieldCollection
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
     *          // string           Nome do modelo de dados a ser usado por este campo. Uma vez definido,
     *          //                  irá anular qualquer definição de propriedades incompatíveis com esta e, a
     *          //                  propriedade "type" será definida como "reference". (opcional)
     *          "modelName" => ,
     *
     *          // array            Usado quando o campo é uma coleção de instâncias de modelos de dados.
     *          //                  Deve indicar quais chaves/campos devem ser utilizados para comparar
     *          //                  a coleção de objetos e determinar quais deles são iguais.
     *          //                  Por padrão, TODOS os campos serão utilizados para efetuar a comparação.
     *          "distinctKeys" => ,
     *
     *          // string           Regras para validação da contagem de valores que devem/podem estar presentes
     *          //                  em uma coleção. (opcional)
     *          //                  Usado apenas se o campo é mesmo uma coleção.
     *          "acceptedCount" => ,
     *
     *          // mixed            Valor que inicia com o campo.
     *          "value" => ,
     *      ];
     * ```
     *
     * @param       array $config
     *              Array associativo com as configurações para este campo.
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
        $config["allowNull"]            = false;
        $config["readOnly"]             = false;
        $config["distinct"]             = true;

        parent::__construct($config, $factory);


        // Redefine valores iniciais caso eles não tenham sido definidos ainda
        if ($this->value === undefined) {
            $this->setValue([]);
        }
        if ($this->default === undefined) {
            $this->setDefault([]);
        }


        $distinctKeys   = ((isset($config["distinctKeys"]))     ? $config["distinctKeys"]   : null);
        $acceptedCount  = ((isset($config["acceptedCount"]))    ? $config["acceptedCount"]  : null);

        $this->setCollectionIsDistinct(true);
        $this->setCollectionGetDistinctKeys($distinctKeys);
        $this->collectionSetAcceptedCount($acceptedCount);
    }
}
