<?php
$rootDir = realpath(__DIR__ . "/..");
require_once $rootDir . "/vendor/autoload.php";


$tstDir = $rootDir . "/tests/src";
require_once $tstDir . "/DataModel/concrete/DataField.php";
require_once $tstDir . "/DataModel/concrete/DataFieldCollection.php";
require_once $tstDir . "/DataModel/concrete/DataModel.php";
require_once $tstDir . "/DataModel/concrete/ModelFactory.php";
require_once $tstDir . "/DataModel/concrete/DataFieldModel.php";
require_once $tstDir . "/DataModel/concrete/DataFieldModelCollection.php";
