<?php
declare (strict_types=1);

namespace AeonDigital\ORM\Traits;










/**
 * Métodos e propriedades comuns para uso de colunas de dados que representam chaves
 * extrangeiras.
 *
 * @package     AeonDigital\ORM
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2020, Rianna Cantarelli
 * @license     MIT
 */
trait ColumnFKProperties
{





    /**
     * Descrição para ser usada na documentação SQL de uma chave extrangeira.
     *
     * @var         ?string
     */
    private ?string $fkDescription = null;
    /**
     * Retorna a descrição para ser usada na documentação SQL de uma chave extrangeira.
     *
     * @return      ?string
     */
    public function getFKDescription() : ?string
    {
        return $this->fkDescription;
    }





    /**
     * Quando ``true`` forçará que os objetos filhos tenham, obrigatoriamente um
     * vínculo com os objetos pai.
     *
     * @var         bool
     */
    private bool $fkAllowNull = true;
    /**
     * Indica se os objetos filhos (que recebem a FK) aceita serem orfãos, ou seja, se
     * podem existir sem vínculo com com o objeto pai.
     *
     * @return      bool
     */
    public function isFKAllowNull() : bool
    {
        return $this->fkAllowNull;
    }





    /**
     * Indica se o vínculo entre as 2 tabelas de dados se dá por meio de uma ``linkTable``.
     * Quando ``true``, designa que a relação é do tipo ``N-N``.
     *
     * @return      bool
     */
    private bool $fkLinkTable = false;
    /**
     * Indica se o vínculo entre as 2 tabelas de dados se dá por meio de uma ``linkTable``.
     * Quando ``true``, designa que a relação é do tipo ``N-N``.
     *
     * @return      bool
     */
    function isFKLinkTable() : bool
    {
        return $this->fkLinkTable;
    }





    /**
     * Descrição da regra definida para o uso da definição ``ON UPDATE``.
     *
     * @var         ?string
     */
    private ?string $fkOnUpdate = null;
    /**
     * Retorna a regra definida para o uso da definição ``ON UPDATE``.
     *
     * @return      ?string
     */
    public function getFKOnUpdate() : ?string
    {
        return $this->fkOnUpdate;
    }





    /**
     * Descrição da regra definida para o uso da definição ``ON DELETE``.
     *
     * @var         ?string
     */
    private ?string $fkOnDelete = null;
    /**
     * Retorna a regra definida para o uso da definição ``ON DELETE``.
     *
     * @return      ?string
     */
    public function getFKOnDelete() : ?string
    {
        return $this->fkOnDelete;
    }

}
