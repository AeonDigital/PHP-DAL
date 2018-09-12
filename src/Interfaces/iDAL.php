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
     * Retorna o último ID inserido na última instrução.
     *
     * @return      ?int
     */
    function getLastInsertId() : ?int;





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
