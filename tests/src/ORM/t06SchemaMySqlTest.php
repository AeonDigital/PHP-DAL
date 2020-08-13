<?php
declare (strict_types = 1);

use PHPUnit\Framework\TestCase;

use AeonDigital\ORM\DataTableFactory as DataTableFactory;
use AeonDigital\DAL\DAL as DAL;
use AeonDigital\ORM\Schema as Schema;

require_once __DIR__ . "/../../phpunit.php";




class t06SchemaMySqlTest extends TestCase
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



    private function provider_schema()
    {
        return new Schema($this->provider_factory());
    }




    /*
    //
    // CONSTRUCTOR
    //

    public function test_constructor_ok()
    {
        $obj = new Schema($this->provider_factory());
        $this->assertTrue(is_a($obj, Schema::class));
    }





    //
    // GENERATECREATESCHEMAFILES
    //


    public function test_method_generatecreateschemafiles()
    {
        // Inicialmente exclui qualquer arquivo de schema pré-existente
        $tgtPath = to_system_path(realpath(__DIR__) . "/datamodel/valid");
        $tgtFile = $tgtPath . DS . "_projectSchema.sql";

        if (file_exists($tgtFile) === true) {
            unlink($tgtFile);
        }
        $this->assertFalse(file_exists($tgtFile));

        // Inicia uma instância "iSchema" e gera o arquivo de schema do projeto.
        $obj = $this->provider_schema();
        $obj->generateCreateSchemaFiles();

        // Verifica se o arquivo foi recriado.
        $this->assertTrue(file_exists($tgtFile));
    }





    //
    // LISTDATABASETABLES
    //

    public function test_method_listdatabasetables()
    {
        $obj = $this->provider_schema();
        $DAL = $this->provider_connection();


        $strSQL = "SHOW TABLES;";
        $allTables = $DAL->getDataTable($strSQL);

        if ($allTables === null) {
            // Cria uma nova tabela de dados não mapeada
            $strSQL = "CREATE TABLE IF NOT EXISTS nonmappedtable (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                firstname VARCHAR(30) NOT NULL,
                lastname VARCHAR(30) NOT NULL,
                email VARCHAR(50)
            );";
            $DAL->executeInstruction($strSQL);

            $strSQL = "SHOW TABLES;";
            $allTables = $DAL->getDataTable($strSQL);
        }


        $allTableNames = [];
        foreach ($allTables  as $row) {
            $allTableNames[] = $row[key($row)];
        }


        $listTables = $obj->listDataBaseTables();
        $this->assertNotNull($listTables);
        $this->assertSame(count($allTableNames), count($listTables));


        foreach ($allTableNames as $tableName) {
            $find = false;
            foreach ($listTables as $data) {
                if ($tableName === $data["tableName"]) {
                    $find = true;
                }
            }
            $this->assertTrue($find);
        }
    }





    //
    // EXECUTEDROPSCHEMA
    //

    public function test_method_executedropschema()
    {
        $obj = $this->provider_schema();
        $DAL = $this->provider_connection();


        $strSQL = "SHOW TABLES;";
        $allTables = $DAL->getDataTable($strSQL);

        if ($allTables === null) {
            // Cria uma nova tabela de dados não mapeada
            $strSQL = "CREATE TABLE IF NOT EXISTS nonmappedtable (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                firstname VARCHAR(30) NOT NULL,
                lastname VARCHAR(30) NOT NULL,
                email VARCHAR(50)
            );";
            $DAL->executeInstruction($strSQL);

            $strSQL = "SHOW TABLES;";
            $allTables = $DAL->getDataTable($strSQL);
        }
        $this->assertNotNull($allTables);



        $r = $obj->executeDropSchema();
        $this->assertTrue($r);


        $strSQL = "SHOW TABLES;";
        $allTables = $DAL->getDataTable($strSQL);
        $this->assertNull($allTables);
    }





    //
    // EXECUTECREATESCHEMA
    //

    public function test_method_executecreateschema()
    {
        // Inicialmente exclui qualquer arquivo de schema pré-existente
        $tgtPath = to_system_path(realpath(__DIR__) . "/datamodel/valid");
        $tgtFile = $tgtPath . DS . "_projectSchema.sql";

        if (file_exists($tgtFile) === true) {
            unlink($tgtFile);
        }
        $this->assertFalse(file_exists($tgtFile));


        $obj = $this->provider_schema();
        $DAL = $this->provider_connection();
        $obj->executeDropSchema();

        $strSQL = "SHOW TABLES;";
        $allTables = $DAL->getDataTable($strSQL);
        $this->assertNull($allTables);

        $obj->executeCreateSchema(true);

        $strSQL = "SHOW TABLES;";
        $allTables = $DAL->getDataTable($strSQL);
        $this->assertNotNull($allTables);
    }





    //
    // LISTTABLECOLUMNS
    //

    public function test_method_listtablecolumns()
    {
        $obj = $this->provider_schema();
        if ($obj->listDataBaseTables() === null) {
            $obj->executeCreateSchema();
        }


        $columnNames = ["Apresentacao", "Ativo", "DataDeDefinicaoDeSenha", "DataDeRegistro",
                        "EmailContato", "Genero", "Id", "Locale", "Login", "Nome", "Senha",
                        "SessaoDeAcesso_Id", "ShortLogin", "ValorInteiro", "ValorFloat",
                        "ValorReal"];


        $listColumns = $obj->listTableColumns("UsuarioDoDominio");
        $this->assertNotNull($listColumns);
        $this->assertSame(count($columnNames), count($listColumns));

        foreach ($listColumns as $row) {
            $this->assertTrue(array_key_exists("columnPrimaryKey", $row));
            $this->assertTrue(array_key_exists("columnUniqueKey", $row));
            $this->assertTrue(array_key_exists("columnName", $row));
            $this->assertTrue(array_key_exists("columnDescription", $row));
            $this->assertTrue(array_key_exists("columnDataType", $row));
            $this->assertTrue(array_key_exists("columnAllowNull", $row));
            $this->assertTrue(array_key_exists("columnDefaultValue", $row));
            $this->assertTrue(array_in_ci($row["columnName"], $columnNames));
        }
    }





    //
    // LISTSCHEMACONSTRAINT
    //

    public function test_method_listschemaconstraint()
    {
        $obj = $this->provider_schema();
        if ($obj->listDataBaseTables() === null) {
            $obj->executeCreateSchema();
        }


        $expected = [
            "uc_cid_Nome_Estado_Capital", "fk_ep_to_cid_Cidade_Id", "uc_sda_SessionID",
            "fk_udd_gds_to_gds_GrupoDeSeguranca_Id", "uc_udd_gds_UsuarioDoDominio_Id_GrupoDeSeguranca_Id",
            "idx_udd_Login", "uc_udd_Login", "idx_udd_ShortLogin", "uc_udd_ShortLogin"
        ];
        $completeConstraintList = $obj->listSchemaConstraint();
        $this->assertNotNull($completeConstraintList);
        $constraintNameList = [];
        foreach ($completeConstraintList as $cRule) {
            $constraintNameList[] = $cRule["constraintName"];
        }

        foreach ($expected as $cName) {
            $this->assertTrue(in_array($cName, $constraintNameList));
        }



        $expected = [
            "PRIMARY", "idx_udd_Login", "uc_udd_Login", "idx_udd_ShortLogin",
            "uc_udd_ShortLogin"
        ];
        $tgtTableConstraintList = $obj->listSchemaConstraint("UsuarioDoDominio");
        $this->assertNotNull($tgtTableConstraintList);
        $constraintNameList = [];
        foreach ($tgtTableConstraintList as $cRule) {
            $constraintNameList[] = $cRule["constraintName"];
        }


        foreach ($expected as $cName) {
            $this->assertTrue(in_array($cName, $constraintNameList));
        }
    }
    */





    //
    // CREATEDATABASEDUMP e CREATEDATABASEFROMDUMP
    //
    /*
    public function test_method_createdatabasedump_createdatabasefromdump()
    {
        // Recria todo o schema original
        $factory = $this->provider_factory();
        $dataBaseName = $factory->getProjectName();


        $fullPathToBackupDirectory = to_system_path(realpath(__DIR__) . "/datamodel/backup");
        $fullPathToDataBaseDumpFile = $fullPathToBackupDirectory . DS . $dataBaseName . ".sql";

        if (is_file($fullPathToDataBaseDumpFile) === true) {
            unlink($fullPathToDataBaseDumpFile);
        }
        $this->assertFalse(is_file($fullPathToDataBaseDumpFile));

        $obj = $this->provider_schema();
        $obj->executeCreateDataBaseDump(
            "root",
            "admin",
            $fullPathToDataBaseDumpFile
        );

        $this->assertTrue(is_file($fullPathToDataBaseDumpFile));



        $DAL = $this->provider_connection();
        $newDataBaseName = $dataBaseName . "_new";
        $strSQL = " SELECT
                        COUNT(SCHEMA_NAME ) as count
                    FROM
                        INFORMATION_SCHEMA.SCHEMATA
                    WHERE
                        SCHEMA_NAME='$newDataBaseName';";
        if ($DAL->getCountOf($strSQL) > 0) {
            $DAL->executeInstruction("DROP DATABASE $newDataBaseName;");
        }
        $this->assertEquals(0, $DAL->getCountOf($strSQL));

        $obj->executeCreateDataBaseFromDump(
            "root",
            "admin",
            $newDataBaseName,
            $fullPathToDataBaseDumpFile
        );

        $this->assertEquals(1, $DAL->getCountOf($strSQL));
        $DAL->executeInstruction("DROP DATABASE $newDataBaseName;");
        $this->assertEquals(0, $DAL->getCountOf($strSQL));
    }
    */




    //
    // UPDATEPROJECTSCHEMA
    //

    public function test_method_updateprojectschema()
    {
        $DAL = $this->provider_connection();
        $factory = $this->provider_factory();
        $dataBaseName = $factory->getProjectName();


        $fullPathToBackupDirectory = to_system_path(realpath(__DIR__) . "/datamodel/backup");
        $fullPathToAtualDataBaseDumpFile = $fullPathToBackupDirectory . DS . $dataBaseName . "_1-0.sql";

        if (is_file($fullPathToAtualDataBaseDumpFile) === true) {
            unlink($fullPathToAtualDataBaseDumpFile);
        }
        $this->assertFalse(is_file($fullPathToAtualDataBaseDumpFile));

        $obj = $this->provider_schema();
        $obj->executeUpdateProjectSchema(
            "root",
            "admin",
            "1-0",
            $fullPathToBackupDirectory
        );

        $this->assertTrue(is_file($fullPathToAtualDataBaseDumpFile));


        $newDataBaseName = $dataBaseName . "_tmp";
        $strSQL = " SELECT
                        COUNT(SCHEMA_NAME ) as count
                    FROM
                        INFORMATION_SCHEMA.SCHEMATA
                    WHERE
                        SCHEMA_NAME='$newDataBaseName';";
        $this->assertEquals(1, $DAL->getCountOf($strSQL));
    }}
