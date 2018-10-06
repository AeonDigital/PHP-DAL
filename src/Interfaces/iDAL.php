<?php
declare (strict_types = 1);

namespace AeonDigital\DAL\Interfaces;










/**
 * Interface básica para conexões.
 * 
 * @package     AeonDigital\DAL
 * @version     0.9.0 [alpha]
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   GNUv3
 */
interface iDAL
{





    /**
     * Retorna um objeto clone do 
     * "dbConnection" desta instância.
     *
     * @return       PDO
     */
    function getCloneConnection() : \PDO;





    /**
     * Retorna o tipo do banco de dados utilizado.
     *
     * @return      string
     */
    function getDBType() : string;


    /**
     * Retorna o host da conexão com o banco de dados.
     *
     * @return      string
     */
    function getDBHost() : string;


    /**
     * Retorna o nome do banco de dados que esta conexão 
     * está apta a acessar.
     *
     * @return      string
     */
    function getDBName() : string;





    /**
     * Substitui a conexão desta instância pela
     * do objeto passado.
     *
     * @param       iDAL $oConnection
     *              Objeto que contêm a conexão que passará a ser
     *              usada por esta instância.
     * 
     * @return      void
     */
    function replaceConnection(iDAL $oConnection) : void;





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
    function executeInstruction(string $strSQL, ?array $parans = null) : bool;


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
    function getDataTable(string $strSQL, ?array $parans = null) : ?array;


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
    function getDataRow(string $strSQL, ?array $parans = null) : ?array;


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
    function getDataColumn(string $strSQL, ?array $parans = null, string $castTo = "string");


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
     */
    function getCountOf(string $strSQL, ?array $parans = null) : int;





    /**
     * Indica se a última instrução foi corretamente
     * executada.
     *
     * @return      bool
     */
    function isExecuted() : bool;


    /**
     * Retorna a quantidade de linhas afetadas pela
     * última instrução SQL executada ou a quantidade
     * de linhas retornadas pela mesma
     *
     * @return      int
     */
    function countAffectedRows() : int;


    /**
     * Retorna a mensagem de erro referente a última instrução 
     * SQL executada. Não havendo erro, retorna NULL.
     *
     * @return      ?string
     */
    function getLastError() : ?string;





    /**
     * Retorna o último valor definido para o último registro
     * inserido na tabela de dado alvo.
     * 
     * Tem efeito sobre chaves primárias do tipo AUTO INCREMENT.
     *
     * @param       string $tableName
     *              Nome da tabela de dados.
     * 
     * @param       string $pkName
     *              Nome da chave primária a ser usada.
     *
     * @return      ?int
     */
    function getLastPK(string $tableName, string $pkName) : ?int;





    /**
     * Efetua a contagem da totalidade de registros existentes 
     * na tabela de dados indicada.
     *
     * @param       string $tableName
     *              Nome da tabela de dados.
     * 
     * @param       string $pkName
     *              Nome da chave primária da tabela.
     *
     * @return      int
     */
    function countRowsFrom(string $tableName, string $pkName) : int;


    /**
     * Efetua a contagem de registros existentes na tabela de dados indicada
     * que corresponda com o valor passado para a coluna indicada.
     *
     * @param       string $tableName
     *              Nome da tabela de dados.
     * 
     * @param       string $colName
     *              Nome da coluna a ser usada.
     * 
     * @param       mixed $colValue
     *              Valor a ser pesquisado.
     * 
     * @return      bool
     */
    function countRowsWith(string $tablename, string $colName, $colValue) : int;


    /**
     * Verifica se existe na tabela de dados indicada um ou mais registros
     * que possua na coluna indicada o valor passado.
     *
     * @param       string $tableName
     *              Nome da tabela de dados.
     * 
     * @param       string $colName
     *              Nome da coluna a ser usada.
     * 
     * @param       mixed $colValue
     *              Valor a ser pesquisado.
     * 
     * @return      bool
     */
    function hasRowsWith(string $tablename, string $colName, $colValue) : bool;


    /**
     * Efetua uma instrução "INSERT INTO" na tabela de dados alvo
     * para cada um dos itens existentes no array associativo passado.
     *
     * @param       string $tableName
     *              Nome da tabela de dados.
     * 
     * @param       array $rowData
     *              Array associativo mapeando colunas e valores a serem
     *              utilizados na intrução SQL.
     * 
     * @return      bool
     */
    function insertInto(string $tableName, array $rowData) : bool;


    /**
     * Efetua uma instrução "UPDATE SET" na tabela de dados alvo
     * para cada um dos itens existentes no array associativo passado.
     *
     * @param       string $tableName
     *              Nome da tabela de dados.
     * 
     * @param       array $rowData
     *              Array associativo mapeando colunas e valores a serem
     *              utilizados na intrução SQL.
     * 
     * @param       string $pkName
     *              Nome da chave primária a ser usada.
     *              Seu respectivo valor deve estar entre aqueles constantes
     *              em "$rowData".
     * 
     * @return      bool
     */
    function updateSet(string $tableName, array $rowData, string $pkName) : bool;


    /**
     * Efetua uma instrução "INSERT INTO" ou "UPDATE SET" conforme a existência
     * ou não da chave primária entre os dados passados para uso na instrução SQL.
     *
     * @param       string $tableName
     *              Nome da tabela de dados.
     * 
     * @param       array $rowData
     *              Array associativo mapeando colunas e valores a serem
     *              utilizados na intrução SQL.
     * 
     * @param       string $pkName
     *              Nome da chave primária a ser usada.
     * 
     * @return      bool
     */
    function insertOrUpdate(string $tableName, array $rowData, string $pkName) : bool;


    /**
     * Seleciona 1 unica linha de registro da tabela de dados alvo a partir
     * da chave primária indicada e retorna um array associativo contendo cada
     * uma das colunas de dados indicados.
     *
     * @param       string $tableName
     *              Nome da tabela de dados.
     * 
     * @param       string $pkName
     *              Nome da chave primária a ser usada.
     * 
     * @param       int $pk
     *              Valor da chave primária.
     * 
     * @param       ?array $columnNames
     *              Array contendo o nome de cada uma das colunas de
     *              dados a serem retornadas. Usando "null" todas serão
     *              retornadas.
     * 
     * @return      ?array
     */
    function selectFrom(string $tableName, string $pkName, int $pk, ?array $columnNames = null) : ?array;


    /**
     * Efetua uma instrução "DELETE FROM" para a tabela de dados alvo
     * usando o nome e valor da chave primária definida.
     *
     * @param       string $tableName
     *              Nome da tabela de dados.
     * 
     * @param       string $pkName
     *              Nome da chave primária a ser usada.
     * 
     * @param       int $pk
     *              Valor da chave primária.
     * 
     * @return      bool
     */
    function deleteFrom(string $tableName, string $pkName = null, int $pk = null) : bool;





    /**
     * Indica se o modo de transação está aberto.
     *
     * @return      bool
     */
    function inTransaction() : bool;


    /**
     * Inicia o modo de transação, dando ao desenvolvedor
     * a responsabilidade de efetuar o commit ou rollback conforme
     * a necessidade.
     *
     * @return      bool
     */
    function beginTransaction() : bool;


    /**
     * Efetiva as transações realizadas desde que o modo 
     * de transação foi aberto.
     *
     * @return      bool
     */
    function commit() : bool;


    /**
     * Efetua o rollback das transações feitas desde que o 
     * modo de transação foi aberto.
     *
     * @return      bool
     */
    function rollBack() : bool;
}
