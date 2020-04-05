<?php
declare (strict_types=1);

namespace AeonDigital\ORM;

use AeonDigital\Interfaces\DAL\iDAL as iDAL;
use AeonDigital\Interfaces\ORM\iDataTableFactory as iDataTableFactory;
use AeonDigital\Interfaces\DataModel\iModel as iModel;
use AeonDigital\Interfaces\ORM\iTable as iTable;
use AeonDigital\ORM\DataColumn as DataColumn;
use AeonDigital\ORM\DataColumnFK as DataColumnFK;
use AeonDigital\ORM\DataColumnFKCollection as DataColumnFKCollection;
use AeonDigital\ORM\DataTable as DataTable;






/**
 * Fábrica de tabelas de dados para um dado projeto.
 *
 * @package     AeonDigital\ORM
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2020, Rianna Cantarelli
 * @license     MIT
 */
class DataTableFactory implements iDataTableFactory
{





    /**
     * Objeto de conexão com o banco de dados alvo.
     *
     * @var         iDAL
     */
    private ?iDAL $DAL = null;
    /**
     * Retorna o objeto ``DAL`` que está sendo usado por esta instância.
     *
     * @return      iDAL
     */
    public function getDAL() : iDAL
    {
        return $this->DAL;
    }





    /**
     * Nome do projeto que está sendo usado.
     * É sempre igual ao nome do banco de dados.
     *
     * @var         string
     */
    private string $projectName = "";
    /**
     * Nome do projeto.
     * Geralmente é o mesmo nome do banco de dados definido na instância ``iDAL`` usada.
     *
     * @return      string
     */
    public function getProjectName() : string
    {
        return $this->projectName;
    }





    /**
     * Caminho completo até o diretório onde estão os arquivos que descrevem os
     * modelos de dados utilizado por este projeto.
     *
     * @var         string
     */
    private string $projectDirectory = "";
    /**
     * Retorna o caminho completo até o diretório onde estão os arquivos que descrevem os
     * modelos de dados utilizado por este projeto.
     *
     * Dentro do mesmo diretório deve haver um outro chamado ``enum`` contendo os
     * enumeradores usados pelo projeto.
     *
     * @return      string
     */
    public function getProjectDirectory() : string
    {
        return $this->projectDirectory;
    }





    /**
     * Cria um arquivo ``_projectData.php`` no diretório principal do projeto.
     * Este arquivo armazenará um array associativo contendo o nome das tabelas de dados
     * usadas no projeto e seus respectivos arquivos de configuração.
     *
     * Caso o arquivo já exista, será substituído por um novo.
     *
     * @return      void
     */
    public function recreateProjectDataFile() : void
    {
        $tgtFile = $this->projectDirectory . DS . "_projectData.php";
        $projectDataFile = [];


        $dirContent = \scandir($this->projectDirectory);
        $tableNames = [];
        $tableAlias = [];


        foreach($dirContent as $originalFileName) {
            if (\in_array($originalFileName, [".", ".."]) === false &&
                \mb_str_starts_with($originalFileName, "_") === false &&
                \mb_str_ends_with($originalFileName, ".php") === true)
            {
                $completePath   = \to_system_path($this->projectDirectory . DS . $originalFileName);
                $tmpData        = include $completePath;

                if (\array_is_assoc($tmpData) === true && isset($tmpData["tableName"]) === true)
                {
                    $tableName      = $tmpData["tableName"];
                    $useAlias       = $tmpData["alias"];

                    if (\in_array($tableName, $tableNames) === true) {
                        $msg = "Duplicated table name [\"$tableName\"].";
                        throw new \InvalidArgumentException($msg);
                    }
                    if (\in_array($useAlias, $tableAlias) === true) {
                        $msg = "Duplicated table alias [\"$useAlias\"].";
                        throw new \InvalidArgumentException($msg);
                    }

                    $tableNames[] = $tableName;
                    $tableAlias[] = $useAlias;

                    $projectDataFile[$tableName] = [
                        "modelFilePath"             => $originalFileName,
                        "ormInstructions"           => [
                            "select"            => null,
                            "selectChild"       => null,
                            "selectParentId"    => [],
                            "attatchWith"       => [],
                            "detachWith"        => [],
                            "detachWithAll"     => [],
                            "oColumn"           => [],
                            "singleFK"          => [],
                            "collectionFK"      => []
                        ]
                    ];
                }
            }
        }



        // Para cada tabela de dados no projeto
        $this->projectDataFile = $projectDataFile;
        foreach ($projectDataFile as $tableName => $tableData) {
            $oTable         = $this->createDataTable($tableName);
            $columnNames    = $oTable->getFieldNames();

            $selectChild    = [];
            $selectColumns  = [];


            foreach ($columnNames as $cName) {
                $field = $oTable->{"_".$cName};
                $sc = [
                    "select"            => null,
                    "oColumnFK"         => null,
                    "linkTableName"     => null,
                    "linkTableColumns"  => null
                ];


                if ($field->isReference() === false) {
                    $selectColumns[] = $cName;
                } else {
                    $fkTableName = $field->getModelName();

                    // Se for uma relação 1-1
                    if ($field->isCollection() === false) {
                        $fkName = $fkTableName . "_Id";
                        $sc["select"]   = "SELECT $fkName as fkId FROM $tableName WHERE Id=:Id;";


                        // Define as instruções para anexar e desanexar vínculos 1-1
                        // a partir do proprietário (objeto que possui o outro).
                        $strSQLAttatch      = "UPDATE $tableName SET $fkName=:tgtId WHERE Id=:thisId;";
                        $strSQLDetach       = "UPDATE $tableName SET $fkName=null WHERE Id=:thisId;";
                        $strSQLDetachAll    = "UPDATE $tableName SET $fkName=null WHERE Id=:thisId;";
                        $projectDataFile[$tableName]["ormInstructions"]["attatchWith"][$fkTableName]    = $strSQLAttatch;
                        $projectDataFile[$tableName]["ormInstructions"]["detachWith"][$fkTableName]     = $strSQLDetach;
                        $projectDataFile[$tableName]["ormInstructions"]["detachWithAll"][$fkTableName]  = $strSQLDetachAll;


                        // Define as instruções para anexar e desanexar vínculos 1-1
                        // no lado do objeto possuído.
                        $strSQLParentId     = "SELECT Id FROM $tableName WHERE $fkName=:thisId;";
                        $strSQLAttatch      = "UPDATE $tableName SET $fkName=:thisId WHERE Id=:tgtId;";
                        $strSQLDetach       = "UPDATE $tableName SET $fkName=null WHERE Id=:tgtId;";
                        $strSQLDetachAll    = "UPDATE $tableName SET $fkName=null WHERE $fkName=:thisId;";
                        $projectDataFile[$fkTableName]["ormInstructions"]["selectParentId"][$tableName] = $strSQLParentId;
                        $projectDataFile[$fkTableName]["ormInstructions"]["attatchWith"][$tableName]    = $strSQLAttatch;
                        $projectDataFile[$fkTableName]["ormInstructions"]["detachWith"][$tableName]     = $strSQLDetach;
                        $projectDataFile[$fkTableName]["ormInstructions"]["detachWithAll"][$tableName]  = $strSQLDetachAll;
                    }
                    else {
                        // Se for uma relação 1-N
                        if ($field->isFKLinkTable() === false) {
                            $fkName = $tableName . "_Id";
                            $sc["select"]   = "SELECT Id as fkId FROM $fkTableName WHERE $fkName=:Id;";


                            // Define as instruções para anexar e desanexar vínculos 1-N
                            // a partir do proprietário (objeto que possui o outro).
                            $strSQLAttatch      = "UPDATE $fkTableName SET $fkName=:thisId WHERE Id=:tgtId;";
                            $strSQLDetach       = "UPDATE $fkTableName SET $fkName=null WHERE Id=:tgtId;";
                            $strSQLDetachAll    = "UPDATE $fkTableName SET $fkName=null WHERE $fkName=:thisId;";
                            $projectDataFile[$tableName]["ormInstructions"]["attatchWith"][$fkTableName]    = $strSQLAttatch;
                            $projectDataFile[$tableName]["ormInstructions"]["detachWith"][$fkTableName]     = $strSQLDetach;
                            $projectDataFile[$tableName]["ormInstructions"]["detachWithAll"][$fkTableName]  = $strSQLDetachAll;

                            // Define as instruções para anexar e desanexar vínculos 1-N
                            // no lado do objeto possuído.
                            $strSQLParentId     = "SELECT $fkName FROM $fkTableName WHERE Id=:thisId;";
                            $strSQLAttatch      = "UPDATE $fkTableName SET $fkName=:tgtId WHERE Id=:thisId;";
                            $strSQLDetach       = "UPDATE $fkTableName SET $fkName=null WHERE Id=:thisId;";
                            $strSQLDetachAll    = "UPDATE $fkTableName SET $fkName=null WHERE Id=:thisId;";
                            $projectDataFile[$fkTableName]["ormInstructions"]["selectParentId"][$tableName] = $strSQLParentId;
                            $projectDataFile[$fkTableName]["ormInstructions"]["attatchWith"][$tableName]    = $strSQLAttatch;
                            $projectDataFile[$fkTableName]["ormInstructions"]["detachWith"][$tableName]     = $strSQLDetach;
                            $projectDataFile[$fkTableName]["ormInstructions"]["detachWithAll"][$tableName]  = $strSQLDetachAll;
                        }
                        // Sendo uma relação N-N
                        else {
                            $oTableFk = $this->createDataTable($fkTableName);

                            $arrTables = [$oTable->getAlias(), $oTableFk->getAlias()];
                            \rsort($arrTables);
                            $linkTableName  = \implode("_to_", $arrTables);

                            $pkName = $tableName . "_Id";
                            $fkName = $fkTableName . "_Id";

                            $sc["select"]               = "SELECT $fkName as fkId FROM $linkTableName WHERE $pkName=:Id;";
                            $sc["linkTableName"]        = $linkTableName;
                            $sc["linkTableColumns"]     = [$pkName, $fkName];


                            // Define as instruções para anexar e desanexar vínculos N-N
                            // a partir do proprietário (objeto que possui o outro).
                            $strSQLAttatch      = "INSERT INTO $linkTableName ($pkName, $fkName) VALUES (:thisId, :tgtId);";
                            $strSQLDetach       = "DELETE FROM $linkTableName WHERE $pkName=:thisId AND $fkName=:tgtId;";
                            $strSQLDetachAll    = "DELETE FROM $linkTableName WHERE $pkName=:thisId;";
                            $projectDataFile[$tableName]["ormInstructions"]["attatchWith"][$fkTableName]    = $strSQLAttatch;
                            $projectDataFile[$tableName]["ormInstructions"]["detachWith"][$fkTableName]     = $strSQLDetach;
                            $projectDataFile[$tableName]["ormInstructions"]["detachWithAll"][$fkTableName]  = $strSQLDetachAll;
                        }
                    }

                    $selectChild[$cName] = $sc;
                }
            }


            // Preenche os dados
            $strColumns = \implode(", ", $selectColumns);
            $projectDataFile[$tableName]["ormInstructions"]["select"]       = "SELECT $strColumns FROM $tableName WHERE Id=:Id;";
            $projectDataFile[$tableName]["ormInstructions"]["selectChild"]  = $selectChild;
        }


        $projectData = "<?php return " . \var_export($projectDataFile, true) . ";";
        \file_put_contents($tgtFile, $projectData);
        $this->projectDataFile = $projectDataFile;
    }





    /**
     * Array associativo trazendo nas chaves o nome de cada uma das tabelas de dados do projeto,
     * e em seus respectivos valores o nome do arquivo que armazena suas configurações.
     *
     * @var         array
     */
    private array $projectDataFile = [];
    /**
     * Retorna um array com a lista de todas as tabelas de dados existêntes neste projeto.
     *
     * @return      array
     */
    public function getDataTableList() : array
    {
        return \array_keys($this->projectDataFile);
    }





    /**
     * Identifica se esta fábrica pode fornecer um objeto compatível com o nome do Identificador
     * passado.
     *
     * @param       string $idName
     *              Identificador único do modelo de dados dentro do escopo definido.
     *
     * @return      bool
     */
    public function hasDataModel(string $idName) : bool
    {
        return (\key_exists($idName, $this->projectDataFile) === true);
    }



    /**
     * Identifica se no atual projeto existe uma tabela de dados com o nome passado.
     *
     * @param       string $tableName
     *              Nome da tabela de dados.
     *
     * @return      bool
     */
    public function hasDataTable(string $tableName) : bool
    {
        return $this->hasDataModel($tableName);
    }



    /**
     * Array associativo que vai sendo incrementado conforme novas tabelas de dados vão sendo
     * carregadas evitando assim o reprocessamento de modelos de dados já carregados.
     *
     * @var         array
     */
    private array $projectRawDataTables = [];
    /**
     * Retorna um objeto ``iModel`` com as configurações equivalentes ao identificador único
     * do mesmo.
     *
     * @param       string $idName
     *              Identificador único do modelo de dados dentro do escopo definido.
     *
     * @param       mixed $initialValues
     *              Coleção de valores a serem setados para a nova instância que será retornada.
     *
     * @return      iModel
     *
     * @throws      \InvalidArgumentException
     *              Caso o nome da tabela seja inexistente.
     */
    public function createDataModel(string $idName, $initialValues = null) : iModel
    {
        if ($this->hasDataTable($idName) === false) {
            $msg = "The given data table name does not exist in this project [\"$idName\"].";
            throw new \InvalidArgumentException($msg);
        } else {

            // Se a tabela referenciada ainda não foi carregada na memória...
            if (isset($this->projectRawDataTables[$idName]) === false) {
                $modelConfig                = include $this->getProjectDirectory() . DS . $this->projectDataFile[$idName]["modelFilePath"];
                $useDescription             = ((isset($modelConfig["description"]) === true)                ? $modelConfig["description"]               : null);
                $executeAfterCreateTable    = ((isset($modelConfig["executeAfterCreateTable"]) === true)    ? $modelConfig["executeAfterCreateTable"]   : null);
                $rawColumns                 = $modelConfig["columns"];

                $this->projectRawDataTables[$idName] = [
                    "tableName"                 => $idName,
                    "alias"                     => $modelConfig["alias"],
                    "description"               => $useDescription,
                    "executeAfterCreateTable"   => $executeAfterCreateTable,
                    "columns"                   => $rawColumns,
                    "ormInstructions"           => $this->projectDataFile[$idName]["ormInstructions"]
                ];
            }

            $newInstance    = $this->projectRawDataTables[$idName];
            $rawColumns     = $this->projectRawDataTables[$idName]["columns"];

            // Cria chave primária
            $useColumns[] = new DataColumn([
                "name"              => "Id",
                "type"              => "Long",
                "allowNull"         => false,
                "autoIncrement"     => true,
                "primaryKey"        => true,
                "default"           => 0
            ]);

            // Monta as colunas de dados simples
            foreach ($rawColumns as $columnRules) {
                if (isset($columnRules["fkTableName"]) === true) {
                    $isCollection = (\strpos($columnRules["fkTableName"], "[]") !== false);
                    if ($isCollection === true) {
                        $columnRules["fkTableName"] = \str_replace("[]", "", $columnRules["fkTableName"]);
                        $useColumns[] = new DataColumnFKCollection($columnRules, $this);
                    } else {
                        $useColumns[] = new DataColumnFK($columnRules, $this);
                    }
                } else {
                    // Ajusta o nome dos enumeradores para que conste o caminho
                    // correto até os arquivos alvos.
                    if (isset($columnRules["enumerator"]) === true) {
                        $columnRules["enumerator"] = \to_system_path($this->projectDirectory . DS . $columnRules["enumerator"]);
                    }
                    $useColumns[] = new DataColumn($columnRules);
                }
            }

            // Retorna uma nova instância da tabela de dados alvo
            $newInstance["columns"] = $useColumns;
            $objTable = new DataTable($newInstance);
            $objTable->setDAL($this->DAL);
            return  $objTable;
        }
    }



    /**
     * Retorna uma tabela de dados correspondente ao nome informado no argumento ``$tableName``.
     *
     * @param       string $tableName
     *              Nome da tabela de dados.
     *
     * @param       mixed $initialValues
     *              Coleção de valores a serem setados para a nova instância que será retornada.
     *
     * @return      iTable
     *
     * @throws      \InvalidArgumentException
     *              Caso o nome da tabela seja inexistente.
     */
    public function createDataTable(string $tableName, $initialValues = null) : iTable
    {
        return $this->createDataModel($tableName, $initialValues);
    }










    /**
     * Inicia uma fábrica de DataTables para o projeto.
     *
     * @param       string $projectDirectory
     *              Caminho completo até o local onde devem ser definidos os modelos de dados das
     *              tabelas do projeto.
     *
     * @param       iDAL $DAL
     *              Conexão que permite a manipulação do banco de dados alvo.
     *
     * @throws      \InvalidArgumentException
     *              Caso algum valor passado não seja válido.
     *
     * @throws      \Exception
     *              Caso não existam modelos de dados a serem carregados.
     */
    function __construct(
        string $projectDirectory,
        iDAL $DAL
    ) {
        $projectDirectory   = \to_system_path($projectDirectory);

        if (\is_dir($projectDirectory) === false) {
            $msg = "Invalid given path to data models [\"$projectDirectory\"].";
            throw new \InvalidArgumentException($msg);
        } else {
            $this->DAL                  = $DAL;
            $this->projectName          = $DAL->getDBName();
            $this->projectDirectory     = $projectDirectory;


            $projectDataFile = $projectDirectory . DS . "_projectData.php";
            if (\file_exists($projectDataFile) === true) {
                $this->projectDataFile = include $projectDataFile;
            } else {
                $this->recreateProjectDataFile();
            }
        }
    }
}
