<?php
declare (strict_types = 1);

use PHPUnit\Framework\TestCase;

use AeonDigital\ORM\DataTableFactory as DataTableFactory;
use AeonDigital\ORM\DataColumnFK as DataColumnFK;
use AeonDigital\ORM\DataColumnFKCollection as DataColumnFKCollection;
use AeonDigital\DAL\DAL as DAL;

require_once __DIR__ . "/../../phpunit.php";



class t04DataColumnFKTest extends TestCase
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



    private function provider_factory()
    {
        $tgtPath = to_system_path(realpath(__DIR__) . "/datamodel/valid");
        return new DataTableFactory($tgtPath, $this->provider_connection());
    }


    private function provider_datacolumnfk()
    {
        return new DataColumnFK(
            [
                "name"              => "validName",
                "fkTableName"       => "Cidade",
                "fkOnUpdate"        => "RESTRICT",
                "fkOnDelete"        => "CASCADE"
            ],
            $this->provider_factory()
        );
    }





    //
    // CONSTRUCTOR
    //

    public function test_constructor_fails_invalid_onupdate()
    {
        $fail = false;
        try {
            $obj = new DataColumnFK(
                [
                    "name" => "validName",
                    "fkTableName" => "Cidade",
                    "fkOnUpdate" => "invalidValue"
                ],
                $this->provider_factory()
            );

        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid \"ON UPDATE\" definition [\"INVALIDVALUE\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");



        $fail = false;
        try {
            $obj = new DataColumnFKCollection(
                [
                    "name" => "validName",
                    "fkTableName" => "Cidade",
                    "fkOnUpdate" => "invalidValue"
                ],
                $this->provider_factory()
            );

        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid \"ON UPDATE\" definition [\"INVALIDVALUE\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_invalid_ondelete()
    {
        $fail = false;
        try {
            $obj = new DataColumnFK(
                [
                    "name" => "validName",
                    "fkTableName" => "Cidade",
                    "fkOnDelete" => "invalidValue"
                ],
                $this->provider_factory()
            );

        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid \"ON DELETE\" definition [\"INVALIDVALUE\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");



        $fail = false;
        try {
            $obj = new DataColumnFKCollection(
                [
                    "name" => "validName",
                    "fkTableName" => "Cidade",
                    "fkOnDelete" => "invalidValue"
                ],
                $this->provider_factory()
            );

        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid \"ON DELETE\" definition [\"INVALIDVALUE\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok()
    {
        $obj = new DataColumnFK(
            [
                "name" => "validName",
                "fkTableName" => "Cidade",
                "fkDescription" => "Descrição desta FK",
                "fkAllowNull" => false,
                "fkUnique" => true,
                "fkOnUpdate" => "CASCADE",
                "fkOnDelete" => "CASCADE",
            ],
            $this->provider_factory()
        );
        $this->assertTrue(is_a($obj, DataColumnFK::class));
        $this->assertSame("Cidade", $obj->getModelName());
        $this->assertTrue($obj->isReference());
        $this->assertFalse($obj->isCollection());

        $this->assertEquals("Descrição desta FK", $obj->getFKDescription());
        $this->assertFalse($obj->isFKAllowNull());
        $this->assertTrue($obj->isFKUnique());
        $this->assertEquals("CASCADE", $obj->getFKOnUpdate());
        $this->assertEquals("CASCADE", $obj->getFKOnDelete());
    }


    public function test_constructor_fkcollection_ok()
    {
        $obj = new DataColumnFKCollection(
            [
                "name" => "validName",
                "fkTableName" => "Cidade",
                "fkLinkTable" => true,
                "fkDescription" => "Descrição desta FK",
                "fkAllowNull" => false,
                "fkUnique" => true,
                "fkOnUpdate" => "CASCADE",
                "fkOnDelete" => "CASCADE",
            ],
            $this->provider_factory()
        );
        $this->assertTrue(is_a($obj, DataColumnFKCollection::class));
        $this->assertSame("Cidade", $obj->getModelName());
        $this->assertTrue($obj->isReference());
        $this->assertTrue($obj->isCollection());
        $this->assertTrue($obj->isFKLinkTable());

        $this->assertEquals("Descrição desta FK", $obj->getFKDescription());
        $this->assertFalse($obj->isFKAllowNull());
        $this->assertTrue($obj->isFKUnique());
        $this->assertEquals("CASCADE", $obj->getFKOnUpdate());
        $this->assertEquals("CASCADE", $obj->getFKOnDelete());
    }






    //
    // ISFOREIGNKEY
    //

    public function test_method_isforeignkey()
    {
        $obj = $this->provider_datacolumnfk();
        $this->assertTrue($obj->isForeignKey());
    }




    //
    // GETFKONUPDATE
    //

    public function test_method_getfkonupdate()
    {
        $obj = $this->provider_datacolumnfk();
        $this->assertSame("RESTRICT", $obj->getFKOnUpdate());
    }





    //
    // GETFKONDELETE
    //

    public function test_method_getfkondelete()
    {
        $obj = $this->provider_datacolumnfk();
        $this->assertSame("CASCADE", $obj->getFKOnDelete());
    }
}
