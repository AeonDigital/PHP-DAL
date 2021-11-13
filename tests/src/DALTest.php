<?php
declare (strict_types=1);

use PHPUnit\Framework\TestCase;

use AeonDigital\DAL\DAL as DAL;

require_once __DIR__ . "/../phpunit.php";








class DALTest extends TestCase
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

    public function provider_create_test_table()
    {
        $obj = $this->provider_connection();


        $strSQL = "SHOW TABLES;";
        $rset = $obj->getDataTable($strSQL);

        if ($rset !== null) {
            $obj->executeInstruction("SET FOREIGN_KEY_CHECKS=0;");
            foreach ($rset as $row) {
                $dbName = $row["Tables_in_test"];
                $obj->executeInstruction("DROP TABLE $dbName;");
            }
            $obj->executeInstruction("SET FOREIGN_KEY_CHECKS=1;");
        }

        $strSQL = "SHOW TABLES;";
        $rset = $obj->getDataTable($strSQL);
        if ($rset !== null) {
            $this->assertSame(0, count($rset));
        }


        $strSQL = " CREATE TABLE user (
                        id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        firstname VARCHAR(30) NOT NULL,
                        lastname VARCHAR(30) NOT NULL,
                        email VARCHAR(10),
                        active BOOL,
                        register DATETIME
                    );";
        $r = $obj->executeInstruction($strSQL);
        $this->assertTrue($r);


        $strSQL = "SHOW TABLES;";
        $rset = $obj->getDataTable($strSQL);
        $this->assertSame(1, count($rset));
    }






    public function test_constructor_fails_invalid_database()
    {
        $fail = false;
        try {
            $con = $this->provider_connection_credentials();

            $obj = new DAL(
                $con["dbType"],
                $con["dbHost"],
                "invalidDataBase",
                $con["dbUserName"],
                $con["dbUserPassword"]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("SQLSTATE[HY000] [1049] Unknown database 'invalidDatabase'", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_invalid_dbtype()
    {
        $fail = false;
        try {
            $con = $this->provider_connection_credentials();

            $obj = new DAL(
                "invalidDbType",
                $con["dbHost"],
                $con["dbName"],
                $con["dbUserName"],
                $con["dbUserPassword"]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid value defined for \"dbType\". Expected [ mysql, mssqlserver, oracle, postgree ]. Given: [ invalidDbType ].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok()
    {
        $obj = $this->provider_connection();
        $this->assertTrue(is_a($obj, DAL::class));
        $this->provider_create_test_table();
    }





    public function test_method_getconnection()
    {
        $obj = $this->provider_connection();
        $this->assertTrue(is_a($obj->getConnection(), \PDO::class));
    }


    public function test_method_dbtype()
    {
        $obj = $this->provider_connection();
        $this->assertSame("mysql", $obj->getDBType());
    }


    public function test_method_dbhost()
    {
        $obj = $this->provider_connection();
        $this->assertSame("localhost", $obj->getDBHost());
    }


    public function test_method_dbname()
    {
        $obj = $this->provider_connection();
        $this->assertSame("test", $obj->getDBName());
    }




    //
    // executeInstruction | getDataTable | getDataRow | getDataColumn | getCountOf
    // isExecuted | countAffectedRows | getLastError
    //

    public function test_methods_for_executeinstructions()
    {
        $obj = $this->provider_connection();



        $strSQL = "DELETE FROM user;";
        $r = $obj->executeInstruction($strSQL);
        $this->assertTrue($r);
        $this->assertSame(0, $obj->getCountOf("SELECT COUNT(id) as count FROM user;"));

        $strSQL = "ALTER TABLE user AUTO_INCREMENT = 500;";
        $r = $obj->executeInstruction($strSQL);
        $this->assertTrue($r);





        $strSQL = " INSERT INTO
                        user
                            (firstname, lastname, email, active, register)
                        VALUES
                            (:firstname, :lastname, :email, :active, :register);";
        $parans = [
            "firstname" => "user01",
            "lastname"  => "ln01",
            "email"     => "email01",
            "active"    => true,
            "register"  => new DateTime()
        ];
        $r = $obj->executeInstruction($strSQL, $parans);
        $this->assertTrue($r);
        $this->assertTrue($obj->isExecuted());
        $this->assertNull($obj->getLastError());
        $this->assertSame(1, $obj->countAffectedRows());
        $this->assertSame(500, $obj->getLastPK("user", "Id"));
        $this->assertSame(1, $obj->getCountOf("SELECT COUNT(id) as count FROM user;"));




        $strSQL = "SELECT id, firstname FROM user WHERE email=:email;";
        $parans = [
            "email" => "non exist"
        ];
        $dataTable = $obj->getDataTable($strSQL, $parans);
        $this->assertTrue($obj->isExecuted());
        $this->assertNull($obj->getLastError());
        $this->assertSame(0, $obj->countAffectedRows());
        $this->assertNull($dataTable);



        $parans = [
            "email" => "email01"
        ];
        $dataTable = $obj->getDataTable($strSQL, $parans);
        $this->assertTrue($obj->isExecuted());
        $this->assertNull($obj->getLastError());
        $this->assertSame(1, $obj->countAffectedRows());
        $this->assertTrue(is_array($dataTable));
        $this->assertSame(1, count($dataTable));
        $this->assertSame("user01", $dataTable[0]["firstname"]);




        $strSQL = " INSERT INTO
                        user
                            (firstname, lastname, email, active, register)
                        VALUES
                            (:firstname, :lastname, :email, :active, :register);";
        $parans = [
            "firstname" => "user01",
            "lastname"  => "ln01",
            "email"     => "email0123456789",
            "active"    => true,
            "register"  => new DateTime()
        ];
        $r = $obj->executeInstruction($strSQL, $parans);
        $this->assertFalse($r);
        $this->assertFalse($obj->isExecuted());
        $this->assertNotNull($obj->getLastError());
        $this->assertSame("SQLSTATE[22001]: String data, right truncated: 1406 Data too long for column 'email' at row 1", $obj->getLastError());
    }


    public function test_method_getdatarow()
    {
        $obj = $this->provider_connection();



        $strSQL = "DELETE FROM user;";
        $r = $obj->executeInstruction($strSQL);
        $this->assertTrue($r);

        $strSQL = "ALTER TABLE user AUTO_INCREMENT = 500;";
        $r = $obj->executeInstruction($strSQL);
        $this->assertTrue($r);



        $strSQL = " INSERT INTO
                        user
                            (firstname, lastname, email, active, register)
                        VALUES
                            (:firstname, :lastname, :email, :active, :register);";
        $parans = [
            "firstname" => "user01",
            "lastname"  => "ln01",
            "email"     => "email01",
            "active"    => true,
            "register"  => new DateTime()
        ];
        $r = $obj->executeInstruction($strSQL, $parans);
        $this->assertTrue($r);
        $this->assertTrue($obj->isExecuted());
        $this->assertNull($obj->getLastError());
        $this->assertSame(1, $obj->countAffectedRows());
        $this->assertSame(500, $obj->getLastPK("user", "Id"));


        $strSQL = "SELECT firstname, email, active FROM user WHERE id=500;";
        $r = $obj->getDataRow($strSQL);
        $this->assertTrue(is_array($r));
        $this->assertTrue(array_key_exists("firstname", $r));
        $this->assertTrue(array_key_exists("email", $r));
        $this->assertTrue(array_key_exists("active", $r));

        $this->assertSame($parans["firstname"], $r["firstname"]);
        $this->assertSame($parans["email"], $r["email"]);
        $this->assertSame($parans["active"], (bool)$r["active"]);
    }


    public function test_method_getdatacolumn()
    {
        $obj = $this->provider_connection();



        $strSQL = "DELETE FROM user;";
        $r = $obj->executeInstruction($strSQL);
        $this->assertTrue($r);

        $strSQL = "ALTER TABLE user AUTO_INCREMENT = 500;";
        $r = $obj->executeInstruction($strSQL);
        $this->assertTrue($r);



        $strSQL = " INSERT INTO
                        user
                            (firstname, lastname, email, active, register)
                        VALUES
                            (:firstname, :lastname, :email, :active, :register);";
        $parans = [
            "firstname" => "user01",
            "lastname"  => "ln01",
            "email"     => "email01",
            "active"    => true,
            "register"  => new DateTime()
        ];
        $r = $obj->executeInstruction($strSQL, $parans);
        $this->assertTrue($r);
        $this->assertTrue($obj->isExecuted());
        $this->assertNull($obj->getLastError());
        $this->assertSame(1, $obj->countAffectedRows());
        $this->assertSame(500, $obj->getLastPK("user", "Id"));


        $strSQL = "SELECT register FROM user WHERE id=500;";
        $r = $obj->getDataColumn($strSQL, null, "datetime");
        $this->assertTrue(is_a($r, "\DateTime"));
        $this->assertSame($parans["register"]->format("Y-m-d H:i:s"), $r->format("Y-m-d H:i:s"));

        $strSQL = "SELECT active FROM user WHERE id=500;";
        $r = $obj->getDataColumn($strSQL, null, "bool");
        $this->assertTrue(is_bool($r));
        $this->assertTrue($r);

        $strSQL = "SELECT id FROM user;";
        $r = $obj->getDataColumn($strSQL, null, "int");
        $this->assertTrue(is_int($r));
        $this->assertSame(500, $r);
    }




    //
    // getLastPK | countRowsFrom | countRowsWith | hasRowsWith
    // insertInto | updateSet | insertOrUpdate | selectFrom | deleteFrom
    //

    public function test_methods_for_crud()
    {
        $obj = $this->provider_connection();


        $strSQL = "DELETE FROM user;";
        $r = $obj->executeInstruction($strSQL);
        $this->assertTrue($r);

        $strSQL = "ALTER TABLE user AUTO_INCREMENT = 500;";
        $r = $obj->executeInstruction($strSQL);
        $this->assertTrue($r);
        $this->assertSame(0, $obj->countRowsFrom("user", "id"));


        $parans = [
            "firstname" => "user01",
            "lastname"  => "ln01",
            "email"     => "email01",
            "active"    => true,
            "register"  => new DateTime()
        ];
        $r = $obj->insertOrUpdate("user", $parans, "id");
        $this->assertTrue($r);
        $this->assertSame(1, $obj->countRowsFrom("user", "id"));


        $id = $obj->getLastPK("user", "id");
        $this->assertSame(500, $id);

        $parans["id"] = $id;
        $parans["firstname"] = "user01 alter name";
        $parans["email"] = "emailalter";
        $r = $obj->insertOrUpdate("user", $parans, "id");
        $this->assertTrue($r);
        $this->assertSame(1, $obj->countRowsFrom("user", "id"));

        $rowData = $obj->selectFrom("user", "id", $id);
        $this->assertTrue(key_exists("id", $rowData));
        $this->assertTrue(key_exists("lastname", $rowData));
        $this->assertTrue(key_exists("email", $rowData));
        $this->assertTrue(key_exists("active", $rowData));
        $this->assertTrue(key_exists("register", $rowData));

        $this->assertSame($id, (int)$rowData["id"]);
        $this->assertSame($parans["firstname"], $rowData["firstname"]);
        $this->assertSame($parans["email"], $rowData["email"]);
        $this->assertSame((int)$parans["active"], (int)$rowData["active"]);
        $this->assertSame($parans["register"]->format("Y-m-d H:i:s"), $rowData["register"]);



        $parans = [
            "firstname" => "user02",
            "lastname"  => "ln01",
            "email"     => "email01",
            "active"    => true,
            "register"  => new DateTime()
        ];
        $r = $obj->insertOrUpdate("user", $parans, "id");
        $this->assertTrue($r);
        $this->assertSame(2, $obj->countRowsFrom("user", "id"));

        $parans = [
            "firstname" => "user03",
            "lastname"  => "ln03",
            "email"     => "email03",
            "active"    => false,
            "register"  => new DateTime()
        ];
        $r = $obj->insertOrUpdate("user", $parans, "id");
        $this->assertTrue($r);
        $this->assertSame(3, $obj->countRowsFrom("user", "id"));


        $this->assertSame(2, $obj->countRowsWith("user", "lastname", "ln01"));
        $this->assertSame(1, $obj->countRowsWith("user", "firstname", "user02"));
        $this->assertSame(2, $obj->countRowsWith("user", "active", true));
        $this->assertTrue($obj->hasRowsWith("user", "email", "email03"));


        $r = $obj->deleteFrom("user", "id", $id);
        $this->assertTrue($r);
        $this->assertSame(2, $obj->countRowsFrom("user", "id"));

        $strSQL = "DELETE FROM user;";
        $r = $obj->executeInstruction($strSQL);
        $this->assertTrue($r);
        $this->assertSame(0, $obj->countRowsFrom("user", "id"));
    }




    //
    // beginTransaction | inTransaction | commit | rollBack
    //

    public function test_methods_for_transaction()
    {
        $obj = $this->provider_connection();
        $this->assertFalse($obj->inTransaction());



        $strSQL = "DELETE FROM user;";
        $r = $obj->executeInstruction($strSQL);
        $this->assertTrue($r);

        $strSQL = "ALTER TABLE user AUTO_INCREMENT = 500;";
        $r = $obj->executeInstruction($strSQL);
        $this->assertTrue($r);



        $obj->beginTransaction();
        $this->assertTrue($obj->inTransaction());

        $strSQL = " INSERT INTO
                        user
                            (firstname, lastname, email, active, register)
                        VALUES
                            (:firstname, :lastname, :email, :active, :register);";
        $parans = [
            "firstname" => "user01",
            "lastname"  => "ln01",
            "email"     => "email01",
            "active"    => true,
            "register"  => new DateTime()
        ];
        $r = $obj->executeInstruction($strSQL, $parans);
        $this->assertTrue($r);
        $this->assertSame(500, $obj->getLastPK("user", "Id"));



        $parans = [
            "firstname" => "user01",
            "lastname"  => "ln01",
            "email"     => "email01",
            "active"    => true,
            "register"  => new DateTime()
        ];
        $r = $obj->executeInstruction($strSQL, $parans);
        $this->assertTrue($r);
        $this->assertSame(501, $obj->getLastPK("user", "Id"));



        $obj->rollBack();
        $this->assertFalse($obj->inTransaction());



        $strSQL = "SELECT COUNT(id) as count FROM user;";
        $dataTable = $obj->getDataTable($strSQL, $parans);
        $this->assertTrue($obj->isExecuted());
        $this->assertNull($obj->getLastError());
        $this->assertTrue(is_array($dataTable));
        $this->assertSame(1, count($dataTable));
        $this->assertSame("0", $dataTable[0]["count"]);



        $obj->beginTransaction();
        $this->assertTrue($obj->inTransaction());



        $strSQL = " INSERT INTO
                        user
                            (firstname, lastname, email, active, register)
                        VALUES
                            (:firstname, :lastname, :email, :active, :register);";
        $parans = [
            "firstname" => "user01",
            "lastname"  => "ln01",
            "email"     => "email01",
            "active"    => true,
            "register"  => new DateTime()
        ];
        $r = $obj->executeInstruction($strSQL, $parans);
        $this->assertTrue($r);
        $this->assertSame(502, $obj->getLastPK("user", "Id"));



        $parans = [
            "firstname" => "user01",
            "lastname"  => "ln01",
            "email"     => "email01",
            "active"    => true,
            "register"  => new DateTime()
        ];
        $r = $obj->executeInstruction($strSQL, $parans);
        $this->assertTrue($r);
        $this->assertSame(503, $obj->getLastPK("user", "Id"));


        $obj->commit();
        $this->assertFalse($obj->inTransaction());

        $strSQL = "SELECT COUNT(id) as count FROM user;";
        $dataTable = $obj->getDataTable($strSQL, $parans);
        $this->assertTrue($obj->isExecuted());
        $this->assertNull($obj->getLastError());
        $this->assertTrue(is_array($dataTable));
        $this->assertSame(1, count($dataTable));
        $this->assertSame("2", $dataTable[0]["count"]);
    }
}
