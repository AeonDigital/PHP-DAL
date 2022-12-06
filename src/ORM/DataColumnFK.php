<?php

declare(strict_types=1);

namespace AeonDigital\ORM;

use AeonDigital\Interfaces\ORM\iColumnFK as iColumnFK;
use AeonDigital\Interfaces\ORM\iTable as iTable;
use AeonDigital\Interfaces\ORM\iDataTableFactory as iDataTableFactory;
use AeonDigital\DataModel\Abstracts\aFieldModel as aFieldModel;




/**
 * Representação de uma coluna de dados que armazena uma referência para um outro
 * registro de uma outra tabela de dados.
 *
 * @package     AeonDigital\ORM
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2020, Rianna Cantarelli
 * @license     MIT
 */
class DataColumnFK extends aFieldModel implements iColumnFK
{
    use \AeonDigital\ORM\Traits\ColumnProperties;
    use \AeonDigital\ORM\Traits\DataColumnCommomMethods;
    use \AeonDigital\ORM\Traits\ColumnFKProperties;




    /**
     * Inicia um novo campo de dados.
     *
     * @param array $config
     * Array associativo com as configurações para este campo.
     *
     * ```php
     *      $arr = [
     *          string          "name"
     *          Nome do campo.
     *
     *          string          "description"
     *          Descrição do campo. (opcional)
     *
     *          bool            "allowNull"
     *          Indica se "null" é um valor aceito para este campo. (opcional)
     *
     *          bool            "readOnly"
     *          Indica se o campo é apenas de leitura.
     *          Neste caso ele poderá ser definido apenas 1 vez e após
     *          isto seu valor não poderá ser alterado. (opcional)
     *
     *          string          "fkTableName"
     *          Nome da tabela de dados a qual esta coluna se referencia.
     *
     *          string          "fkDescription"
     *          Descrição especial desta coluna enquanto FK. (opcional)
     *
     *          bool            "fkAllowNull"
     *          Indica se os objetos filhos devem ser obrigados a terem uma correlação
     *          obrigatória com o objeto pai. (opcional)
     *
     *          bool            "fkUnique"
     *          Indica que cada objeto pai pode se relacionar com apenas 1 objeto filho e vice-versa.
     *
     *          string          "fkOnUpdate"
     *          Regra para ser aplicada nesta FK quando o registro pai for alterado. (opcional)
     *          São esperados um dos seguintes valores:
     *          [ RESTRICT | NO ACTION | CASCADE | SET NULL | SET DEFAULT ]
     *
     *          string          "fkOnDelete"
     *          Regra para ser aplicada nesta FK quando o registro pai for excluído. (opcional)
     *          São esperados um dos seguintes valores:
     *          [ RESTRICT | NO ACTION | CASCADE | SET NULL | SET DEFAULT ]
     *
     *          mixed           "value"
     *          Valor que inicia com o campo.
     *      ];
     * ```
     *
     * @param iDataTableFactory $factory
     * Instância de uma fábrica de tabelas de dados.
     *
     * @throws \InvalidArgumentException
     * Caso algum valor passado não seja válido.
     */
    function __construct(array $config, iDataTableFactory $factory)
    {
        $config["modelName"] = ((isset($config["fkTableName"])) ? $config["fkTableName"] : null);

        parent::__construct($config, $factory);

        // Propriedades básicas de um campo de dados.
        $this->unique           = ((isset($config["unique"])) ? $config["unique"] : false);
        $this->autoIncrement    = false;
        $this->primaryKey       = false;
        $this->foreignKey       = true;
        $this->index            = true;


        // Propriedades específicas para um campo do tipo "reference"
        $fkDescription          = ((isset($config["fkDescription"]))    ? $config["fkDescription"]          : null);
        $fkAllowNull            = ((isset($config["fkAllowNull"]))      ? $config["fkAllowNull"]            : true);
        $fkUnique               = ((isset($config["fkUnique"]))         ? $config["fkUnique"]               : false);
        $fkOnUpdate             = ((isset($config["fkOnUpdate"]))       ? \strtoupper($config["fkOnUpdate"]) : null);
        $fkOnDelete             = ((isset($config["fkOnDelete"]))       ? \strtoupper($config["fkOnDelete"]) : null);


        $validOptions = [
            "RESTRICT", "NO ACTION", "CASCADE", "SET NULL", "SET DEFAULT"
        ];

        if ($fkOnUpdate !== null && \in_array($fkOnUpdate, $validOptions) === false) {
            $msg = "Invalid \"ON UPDATE\" definition [\"" . $fkOnUpdate . "\"].";
            throw new \InvalidArgumentException($msg);
        }

        if ($fkOnDelete !== null && \in_array($fkOnDelete, $validOptions) === false) {
            $msg = "Invalid \"ON DELETE\" definition [\"" . $fkOnDelete . "\"].";
            throw new \InvalidArgumentException($msg);
        }

        $this->fkDescription    = $fkDescription;
        $this->fkAllowNull      = $fkAllowNull;
        $this->fkUnique         = $fkUnique;
        $this->fkOnUpdate       = $fkOnUpdate;
        $this->fkOnDelete       = $fkOnDelete;
    }





    /**
     * Retorna uma instância do modelo de dados usada por este campo.
     *
     * @return iTable
     */
    public function getTable(): iTable
    {
        return $this->getModel();
    }
}
