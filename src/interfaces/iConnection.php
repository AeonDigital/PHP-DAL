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
interface iConnection
{





    /**
     * Retorna o objeto "dbConnection" desta instância.
     *
     * @return       PDO
     */
    function getConnection() : \PDO;


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
     * @param       iConnection $oConnection
     *              Objeto que contêm a conexão que passará a ser
     *              usada por esta instância.
     * 
     * @return      void
     */
    function replaceConnection(iConnection $oConnection) : void;





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
    function executeQuery(string $strSQL, ?array $parans = null) : ?array;


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
}
