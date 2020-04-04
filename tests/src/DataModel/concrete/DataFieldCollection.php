<?php
declare (strict_types=1);

namespace AeonDigital\DataModel\Tests\Concrete;

use AeonDigital\DataModel\Abstracts\aFieldCollection as aFieldCollection;








/**
 * Classe concreta do tipo "iFieldCollection".
 */
class DataFieldCollection extends aFieldCollection
{
    function __construct(array $config)
    {
        parent::__construct($config);
    }
}
