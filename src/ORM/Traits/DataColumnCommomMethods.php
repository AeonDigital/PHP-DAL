<?php
declare (strict_types=1);

namespace AeonDigital\ORM\Traits;










/**
 * Métodos que trazem suporte a tipos especiais de transformação a ser usado pelas
 * colunas de dados.
 *
 * @package     AeonDigital\ORM
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2020, Rianna Cantarelli
 * @license     ADPL-v1.0
 */
trait DataColumnCommomMethods
{





    /**
     * Retorna um array contendo as definições necessárias para aplicar a transformação
     * ``UPPER`` na coluna de dados.
     *
     * Diferente dos demais tipos de transformação, neste caso tanto a função
     * ``removeFormat`` quanto ``format`` fazem o mesmo efeito. Com isso, os valores serão
     * SEMPRE armazenados em seu formato transformado.
     *
     * @return      array
     */
    private function inputFormat_UPPER() : array
    {
        return [
            "name"          => "UPPER",
            "length"        => null,
            "check"         => function($v) { return true; },
            "removeFormat"  => function($v) { return \strtoupper($v); },
            "format"        => function($v) { return \strtoupper($v); },
            "storageFormat" => function($v) { return \strtoupper($v); }
        ];
    }



    /**
     * Retorna um array contendo as definições necessárias para aplicar a transformação
     * ``LOWER`` na coluna de dados.
     *
     * Diferente dos demais tipos de transformação, neste caso tanto a função
     * ``removeFormat`` quanto ``format`` fazem o mesmo efeito. Com isso, os valores serão
     * SEMPRE armazenados em seu formato transformado.
     *
     * @return      array
     */
    private function inputFormat_LOWER() : array
    {
        return [
            "name"          => "LOWER",
            "length"        => null,
            "check"         => function($v) { return true; },
            "removeFormat"  => function($v) { return \strtolower($v); },
            "format"        => function($v) { return \strtolower($v); },
            "storageFormat" => function($v) { return \strtolower($v); }
        ];
    }



    /**
     * Retorna um array contendo as definições necessárias para aplicar a transformação
     * ``NAMES`` na coluna de dados.
     *
     * Esta transformação converte o primeiro caracter de cada palavra para maiúsculo mas
     * preserva em minúsculo os artigos que existam no nome.
     *
     * Este método se adequa bem para necessidades de nomes próprios típicos da cultura
     * de lingua portuguesa.
     *
     * Diferente dos demais tipos de transformação, neste caso tanto a função
     * ``removeFormat`` quanto ``format`` fazem o mesmo efeito. Com isso, os valores serão
     * SEMPRE armazenados em seu formato transformado.
     *
     * @return      array
     */
    private function inputFormat_NAMES_PTBR() : array
    {
        return [
            "name"          => "NAMES_PTBR",
            "length"        => null,
            "check"         => function($v) { return true; },
            "removeFormat"  => function($v) { return \mb_str_uc_names_ptbr($v); },
            "format"        => function($v) { return \mb_str_uc_names_ptbr($v); },
            "storageFormat" => function($v) { return \mb_str_uc_names_ptbr($v); }
        ];
    }



    /**
     * Verifica se o ``inputFormat`` definido corresponde a um dos tipos de transformação
     * especial definidos nesta classe ou se trata-se de uma notação especial para as
     * classes do projeto ``AeonDigital\DataFormat\Patterns\``.
     *
     * Os tipos especiais são ``UPPER``, ``LOWER`` e ``NAMES``.
     *
     * @param       mixed $inputFormat
     *              Regra ``inputFormat`` que será utilizado.
     *
     * @return      mixed
     */
    private function selectInputFormat($inputFormat)
    {
        if (\is_string($inputFormat) === true) {
            $if = \strtoupper($inputFormat);
            $match = false;
            switch ($if) {
                case "UPPER":
                    $match = true;
                    $inputFormat = $this->inputFormat_UPPER();
                    break;

                case "LOWER":
                    $match = true;
                    $inputFormat = $this->inputFormat_LOWER();
                    break;

                case "NAMES_PTBR":
                    $match = true;
                    $inputFormat = $this->inputFormat_NAMES_PTBR();
                    break;
            }
        }
        return $inputFormat;
    }

}
