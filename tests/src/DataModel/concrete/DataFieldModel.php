<?php
declare (strict_types=1);

namespace AeonDigital\DataModel\Tests\Concrete;

use AeonDigital\Interfaces\DataModel\iModelFactory as iModelFactory;
use AeonDigital\DataModel\Abstracts\aFieldModel as aFieldModel;







/**
 * Classe concreta do tipo "iFieldModel".
 */
class DataFieldModel extends aFieldModel
{
    function __construct(array $config, iModelFactory $factory)
    {
        parent::__construct($config, $factory);
    }
}
