<?php
declare (strict_types=1);

namespace AeonDigital\DataModel\Tests\Concrete;

use AeonDigital\Interfaces\DataModel\iModelFactory as iModelFactory;
use AeonDigital\DataModel\Abstracts\aFieldModelCollection as aFieldModelCollection;







/**
 * Classe concreta do tipo "aFieldModelCollection".
 */
class DataFieldModelCollection extends aFieldModelCollection
{
    function __construct(array $config, iModelFactory $factory)
    {
        parent::__construct($config, $factory);
    }
}
