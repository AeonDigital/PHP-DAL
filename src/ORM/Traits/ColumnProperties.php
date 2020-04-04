<?php
declare (strict_types=1);

namespace AeonDigital\ORM\Traits;










/**
 * Métodos e propriedades comuns para uso de colunas de dados que implementem a
 * interface ``iColumn``.
 *
 * @package     AeonDigital\ORM
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2020, Rianna Cantarelli
 * @license     ADPL-v1.0
 */
trait ColumnProperties
{





    /**
     * Propriedade que indica se o valor para esta coluna pode ser repetido entre os
     * demais registros que compões a coleção da tabela de dados.
     *
     * @var         bool
     */
    private bool $unique = false;
    /**
     * Indica se o valor para esta coluna pode ser repetido entre os demais registros
     * que compões a coleção da tabela de dados.
     *
     * @return      bool
     */
    public function isUnique() : bool
    {
        return $this->unique;
    }





    /**
     * Propriedade que indica quando o o valor desta coluna é do tipo *auto-incremento*.
     *
     * @var         bool
     */
    private bool $autoIncrement = false;
    /**
     * Indica quando o o valor desta coluna é do tipo *auto-incremento*.
     *
     * @return      bool
     */
    public function isAutoIncrement() : bool
    {
        return $this->autoIncrement;
    }





    /**
     * Propriedade que indica se esta coluna é a chave primária da tabela de dados.
     *
     * @var         bool
     */
    private bool $primaryKey = false;
    /**
     * Indica se esta coluna é a chave primária da tabela de dados.
     *
     * @return      bool
     */
    public function isPrimaryKey() : bool
    {
        return $this->primaryKey;
    }





    /**
     * Propriedade que indica se esta coluna é uma chave extrangeira.
     *
     * @var         bool
     */
    private bool $foreignKey = false;
    /**
     * Indica se esta coluna é uma chave extrangeira.
     *
     * @return      bool
     */
    public function isForeignKey() : bool
    {
        return $this->foreignKey;
    }





    /**
     * Propriedade que indica se esta coluna está ou não indexada.
     *
     * @var         bool
     */
    private bool $index = false;
    /**
     * Indica se esta coluna está ou não indexada.
     * Por padrão, toda ``primaryKey`` e ``foreignKey`` é automaticamente indexada.
     *
     * @return      bool
     */
    public function isIndex() : bool
    {
        return $this->index;
    }
}
