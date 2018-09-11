<?php
/**
 * Coleção de recursos que devem ser carregados para executar
 * os testes unitários.
 */
$rootDir = realpath(__DIR__ . "/..");
$srcDir = $rootDir . "/src";

require_once $srcDir . "/Interfaces/iConnection.php";
require_once $srcDir . "/Connection.php";
//require_once $srcDir . "/DAL.php";
//require_once $srcDir . "/ParseQualityHeaders.php";
