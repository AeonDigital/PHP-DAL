<?php
declare (strict_types=1);

namespace AeonDigital\DataModel\Tests\Concrete;

use AeonDigital\DataModel\Abstracts\aModel as aModel;








/**
 * Classe concreta do tipo "iModel".
 */
class DataModel extends aModel
{
    function __construct(array $config)
    {
        parent::__construct($config);
    }

    protected function extendCall($name, $arguments) { return true; }
}
