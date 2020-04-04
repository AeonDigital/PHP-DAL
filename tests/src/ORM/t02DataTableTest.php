<?php
declare (strict_types = 1);

use PHPUnit\Framework\TestCase;

use AeonDigital\ORM\DataTable as DataTable;
use AeonDigital\ORM\DataColumn as DataColumn;
use AeonDigital\DAL\DAL as DAL;

require_once __DIR__ . "/../../phpunit.php";




class t02DataTableTest extends TestCase
{

    private $useConnection = null;



    private function provider_connection_credentials()
    {
        return [
            "dbType"            => "mysql",
            "dbHost"            => "localhost",
            "dbName"            => "test",
            "dbUserName"        => "root",
            "dbUserPassword"    => "admin"
        ];
    }



    private function provider_connection()
    {
        if ($this->useConnection === null) {
            $con = $this->provider_connection_credentials();
            $this->useConnection = new DAL(
                $con["dbType"],
                $con["dbHost"],
                $con["dbName"],
                $con["dbUserName"],
                $con["dbUserPassword"]);
        }
        return $this->useConnection;
    }





    //
    // CONSTRUCTOR
    //

    public function test_constructor_fails_required_alias()
    {
        $fail = false;
        try {
            $obj = new DataTable([
                "tableName"                 => "validModelName",
                "columns"                   => [
                    new DataColumn([
                        "name"                      => "validFieldName",
                        "type"                      => "String"
                    ])
                ]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Every data table must have an unique and valid alias.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_executeaftercreatetable()
    {
        $fail = false;
        try {
            $obj = new DataTable([
                "tableName"                 => "validModelName",
                "alias"                     => "vmn",
                "executeAfterCreateTable"   => "invalid value",
                "columns"                   => [
                    new DataColumn([
                        "name"                      => "validFieldName",
                        "type"                      => "String"
                    ])
                ]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid value defined for \"executeAfterCreateTable\". Expected non empty array of strings.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_orminstructions()
    {
        $fail = false;
        try {
            $obj = new DataTable([
                "tableName"                 => "validModelName",
                "alias"                     => "vmn",
                "columns"                   => [
                    new DataColumn([
                        "name"                      => "validFieldName",
                        "type"                      => "String"
                    ])
                ]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid value defined for \"ormInstructions\".", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok()
    {
        $obj = new DataTable([
            "tableName"                 => "validModelName",
            "alias"                     => "vmn",
            "ormInstructions"           => ["select" => "", "selectChild" => ""],
            "columns"                   => [
                new DataColumn([
                    "name"                      => "validFieldName",
                    "type"                      => "String"
                ])
            ]
        ]);
        $this->assertTrue(is_a($obj, DataTable::class));
    }





    //
    // ALIAS
    //

    public function test_property_alias()
    {
        $obj = new DataTable([
            "tableName"                 => "validModelName",
            "alias"                     => "vmn",
            "ormInstructions"           => ["select" => "", "selectChild" => ""],
            "columns"                   => [
                new DataColumn([
                    "name"                      => "validFieldName",
                    "type"                      => "String"
                ])
            ]
        ]);
        $this->assertSame("vmn", $obj->getAlias());
    }





    //
    // EXECUTEAFTERCREATETABLE
    //

    public function test_property_executeaftercreatetable()
    {
        $obj = new DataTable([
            "tableName"                 => "validModelName",
            "alias"                     => "vmn",
            "executeAfterCreateTable"   => ["Instruction 1", "Instruction 2"],
            "ormInstructions"           => ["select" => "", "selectChild" => ""],
            "columns"                   => [
                new DataColumn([
                    "name"                      => "validFieldName",
                    "type"                      => "String"
                ])
            ]
        ]);
        $this->assertSame(["Instruction 1", "Instruction 2"], $obj->getExecuteAfterCreateTable());
    }
}
