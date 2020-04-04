<?php
declare (strict_types=1);

namespace AeonDigital\DataModel\Tests\Concrete;

use AeonDigital\DataModel\Abstracts\aField as aField;








/**
 * Classe concreta do tipo "iField".
 */
class DataField extends aField
{
    function __construct(array $config)
    {
        parent::__construct($config);
    }
}
