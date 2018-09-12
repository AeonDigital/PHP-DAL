<?php
declare (strict_types = 1);

namespace AeonDigital\DAL;

use AeonDigital\Numbers\RealNumber as RealNumber;
use AeonDigital\DAL\Interfaces\iDAL as iDAL;







/**
 * Classe que permite o acesso a um banco de dados utilizando
 * o PDO do PHP.
 * 
 * @package     AeonDigital\DAL
 * @version     0.9.0 [alpha]
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   GNUv3
 */
class DAL implements iDAL
{





    /**
     * Conexão com o banco de dados.
     * 
     * @type        ?PDO
     */
    private $dbConnection = null;
    /**
     * Retorna um objeto clone do 
     * "dbConnection" desta instância.
     *
     * @return       PDO
     */
    public function getCloneConnection() : \PDO
    {
        return clone $this->dbConnection;
    }





    /**
     * Objeto que carrega uma instrução SQL a ser
     * executada conforme os parametros indicados.
     * 
     * @type        PDOStatement
     */
    private $dbPreparedStatment = null;





    /**
     * Tipo do banco de dados utilizado.
     * Suporta os tipos : "mysql", "mssqlserver", "oracle", "postgree".
     * 
     * @type        string
     */
    private $dbType = null;
    /**
     * Retorna o tipo do banco de dados utilizado.
     *
     * @return      string
     */
    public function getDBType() : string
    { 
        return $this->dbType; 
    }


    /**
     * Host da conexão com o banco de dados.
     *
     * @var         string
     */
    private $dbHost = null;
    /**
     * Retorna o host da conexão com o banco de dados.
     *
     * @return      string
     */
    public function getDBHost() : string
    { 
        return $this->dbHost; 
    }


    /**
     * Nome do banco de dados que esta conexão 
     * está apta a acessar.
     * 
     * @type        string
     */
    private $dbName = null;
    /**
     * Retorna o nome do banco de dados que esta conexão 
     * está apta a acessar.
     *
     * @return      string
     */
    public function getDBName() : string
    { 
        return $this->dbName; 
    }










    /**
     * Substitui a conexão desta instância pela
     * do objeto passado.
     *
     * @param       iDAL $oConnection
     *              Objeto que contêm a conexão que passará a ser
     *              usada por esta instância.
     * 
     * @return      void
     * 
     * @codeCoverageIgnore
     */
    public function replaceConnection(iDAL $oConnection) : void
    {
        $this->dbConnection         = $oConnection->getCloneConnection();
        $this->dbPreparedStatment   = null;
        $this->dbType               = $oConnection->getDBType();
        $this->dbHost               = $oConnection->getDBHost();
        $this->dbName               = $oConnection->getDBName();
    }










    /**
     * Prepara e executa um comando SQL.
     *
     * @param       string $strSQL
     *              Instrução a ser executada.
     * 
     * @param       ?array $parans
     *              Array associativo contendo as chaves e respectivos
     *              valores que serão substituídos na instrução SQL.
     * 
     * @return      bool
     */
    public function executeInstruction(string $strSQL, ?array $parans = null) : bool
    {
        $this->dbPreparedStatment = $this->dbConnection->prepare($strSQL);
        $this->pdoLastError = null;


        if($parans !== null) {
            foreach($parans as $key => $value) {
                $val = $value;

                // Trata dados de tipos especiais
                if(is_bool($value)) { 
                    if($value === true) { $val = 1; }
                    else { $val = 0; }
                }
                else if(is_a($value, "\DateTime")) {
                    $val = $value->format("Y-m-d H:i:s");
                }

                $this->dbPreparedStatment->bindValue(":" . $key, $val);
            }
        }


        try {
            $this->dbPreparedStatment->execute();
        } catch (\Exception $ex) {
            $this->pdoLastError = $ex->getMessage();
        }

        return $this->isExecuted();
    }


    /**
     * Executa uma instrução SQL e retorna os dados obtidos.
     *
     * @param       string $strSQL
     *              Instrução a ser executada.
     * 
     * @param       ?array $parans
     *              Array associativo contendo as chaves e respectivos
     *              valores que serão substituídos na instrução SQL.
     * 
     * @return      ?array
     */
    public function getDataTable(string $strSQL, ?array $parans = null) : ?array
    {
        $r = $this->executeInstruction($strSQL, $parans);
        $dataTable = [];
        if ($r === true) {
            $dataTable = $this->dbPreparedStatment->fetchAll(\PDO::FETCH_ASSOC);
        }
        return (($dataTable === []) ? null : $dataTable);
    }


    /**
     * Executa uma instrução SQL e retorna apenas a primeira linha
     * de dados obtidos.
     *
     * @param       string $strSQL
     *              Instrução a ser executada.
     * 
     * @param       ?array $parans
     *              Array associativo contendo as chaves e respectivos
     *              valores que serão substituídos na instrução SQL.
     * 
     * @return      ?array
     */
    public function getDataRow(string $strSQL, ?array $parans = null) : ?array
    {
        $dataRow = $this->getDataTable($strSQL, $parans);
        if ($dataRow !== null) {
            $dataRow = $dataRow[0];
        }
        return $dataRow;
    }


    /**
     * Executa uma instrução SQL e retorna apenas a coluna da primeira linha
     * de dados obtidos.
     * O valor "null" será retornado caso a consulta não traga resultados.
     *
     * @param       string $strSQL
     *              Instrução a ser executada.
     * 
     * @param       ?array $parans
     *              Array associativo contendo as chaves e respectivos
     *              valores que serão substituídos na instrução SQL.
     * 
     * @param       string $castTo
     *              Indica o tipo que o valor resgatado deve ser retornado
     *              Esperado: "bool", "int", "float", "real", "datetime", "string".
     * 
     * @return      ?mixed
     */
    function getDataColumn(string $strSQL, ?array $parans = null, string $castTo = "string")
    {
        $r = null;
        $dataRow = $this->getDataRow($strSQL, $parans);
        
        if ($dataRow !== null) {
            $r = $dataRow[key($dataRow)];

            // @codeCoverageIgnoreStart  
            if($r !== null) {
                switch(strtolower($castTo)) {
                    case "bool":
                    case "boolean":
                        $r = (bool)$r;
                        break;

                    case "int":
                    case "integer":
                        $r = (int)$r;
                        break;

                    case "float":
                    case "double":
                        $r = (float)$r;
                        break;

                    case "real":
                    case "decimal":
                        $r = new RealNumber($r);

                    case "datetime":
                        $r = \DateTime::createFromFormat("Y-m-d H:i:s", $r);
                        break;

                    case "string":
                        $r = (string)$r;
                        break;
                }
            }
            // @codeCoverageIgnoreEnd
        }

        return $r;
    }


    /**
     * Efetua uma consulta SQL do tipo "COUNT" e retorna seu resultado.
     * A consulta passada deve sempre trazer o resultado da contagem em 
     * um "alias" chamado "count".
     * 
     * @example
     * > SELECT COUNT(id) as count FROM TargetTable WHERE column=:column;
     *
     * @param       string $strSQL
     *              Instrução a ser executada.
     * 
     * @param       ?array $parans
     *              Array associativo contendo as chaves e respectivos
     *              valores que serão substituídos na instrução SQL.
     * 
     * @return      int
     * 
     * @codeCoverageIgnore
     */
    public function getCountOf(string $strSQL, ?array $parans = null) : int
    {
        $r = $this->getDataColumn($strSQL, $parans, "int");
        return (($r === null) ? 0 : $r);
    }


    /**
     * Indica se a última instrução foi corretamente
     * executada.
     *
     * @return      bool
     */
    public function isExecuted() : bool
    {
        $err = $this->dbConnection->errorInfo();
        return ($err[0] === "00000" && $this->pdoLastError === null);
    }


    /**
     * Retorna a quantidade de linhas afetadas pela
     * última instrução SQL executada ou a quantidade
     * de linhas retornadas pela mesma
     *
     * @return      int
     */
    public function countAffectedRows() : int
    {
        return $this->dbPreparedStatment->rowCount();
    }


    /**
     * Armazena o último erro ocorrido após uma instrução
     * SQL ter falhado.
     *
     * @var         ?string
     */
    private $pdoLastError = null;
    /**
     * Retorna a mensagem de erro referente a última instrução 
     * SQL executada. Não havendo erro, retorna NULL.
     *
     * @return      ?string
     */
    public function getLastError() : ?string
    {
        $err = $this->dbConnection->errorInfo();
        $err = $err[2];
        return ($err === null) ? $this->pdoLastError : $err[2];
    }


    /**
     * Retorna o último ID inserido na última instrução.
     *
     * @return      ?int
     */
    public function getLastInsertId() : ?int
    {
        return (($this->isExecuted() === true) ? (int)$this->dbConnection->lastInsertId() : null);
    }










    /**
     * Indica se o modo de transação está aberto.
     *
     * @return      bool
     */
    public function inTransaction() : bool
    {
        return $this->dbConnection->inTransaction();
    }


    /**
     * Inicia o modo de transação, dando ao desenvolvedor
     * a responsabilidade de efetuar o commit ou rollback conforme
     * a necessidade.
     *
     * @return      bool
     */
    public function beginTransaction() : bool
    {
        return $this->dbConnection->beginTransaction();
    }


    /**
     * Efetiva as transações realizadas desde que o modo 
     * de transação foi aberto.
     *
     * @return      bool
     */
    public function commit() : bool
    {
        return $this->dbConnection->commit();
    }


    /**
     * Efetua o rollback das transações feitas desde que o 
     * modo de transação foi aberto.
     *
     * @return      bool
     */
    public function rollBack() : bool
    {
        return $this->dbConnection->rollBack();
    }










    /**
     * Inicia uma nova instância de conexão com um banco de dados.
     *
     * @param       string $dbType
     *              Tipo do banco de dados.
     *              Esperao um dos tipos: "mysql", "mssqlserver", "oracle", "postgree".
     * 
     * @param       string $dbHost
     *              Host da conexão com o banco de dados.
     * 
     * @param       string $dbName
     *              Nome da base de dados à qual a conexão será feita.
     * 
     * @param       string $dbUserName
     *              Credencial "user" para a efetuar a conexão.
     *              
     * @param       string $dbUserPassword
     *              Credencial "password" para efetuar a conexão.
     * 
     * @param       ?string $dbConnectionString
     *              String de conexão a ser usada.
     *              Se não for definida, usará as regras internas para contruir
     *              uma.
     * 
     * @param       ?iConnection $oConnection
     *              Instância de um objeto que terá sua conexão compartilhada
     *              com a nova instância que está sendo criada.
     *
     * @throws      \InvalidArgumentException
     *              Caso algum valor passado não seja válido.
     */
    function __construct(
        string $dbType,
        string $dbHost,
        string $dbName, 
        string $dbUserName,
        string $dbUserPassword,
        ?string $dbConnectionString = null,
        ?iConnection $oConnection = null
    ) {
        if ($oConnection === null) {
            $allowDbTypes = ["mysql", "mssqlserver", "oracle", "postgree"];
            if (in_array($dbType, $allowDbTypes) === false) {
                $msg = "Invalid DataBase Type [\"$dbType\"].";
                throw new \InvalidArgumentException($msg);
            }

            $this->dbType = $dbType;
            $this->dbName = $dbName;
            $this->dbHost = $dbHost;
            $initialInstructions = [];

            switch ($dbType) {
                case "mysql" :
                    $initialInstructions[] = "SET NAMES utf8;";
                    $initialInstructions[] = "USE $dbName;";

                    if ($dbConnectionString === null) {
                        $dbConnectionString = "mysql:host=$dbHost;dbname=$dbName;charset=utf8";
                    }
                    break;
            }

            $this->dbConnection = new \PDO($dbConnectionString, $dbUserName, $dbUserPassword);
            $this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            foreach ($initialInstructions as $sttm) {
                $this->dbConnection->exec($sttm);
            }
        } else {
            $this->replaceConnection($oConnection);
        }
    }
}
?>