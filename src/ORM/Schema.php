<?php
declare (strict_types=1);

namespace AeonDigital\ORM;

use AeonDigital\Interfaces\ORM\iSchema as iSchema;
use AeonDigital\Interfaces\ORM\iColumnFK as iColumnFK;
use AeonDigital\Interfaces\ORM\iDataTableFactory as iDataTableFactory;






/**
 * Classe que cria ou atualiza um schema em um banco de dados.
 *
 * @package     AeonDigital\ORM
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2020, Rianna Cantarelli
 * @license     ADPL-v1.0
 */
class Schema implements iSchema
{





    /**
     * Instância da fábrica de objetos ``iTable`` referentes ao projeto que está sendo
     * usado.
     *
     * @var         iDataTableFactory
     */
    private ?iDataTableFactory $factory = null;
    /**
     * Tipo do banco de dados utilizado.
     *
     * @var         ?string
     */
    private ?string $dbType = null;



    /**
     * Correlação entre os tipos de dados primitivos e os tipos de dados do banco de dados
     * que está sendo usado.
     *
     * Quando uma instância é criada esta propriedade é alterada para armazenar somente as
     * informações referentes ao tipo do banco de dados que está sendo utilizada no momento.
     *
     * @type    array
     */
    private array $dataTypeMap = [
        "mysql"         => [
                        "Bool"          =>  "TINYINT(1)",
                        "Byte"          =>  "TINYINT",
                        "Short"         =>  "SMALLINT",
                        "Int"           =>  "INTEGER",
                        "Long"          =>  "BIGINT",
                        "Float"         =>  "FLOAT",
                        "Double"        =>  "DOUBLE",
                        "Real"          =>  "DECIMAL(10,4)",
                        "DateTime"      =>  "DATETIME",
                        "String"        =>  "VARCHAR(x)",
                        "Text"          =>  "LONGTEXT"
                    ],
        "mssqlserver"   => [],
        "oracle"        => [],
        "postgree"      => []
    ];










    /**
     * A partir das informações contidas na fábrica de tabelas de dados para o projeto em
     * uso, gera um arquivo ``_schema.php`` contendo todas as instruções SQL necessárias
     * para a criação dos modelos no banco de dados alvo.
     *
     * @return      bool
     *
     * @throws      \Exception
     *              Quando a configuração de uma linkTable não está correta.
     */
    public function generateCreateSchemaFiles() : bool
    {
        $r = false;

        $createTable    = [];
        $constraints    = [];
        $listDataTables = [];
        $linkTables     = [];


        // Para cada tabela de dados existente...
        $dataTableList = $this->factory->getDataTableList();
        foreach ($dataTableList as $tableName) {
            if (isset($listDataTables[$tableName]) === false) {
                $listDataTables[$tableName] = $this->factory->createDataTable($tableName);
            }

            $dataTable      = $listDataTables[$tableName];
            $strCreateTable = $this->generateInstructionCreateTable($tableName, $dataTable->getDescription());


            // Adiciona as colunas comuns
            // E chaves extrangeiras que demarcam relações 1-1
            $columns        = [];
            $columnNames    = $dataTable->getFieldNames();
            $tableAlias     = $dataTable->getAlias();
            $usedColumns    = [];
            foreach ($columnNames as $cName) {
                $oColumn    = $dataTable->{"_$cName"};

                // Sendo uma coluna de dados normal
                if ($oColumn->isReference() === false) {
                    $usedColumns[] = $cName;

                    // Adiciona instrução para a chave primária
                    if ($oColumn->isPrimaryKey() === true) {
                        $columns[] = $this->generateInstructionAddPK();
                    }
                    // Adiciona instrução para colunas comuns
                    else {
                        $useType    = \str_replace("AeonDigital\\SimpleType\\st", "", $oColumn->getType());
                        $useLength  = $oColumn->getLength();
                        $useType    = (($useType === "String" && $useLength === null) ? "Text" : $useType);


                        $enum       = $oColumn->getEnumerator();
                        $isUnique   = $oColumn->isUnique();
                        $isIndex    = $oColumn->isIndex();

                        $columns[] = $this->generateInstructionAddColumn(
                            $cName,
                            $oColumn->getDescription(),
                            $this->dataTypeMap[$useType],
                            $useLength,
                            $oColumn->isAllowNull(),
                            $oColumn->getDefault(true)
                        );

                        if ($isUnique === true) {
                            $constraints[] = $this->generateInstructionConstraintUnique($tableName, $tableAlias, $cName);
                        }

                        if ($isIndex === true) {
                            $constraints[] = $this->generateInstructionDefineIndex($tableName, $tableAlias, $cName);
                        }

                        if ($enum !== null) {
                            $constraints[] = $this->generateInstructionConstraintEnumerator($tableName, $tableAlias, $cName, $enum);
                        }
                    }
                }
                // Tratando-se de uma chave estrangeira simples (cardinalidade 1-1)
                else {

                    // Sendo uma referência 1-1
                    if ($oColumn->isCollection() === false)
                    {
                        $usedColumns[] = $oColumn->getModelName() . "_Id";

                        $columns[] = $this->generateInstructionAddColumn(
                            $oColumn->getModelName() . "_Id",
                            $oColumn->getDescription(),
                            $this->dataTypeMap["Long"],
                            null,
                            $oColumn->isAllowNull(),
                            undefined
                        );

                        if (isset($listDataTables[$oColumn->getModelName()]) === false) {
                            $listDataTables[$oColumn->getModelName()] = $this->factory->createDataTable($oColumn->getModelName());
                        }

                        $fkTableData    = $listDataTables[$oColumn->getModelName()];
                        $constraints[]  = $this->generateInstructionAddFK(
                            $tableName,
                            $tableAlias,
                            $fkTableData->getName(),
                            $fkTableData->getAlias(),
                            $fkTableData->getName() . "_Id",
                            $oColumn->getFKOnUpdate(),
                            $oColumn->getFKOnDelete()
                        );
                    }
                    // Senão, tratando-se de uma linktable.
                    else {
                        if ($oColumn->isFKLinkTable() === true) {
                            $link = $this->retrieveLinkTableData(
                                $tableName,
                                $tableAlias,
                                $oColumn);

                            if ($link === []) {
                                $tgtCol = $tableName . "." . $cName;
                                $msg = "Can not create linkTable in $tgtCol.";
                                throw new \Exception($msg);
                            } else {

                                $linkTableName = $link["linkTableName"];
                                if (isset($linkTables[$linkTableName]) === false) {
                                    $linkTables[$linkTableName] = $link;
                                }
                            }
                        }
                    }
                }
            }



            // Verifica as demais tabelas de dados e identifica aquelas que
            // definem chaves extrangeiras nesta.
            foreach ($dataTableList as $tableNameFK) {
                if (isset($listDataTables[$tableNameFK]) === false) {
                    $listDataTables[$tableNameFK] = $this->factory->createDataTable($tableNameFK);
                }

                if ($tableName !== $tableNameFK) {
                    $fkTableData    = $listDataTables[$tableNameFK];
                    $columnNames    = $fkTableData->getFieldNames();
                    $tableAliasFK   = $fkTableData->getAlias();

                    // Para cada coluna de dados existênte na tabela alvo
                    foreach ($columnNames as $cFKName) {
                        $oColumnFK = $fkTableData->{"_$cFKName"};

                        // Se a coluna aponta para um outro modelo de dados
                        // E
                        // trata-se de uma coleção de registros.
                        // E
                        // o modelo apontado é a tabela que está sendo construida no momento...
                        if ($oColumnFK->isReference() === true &&
                            $oColumnFK->isCollection() === true &&
                            $oColumnFK->getModelName() === $tableName)
                        {
                            if ($oColumnFK->isFKLinkTable() === false) {
                                $fkName = $tableNameFK . "_Id";
                                if (\in_array($fkName, $usedColumns) === false) {

                                    $columns[] = $this->generateInstructionAddColumn(
                                        $fkName,
                                        $oColumnFK->getFKDescription(),
                                        $this->dataTypeMap["Long"],
                                        null,
                                        $oColumnFK->isFKAllowNull(),
                                        undefined
                                    );

                                    $constraints[] = $this->generateInstructionAddFK(
                                        $tableName,
                                        $tableAlias,
                                        $tableNameFK,
                                        $tableAliasFK,
                                        $fkName,
                                        $oColumnFK->getFKOnUpdate(),
                                        $oColumnFK->getFKOnDelete()
                                    );
                                }
                            }
                        }
                    }
                }
            }



            $columns[]      = $this->generateInstructionConstraintPK();
            $strColumn      = "    " . \implode(", \n    ", $columns);
            $createTable[]  = \str_replace("[[columns]]", $strColumn, $strCreateTable);

            $executeAfterCreateTable = $dataTable->getExecuteAfterCreateTable();
            if ($executeAfterCreateTable !== null) {
                foreach ($executeAfterCreateTable as $instruction) {
                    $constraints[] = $instruction;
                }
            }
        }



        // Mescla as informações de linkTable com as tabelas de dados comuns
        foreach ($linkTables as $linkTableName => $linkTableData) {
            $createTable[]  = $linkTableData["createTable"];
            $constraints    = \array_merge($constraints, $linkTableData["constraints"]);
        }



        $now = new \DateTime();
        $nowDate        = $now->format("Y-m-d-H-i-s");
        $strIniti       = "";
        $strConstraints = "";
        $strEnd         = "";
        $strSchema      = "";

        $strIniti       .= "/*\n";
        $strIniti       .= " * Main Schema definition\n";
        $strIniti       .= " * Generated in $nowDate\n";
        $strIniti       .= "*/\n";

        $strConstraints .= "\n\n\n\n";
        $strConstraints .= "/*\n";
        $strConstraints .= " * Constraints definition\n";
        $strConstraints .= "*/\n";

        $strEnd         .= "\n\n\n\n/*\n";
        $strEnd         .= " * End of Main Schema definition\n";
        $strEnd         .= " * Generated in $nowDate\n";
        $strEnd         .= "*/\n";

        $strIniCreate   = "\n/*--INI CREATE TABLE--*/\n";
        $strEndCreate   = "\n/*--END CREATE TABLE--*/\n\n\n";
        $strIniConstr   = "\n/*--INI CONSTRAINT INSTRUCTIONS--*/\n";
        $strEndConstr   = "\n/*--END CONSTRAINT INSTRUCTIONS--*/\n";

        $strSchemaCreateTable   = $strIniCreate . \implode($strEndCreate . $strIniCreate, $createTable) . $strEndCreate;
        $strSchemaConstraints   = $strIniConstr . \implode("\n", $constraints) . $strEndConstr;
        $strSchema             .= $strIniti . $strSchemaCreateTable . $strConstraints . $strSchemaConstraints . $strEnd;

        $tgtSchemaFilePath      = $this->factory->getProjectDirectory() . DS . "_projectSchema.sql";

        $r = \file_put_contents($tgtSchemaFilePath, $strSchema);
        return ($r !== false);
    }
    /**
     * Retorna o modelo que deve ser usado por uma instrução SQL para a criação de uma
     * tabela de dados para o tipo de banco de dados que está sendo usado no momento.
     *
     * @param       string $tableName
     *              Nome da tabela de dados.
     *
     * @param       ?string $description
     *              Descrição para a coluna de dados.
     *
     * @return      string
     */
    private function generateInstructionCreateTable(string $tableName, ?string $description) : string
    {
        $str = "";

        switch ($this->dbType) {
            case "mysql":
                $useDesc = "";
                if ($description !== null && $description !== "") {
                    $useDesc = " COMMENT '$description'";
                }

                $str = "CREATE TABLE $tableName (\n[[columns]]\n)$useDesc;";
                break;

            case "mssqlserver":
                break;

            case "oracle":
                break;

            case "postgree":
                break;
        }

        return $str;
    }
    /**
     * Retorna uma instrução para inserir uma chave primária (Id).
     *
     * @return      string
     */
    private function generateInstructionAddPK() : string
    {
        $str = "";

        switch ($this->dbType) {
            case "mysql":
                $str = "Id BIGINT NOT NULL AUTO_INCREMENT";
                break;

            case "mssqlserver":
                break;

            case "oracle":
                break;

            case "postgree":
                break;
        }

        return $str;
    }
    /**
     * Retorna uma instrução para inserir uma constraint para a chave primária.
     *
     * @return      string
     */
    private function generateInstructionConstraintPK() : string
    {
        $str = "";

        switch ($this->dbType) {
            case "mysql":
                $str = "PRIMARY KEY (Id)";
                break;

            case "mssqlserver":
                break;

            case "oracle":
                break;

            case "postgree":
                break;
        }

        return $str;
    }
    /**
     * Retorna uma instrução para inserir uma coluna de dados em uma tabela que corresponda
     * aos valores dos parametros informados.
     *
     * @param       string $name
     *              Nome da coluna.
     *
     * @param       ?string $description
     *              Descrição para a coluna de dados.
     *
     * @param       string $type
     *              Tipo de dados da coluna.
     *
     * @param       ?int $length
     *              Quantidade de caracteres suportados por uma coluna de dados que armazena
     *              strings.
     *
     * @param       bool $allowNull
     *              Indica quando é ou não permitido definir ``null`` como um valor válido para
     *              esta coluna.
     *
     * @param       mixed $default
     *              Valor padrão a ser definido para a coluna de dados.
     *
     * @return      string
     */
    private function generateInstructionAddColumn(
        string $name,
        ?string $description,
        string $type,
        ?int $length,
        bool $allowNull,
        $default = undefined
    ) : string {


        switch ($this->dbType) {
            case "mysql":
                $allowNull = (($allowNull === true) ? "" : " NOT NULL");

                if ($default === undefined) {
                    $default = null;
                } else {
                    $useDefault = " DEFAULT ";

                    switch ($type) {
                        case "TINYINT(1)":
                            $useDefault .= (($default === true) ? "1" : "0");
                            break;

                        case "TINYINT":
                        case "SMALLINT":
                        case "INTEGER":
                        case "BIGINT":
                        case "FLOAT":
                        case "DOUBLE":
                        case "DECIMAL(10,4)":
                            $useDefault .= $default;
                            break;

                        case "VARCHAR(x)":
                        case "LONGTEXT":
                            $useDefault .= "'$default'";
                            break;

                        case "DATETIME":
                            $useDefault .= (($default === "NOW()") ? "NOW()" : $default->format("Y-m-d H-i-s"));
                            break;
                    }
                    $default = $useDefault;
                }

                $type = (($type === "VARCHAR(x)") ? \str_replace("x", $length, $type) : $type);

                $str = "$name $type" . $allowNull . $default;
                if ($description !== null && $description !== "") {
                    $str .= " COMMENT '$description'";
                }
                break;

            case "mssqlserver":
                break;

            case "oracle":
                break;

            case "postgree":
                break;
        }

        return $str;
    }
    /**
     * Retorna uma instrução para inserir uma constraint para verificar se o valor desta
     * coluna é unico dentro da coleção de registros da tabela.
     *
     * @param       string $tableName
     *              Nome da tabela alvo.
     *
     * @param       string $tableAlias
     *              Alias usado para identificar a tabela que contém a coluna que receberá
     *              esta regra.
     *
     * @param       string $colName
     *              Nome da coluna.
     *
     * @return      string
     */
    private function generateInstructionConstraintUnique(
        string $tableName,
        string $tableAlias,
        string $colName
    ) : string {
        $str = "";

        switch ($this->dbType) {
            case "mysql":
                $ctName = "uc_" . $tableAlias . "_" . $colName;
                $str = "ALTER TABLE $tableName ADD CONSTRAINT $ctName UNIQUE ($colName);";
                break;

            case "mssqlserver":
                break;

            case "oracle":
                break;

            case "postgree":
                break;
        }

        return $str;
    }
    /**
     * Retorna uma instrução para inserir uma constraint para verificar se o valor desta
     * coluna é unico dentro da coleção de registros da tabela.
     *
     * @param       string $tableName
     *              Nome da tabela alvo.
     *
     * @param       string $tableAlias
     *              Alias usado para identificar a tabela que contém a coluna que receberá
     *              esta regra.
     *
     * @param       string $colName
     *              Nome da coluna.
     *
     * @return      string
     */
    private function generateInstructionDefineIndex(
        string $tableName,
        string $tableAlias,
        string $colName
    ) : string {
        $str = "";

        switch ($this->dbType) {
            case "mysql":
                $ctName = "idx_" . $tableAlias . "_" . $colName;
                $str = "CREATE INDEX $ctName ON $tableName ($colName);";
                break;

            case "mssqlserver":
                break;

            case "oracle":
                break;

            case "postgree":
                break;
        }

        return $str;
    }
    /**
     * Retorna uma instrução para inserir uma constraint para verificar se o valor desta
     * coluna é um dos valores definidos em sua lista enumerada de valores.
     *
     * @param       string $tableName
     *              Nome da tabela alvo.
     *
     * @param       string $tableAlias
     *              Alias usado para identificar a tabela que contém a coluna que receberá
     *              esta regra.
     *
     * @param       string $colName
     *              Nome da coluna.
     *
     * @param       array $enum
     *              Coleção de enumeradores.
     *
     * @return      string
     */
    private function generateInstructionConstraintEnumerator(
        string $tableName,
        string $tableAlias,
        string $colName,
        array $enum
    ) : string {
        $str = "";

        $useEnum = [];
        foreach ($enum as $k => $v) {
            if (\is_array($v) === false) {
                $useEnum[] = $v;
            }
            else {
                if (\count($v) !== 2) {
                    $msg = "Invalid enumerator value. Multidimensional arrays must have 2 values defined.";
                    throw new \InvalidArgumentException($msg);
                } else {
                    $useEnum[] = $v[0];
                }
            }
        }

        switch ($this->dbType) {
            case "mysql":
                $enum = \implode("', '", $useEnum);
                $ctName = "enum_" . $tableAlias . "_" . $colName;
                $str = "ALTER TABLE $tableName ADD CONSTRAINT $ctName CHECK ($colName IN ('$enum'));";
                break;

            case "mssqlserver":
                break;

            case "oracle":
                break;

            case "postgree":
                break;
        }

        return $str;
    }
    /**
     * Retorna uma instrução para inserir uma constraint para definir uma chave extrangeira.
     *
     * @param       string $tableName
     *              Nome da tabela que contêm a chave extrangeira.
     *
     * @param       string $tableAlias
     *              Alias usado para identificar a tabela que contém
     *              a coluna que receberá esta regra.
     *
     * @param       string $tableNameFK
     *              Nome da tabela para onde a chave extrangeira aponta.
     *
     * @param       string $tableAliasFk
     *              Alias usado para identificar a tabela da chave etrangeira.
     *
     * @param       string $colName
     *              Nome da coluna.
     *
     * @param       ?string $fkOnUpdate
     *              Regra a ser executada quando o registro pai for alterado.
     *
     * @param       ?string $fkOnDelete
     *              Regra a ser executada quando o registro pai for excluído.
     *
     * @return      string
     */
    private function generateInstructionAddFK(
        string $tableName,
        string $tableAlias,
        string $tableNameFK,
        string $tableAliasFK,
        string $colName,
        ?string $fkOnUpdate,
        ?string $fkOnDelete
    ) : string {
        $str = "";

        switch ($this->dbType) {
            case "mysql":
                $ctName     = "fk_" . $tableAlias . "_to_" . $tableAliasFK . "_". $colName;
                $onUpdate   = ($fkOnUpdate === null) ? "" : " ON UPDATE $fkOnUpdate";
                $onDelete   = ($fkOnDelete === null) ? "" : " ON DELETE $fkOnDelete";
                $str = "ALTER TABLE $tableName ADD CONSTRAINT $ctName FOREIGN KEY ($colName) REFERENCES $tableNameFK(Id)$onUpdate$onDelete;";
                break;

            case "mssqlserver":
                break;

            case "oracle":
                break;

            case "postgree":
                break;
        }

        return $str;
    }
    /**
     * Retorna os metadados necessários para definir uma linkTable.
     *
     * @param       string $table01Name
     *              Nome da tabela 01 da relação.
     *
     * @param       string $table01Alias
     *              Alias da tabela 01 da relação.
     *
     * @param       iColumnFK $table01Column
     *              Coluna de dados que faz referência a uma FK do tipo ``linkTable``.
     *
     * @return      array
     *              Retornará um array associativo conforme o modelo:
     *
     * ``` php
     *      $arr => [
     *          "linkTableName"     string  Nome da tabela "linkTable".
     *          "createTable"       string  Instrução para criação da "linkTable".
     *          "constraints"       string  Instruções "constraints" para a "linkTable".
     *      ];
     * ```
     */
    private function retrieveLinkTableData(
        string $table01Name,
        string $table01Alias,
        iColumnFK $table01Column
    ) : array {
        $table01Description = $table01Column->getFKDescription();
        $table01AllowNull   = $table01Column->isFKAllowNull();



        // Procura na tabela de dados secundária a coluna que
        // faz referência à primeira tabela da relação.
        $fkTable            = $table01Column->getModel();
        $fieldNames         = $fkTable->getFieldNames();
        $table02Column      = null;
        foreach ($fieldNames as $colName) {
            $field = $fkTable->{"_$colName"};
            if ($field->isReference() === true &&
                $field->isFKLinkTable() === true &&
                $field->getModelName() === $table01Name)
            {
                $table02Column = $field;
                break;
            }
        }


        if ($table02Column === null) {
            return [];
        } else {
            $table02Name        = $fkTable->getName();
            $table02Alias       = $fkTable->getAlias();
            $table02Description = $table02Column->getFKDescription();
            $table02AllowNull   = $table02Column->isFKAllowNull();

            $arrTables = [$table01Alias, $table02Alias];
            \rsort($arrTables);

            $linkTableName  = \implode("_to_", $arrTables);
            $createTable    = $this->generateInstructionCreateLinkTable(
                $table01Name,
                $table01Alias,
                $table01Description,
                $table01AllowNull,
                $table02Name,
                $table02Alias,
                $table02Description,
                $table02AllowNull
            );

            $constraint[] = $this->generateInstructionAddFK(
                $linkTableName,
                \str_replace("_to_", "_", $linkTableName),
                $table01Name,
                $table01Alias,
                $table02Column->getModelName() . "_Id",
                null,
                "CASCADE"
            );

            $constraint[] = $this->generateInstructionAddFK(
                $linkTableName,
                \str_replace("_to_", "_", $linkTableName),
                $table02Name,
                $table02Alias,
                $table01Column->getModelName() . "_Id",
                null,
                "CASCADE"
            );

            return [
                "linkTableName" => $linkTableName,
                "createTable"   => $createTable,
                "constraints"   => $constraint
            ];
        }

    }
    /**
     * Gera uma instrução ``CREATE TABLE`` para a criação de uma ``linkTable`` permitindo
     * assim que 2 tabelas de dados se referenciem mutuamente em uma relação ``N-N``.
     *
     *
     * @param       string $table01Name
     *              Nome da tabela 01 da relação.
     *
     * @param       string $table01Alias
     *              Alias da tabela 01 da relação.
     *
     * @param       string $table01Description
     *              Descrição da coluna de dados 01 da relação.
     *
     * @param       bool $table01AllowNull
     *              Instrução ``allowNull`` referente a coluna de dados 01 da relação.
     *
     * @param       string $table02Name
     *              Nome da tabela 02 da relação.
     *
     * @param       string $table02Alias
     *              Alias da tabela 02 da relação.
     *
     * @param       string $table02Description
     *              Descrição da coluna de dados 02 da relação.
     *
     * @param       bool $table02AllowNull
     *              Instrução ``allowNull`` referente a coluna de dados 02 da relação.
     *
     * @return      string
     */
    private function generateInstructionCreateLinkTable(
        string $table01Name,
        string $table01Alias,
        string $table01Description,
        bool $table01AllowNull,
        string $table02Name,
        string $table02Alias,
        string $table02Description,
        bool $table02AllowNull
    ) : string {

        $arrTables = [$table01Alias, $table02Alias];
        \rsort($arrTables);

        $linkTableName  = \implode("_to_", $arrTables);
        $columnFK       = [];
        $useDescription = "LinkTable : $table01Name <-> $table02Name";

        $strCreateTable = $this->generateInstructionCreateTable($linkTableName, $useDescription);
        $columnFK[] = $this->generateInstructionAddColumn(
            $table01Name . "_Id",
            $table01Description,
            $this->dataTypeMap["Long"],
            null,
            $table01AllowNull,
            undefined
        );

        $columnFK[] = $this->generateInstructionAddColumn(
            $table02Name . "_Id",
            $table02Description,
            $this->dataTypeMap["Long"],
            null,
            $table02AllowNull,
            undefined
        );

        $strColumn = "    " . \implode(", \n    ", $columnFK);
        return \str_replace("[[columns]]", $strColumn, $strCreateTable);
    }










    /**
     * Retorna uma coleção de arrays contendo o nome e a descrição de cada uma das
     * tabelas do atual banco de dados (mesmo aquelas que não estão mapeadas).
     *
     * ``` php
     *      // O array retornado é uma coleção de entradas conforme o exemplo abaixo:
     *      $arr = [
     *          string  "tableName"         Nome da tabela.
     *          string  "tableDescription"  Descrição da tabela.
     *          int     "tableRows"         Contagem de registros na tabela.
     *          bool    "tableMapped"       Indica se a tabela está mapeada nos modelos de dados do atual schema.
     *      ];
     * ```
     *
     *
     * @return      ?array
     */
    public function listDataBaseTables() : ?array
    {
        $r = null;

        switch ($this->dbType) {
            case "mysql":
                $dbName         = $this->factory->getDAL()->getDBName();
                $strTableNames  = \implode("', '", $this->factory->getDataTableList());

                $strSQL = " SELECT
                                TABLE_NAME as tableName,
                                TABLE_COMMENT as tableDescription,
                                TABLE_ROWS as tableRows,
                                IF(TABLE_NAME IN ('$strTableNames'), '1', '0') as tableMapped
                            FROM
                                INFORMATION_SCHEMA.TABLES
                            WHERE
                                TABLE_SCHEMA=:dbName
                            ORDER BY
                                TABLE_NAME ASC;";

                $r = $this->factory->getDAL()->getDataTable($strSQL, ["dbName" => $dbName]);
                break;

            case "mssqlserver":
                break;

            case "oracle":
                break;

            case "postgree":
                break;
        }

        return $r;
    }





    /**
     * Remove completamente todo o schema atualmente existente dentro do banco de dados
     * alvo.
     *
     * @return      bool
     */
    public function executeDropSchema() : bool
    {
        $r = false;

        switch ($this->dbType) {
            case "mysql":
                // Desliga as constraints
                $this->factory->getDAL()->executeInstruction("SET FOREIGN_KEY_CHECKS = 0;");

                $allTables = $this->factory->getDAL()->getDataTable("SHOW TABLES;");
                if ($allTables !== null) {
                    foreach ($allTables as $row) {
                        $tableData = $row[\key($row)];
                        $this->factory->getDAL()->executeInstruction("DROP TABLE $tableData;");
                    }
                }

                // Religa as constraints
                $this->factory->getDAL()->executeInstruction("SET FOREIGN_KEY_CHECKS = 1;");

                $allTables = $this->factory->getDAL()->getDataTable("SHOW TABLES;");
                $r = ($allTables === null);
                break;

            case "mssqlserver":
                break;

            case "oracle":
                break;

            case "postgree":
                break;
        }

        return $r;
    }





    /**
     * Retorna uma coleção de arrays contendo o nome, tipo e a descrição de cada uma das
     * colunas da tabela indicada.
     *
     * ``` php
     *      // O array retornado é uma coleção de entradas conforme o exemplo abaixo:
     *      $arr = [
     *          bool    "columnPrimaryKey"      Indica se a coluna é uma chave primária.
     *          bool    "columnUniqueKey"       Indica se a coluna é do tipo "unique".
     *          string  "columnName"            Nome da coluna.
     *          string  "columnDescription"     Descrição da coluna.
     *          string  "columnDataType"        Tipo de dados da coluna.
     *          bool    "columnAllowNull"       Indica se a coluna pode assumir NULL como valor.
     *          string  "columnDefaultValue"    Valor padrão para a coluna.
     *      ];
     * ```
     *
     * @param       string $tableName
     *              Nome da tabela de dados alvo.
     *
     * @return      ?array
     */
    public function listTableColumns(string $tableName) : ?array
    {
        $r = null;

        switch ($this->dbType) {
            case "mysql":
                $dbName = $this->factory->getDAL()->getDBName();
                $strSQL = " SELECT
                                IF(COLUMN_KEY='PRI', 1, 0) as columnPrimaryKey,
                                IF(COLUMN_KEY='UNI', 1, 0) as columnUniqueKey,
                                COLUMN_NAME as columnName,
                                COLUMN_COMMENT as columnDescription,
                                COLUMN_TYPE as columnDataType,
                                IF(IS_NULLABLE='YES', '1', '0') as columnAllowNull,
                                COLUMN_DEFAULT as columnDefaultValue
                            FROM
                                INFORMATION_SCHEMA.COLUMNS
                            WHERE
                                TABLE_SCHEMA=:dbName AND
                                TABLE_NAME=:tableName
                            ORDER BY
                                ORDINAL_POSITION ASC;";
                $r = $this->factory->getDAL()->getDataTable($strSQL, ["dbName" => $dbName, "tableName" => $tableName]);
                break;

            case "mssqlserver":
                break;

            case "oracle":
                break;

            case "postgree":
                break;
        }

        return $r;
    }





    /**
     * Retorna um array associativo contendo a coleção de ``constraints`` definidas
     * atualmente no banco de dados.
     *
     * ``` php
     *      // O array retornado é uma coleção de entradas conforme o exemplo abaixo:
     *      $arr = [
     *          string "tableName"              Nome da tabela de dados na qual a regra está vinculada.
     *          string "columnName"             Nome da coluna de dados alvo da regra.
     *          string "constraintName"         Nome da "constraint".
     *          string "constraintType"         Tipo de regra. ["PRIMARY KEY", "FOREIGN KEY", "UNIQUE"]
     *          int    "constraintCardinality"  Cardinalidade da aplicação da regra.
     *      ];
     * ```
     *
     * @param       ?string $tableName
     *              Se for definido, deverá retornar apenas os registros relacionados
     *              com a tabela alvo.
     *
     * @return      ?array
     */
    public function listSchemaConstraint(?string $tableName = null) : ?array
    {
        $r = null;
        $dbName = $this->factory->getDAL()->getDBName();

        switch ($this->dbType) {
            case "mysql":
                $strInTable = (($tableName === null) ? "" : "AND iss.TABLE_NAME='$tableName'");

                $strSQL = "SELECT
                                iss.TABLE_NAME as tableName,
                                iss.COLUMN_NAME as columnName,
                                iss.INDEX_NAME as constraintName,
                                COALESCE(istc.CONSTRAINT_TYPE, 'INDEX') AS constraintType,
                                iss.CARDINALITY as constraintCardinality
                            FROM
                                INFORMATION_SCHEMA.STATISTICS iss
                                LEFT JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS istc ON istc.CONSTRAINT_NAME=iss.INDEX_NAME
                            WHERE
                                iss.TABLE_SCHEMA='$dbName'
                                $strInTable
                            GROUP BY
                                iss.TABLE_NAME,
                                iss.COLUMN_NAME,
                                iss.INDEX_NAME,
                                istc.CONSTRAINT_TYPE
                            ORDER BY
                                iss.TABLE_NAME ASC,
                                iss.COLUMN_NAME ASC,
                                iss.INDEX_NAME ASC;";

                $r = $this->factory->getDAL()->getDataTable($strSQL);
                break;

            case "mssqlserver":
                break;

            case "oracle":
                break;

            case "postgree":
                break;
        }

        return $r;
    }





    /**
     * Executa o script de criação do schema gerado por último pela função
     * ``generateCreateSchemaFiles``.
     *
     * @param       bool $dropSchema
     *              Quando ``true`` irá excluir totalmente todas as tabelas de dados
     *              existentes no banco de dados alvo e então recriar o schema.
     *
     * @return      bool
     */
    public function executeCreateSchema(bool $dropSchema = false) : bool
    {
        $tgtSchemaFilePath = $this->factory->getProjectDirectory() . DS . "_projectSchema.sql";

        if (\file_exists($tgtSchemaFilePath) === false) {
            $this->generateCreateSchemaFiles();
        }

        if ($dropSchema === true) {
            $this->executeDropSchema();
        }

        $strIniCreate   = "/*--INI CREATE TABLE--*/";
        $strEndCreate   = "/*--END CREATE TABLE--*/";
        $strIniConstr   = "/*--INI CONSTRAINT INSTRUCTIONS--*/";
        $strEndConstr   = "/*--END CONSTRAINT INSTRUCTIONS--*/";

        $instructionFile    = \file_get_contents($tgtSchemaFilePath);
        $instructionLines   = \explode("\n", $instructionFile);
        $instructions       = [];

        $ct = [];
        $insertIn = null;
        foreach ($instructionLines as $i => $line) {
            if ($line === $strIniCreate) {
                $insertIn = "createTable";
            } elseif ($line === $strEndCreate) {
                $insertIn = null;
                $instructions[] = \implode("", $ct);
                $ct = [];
            } elseif ($line === $strIniConstr) {
                $insertIn = "alterTable";
            } elseif ($line === $strEndConstr) {
                $insertIn = null;
            }



            switch ($insertIn) {
                case "createTable":
                    $ct[] = $line;
                    break;

                case "alterTable":
                    $instructions[] = $line;
                    break;
            }
        }


        $r = true;
        foreach ($instructions as $strSQL) {
            if ($r === true) {
                $r = $this->factory->getDAL()->executeInstruction($strSQL);
            }
        }

        return $r;
    }










    /**
     * Inicia uma instância de um Schema para lidar com os modelos de dados definidos
     * para o objeto ``iDataTableFactory`` passado.
     *
     * @param       iDataTableFactory $factory
     *              Instância de uma fábrica de objetos ``iTable`` para o projeto que
     *              está sendo usado.
     *
     * @throws      \Exception
     *              Caso não seja possível criar algum dos diretórios do projeto.
     */
    function __construct(iDataTableFactory $factory)
    {
        $this->factory      = $factory;
        $this->dbType       = $this->factory->getDAL()->getDBType();
        $this->dataTypeMap  = $this->dataTypeMap[$this->dbType];
    }
}
