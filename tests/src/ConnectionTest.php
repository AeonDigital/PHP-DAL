<?php
declare (strict_types = 1);

use PHPUnit\Framework\TestCase;

use AeonDigital\DAL\Connection as Connection;

require_once __DIR__ . "/../phpunit.php";








class ConnectionTest extends TestCase
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
            $this->useConnection = new Connection(
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
        //$con = $obj->getConnection();

        $strSQL = "SHOW TABLES;";
        $rset = $obj->executeQuery($strSQL);

        if ($rset !== null) {
            foreach ($rset as $row) {
                $dbName = $row["Tables_in_test"];
                $obj->executeInstruction("DROP TABLE $dbName;");
            }
        }

        $strSQL = "SHOW TABLES;";
        $rset = $obj->executeQuery($strSQL);
        $this->assertSame(0, count($rset));


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
        $rset = $obj->executeQuery($strSQL);
        $this->assertSame(1, count($rset));
    }






    public function test_constructor_fails()
    {
        $fail = false;
        try {
            $con = $this->provider_connection_credentials();

            $obj = new Connection(
                $con["dbType"],
                $con["dbHost"],
                "invalidDataBase",
                $con["dbUserName"],
                $con["dbUserPassword"]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("SQLSTATE[HY000] [1049] Unknown database 'invaliddatabase'", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok() 
    {
        $obj = $this->provider_connection();
        $this->assertTrue(is_a($obj, Connection::class));
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
    // executeInstruction | executeQuery | isExecuted | countAffectedRows | getLastError
    //

    public function test_methods_for_executeinstructions()
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
        $this->assertSame(500, $obj->getLastInsertId());




        $strSQL = "SELECT id, firstname FROM user WHERE email=:email;";
        $parans = [
            "email" => "non exist"
        ];
        $dataTable = $obj->executeQuery($strSQL, $parans);
        $this->assertTrue($obj->isExecuted());
        $this->assertNull($obj->getLastError());
        $this->assertSame(0, $obj->countAffectedRows());
        $this->assertNull($dataTable);
        


        $parans = [
            "email" => "email01"
        ];
        $dataTable = $obj->executeQuery($strSQL, $parans);
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
}
