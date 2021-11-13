<?php
declare (strict_types = 1);

use PHPUnit\Framework\TestCase;

use AeonDigital\ORM\DataTableFactory as DataTableFactory;
use AeonDigital\ORM\DataTable as DataTable;
use AeonDigital\DAL\DAL as DAL;

require_once __DIR__ . "/../../phpunit.php";




class t03DataTableFactoryTest extends TestCase
{

    private $useConnection = null;



    private function provider_connection_credentials()
    {
        return [
            "dbType"            => getenv("DATABASE_TYPE"), // export DATABASE_TYPE=mysql
            "dbHost"            => getenv("DATABASE_HOST"), // export DATABASE_HOST=localhost
            "dbName"            => getenv("DATABASE_NAME"), // export DATABASE_NAME=test
            "dbUserName"        => getenv("DATABASE_USER"), // export DATABASE_USER=root
            "dbUserPassword"    => getenv("DATABASE_PASS"), // export DATABASE_PASS=root
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





    //
    // CONSTRUCTOR
    //

    public function test_constructor_fails_invalid_targetpath()
    {
        $fail = false;
        $tgtPath = to_system_path(realpath(__DIR__) . "/invalidpath");
        try {
            $obj = new DataTableFactory($tgtPath, $this->provider_connection());
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid given path to data models [\"$tgtPath\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }



    public function test_constructor_ok()
    {
        $tgtPath = to_system_path(realpath(__DIR__) . "/datamodel/valid");
        $obj = new DataTableFactory($tgtPath, $this->provider_connection());
        $this->assertTrue(is_a($obj, DataTableFactory::class));
    }





    //
    // GETDAL
    //

    public function test_method_getdal()
    {
        $obj = $this->provider_factory();
        $this->assertTrue(is_a($obj->getDAL(), DAL::class));
    }





    //
    // GETPROJECTNAME
    //

    public function test_property_projectname()
    {
        $obj = $this->provider_factory();
        $this->assertSame("test", $obj->getProjectName());
    }





    //
    // GETPROJECTDIRECTORY
    //

    public function test_property_projectdirectory()
    {
        $tgtPath = to_system_path(realpath(__DIR__) . "/datamodel/valid");

        $obj = $this->provider_factory();
        $this->assertSame($tgtPath, $obj->getProjectDirectory());
    }





    //
    // RECREATEPROJECTDATAFILE
    //

    public function test_method_recreateprojectdatafile_fails_duplicated_tablename()
    {
        $fail = false;
        $tgtPath = to_system_path(realpath(__DIR__) . "/datamodel/duplicatedtable");
        try {
            $obj = new DataTableFactory($tgtPath, $this->provider_connection());
            $obj->recreateProjectDataFile();
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Duplicated table name [\"Cidade\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_method_recreateprojectdatafile_fails_duplicated_alias()
    {
        $fail = false;
        $tgtPath = to_system_path(realpath(__DIR__) . "/datamodel/duplicatedalias");
        try {
            $obj = new DataTableFactory($tgtPath, $this->provider_connection());
            $obj->recreateProjectDataFile();
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Duplicated table alias [\"samealias\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_method_recreateprojectdatafile()
    {
        // Inicialmente exclui qualquer arquivo de configuração pré-existente
        $tgtPath = to_system_path(realpath(__DIR__) . "/datamodel/valid");
        $tgtFile = $tgtPath . DS . "_projectData.php";

        if (file_exists($tgtFile) === true) {
            unlink($tgtFile);
        }
        $this->assertFalse(file_exists($tgtFile));

        // Inicia uma nova fábrica e executa a recriação do arquivo
        // de configuração.
        $obj = $this->provider_factory();
        $obj->recreateProjectDataFile();

        // Verifica se o arquivo foi recriado.
        $this->assertTrue(file_exists($tgtFile));

        // Executa novamente, sobrescrevendo o arquivo existente
        $obj->recreateProjectDataFile();
        $this->assertTrue(file_exists($tgtFile));
    }




    //
    // GETDATATABLELIST
    //

    public function test_property_datatablelist()
    {
        $obj = $this->provider_factory();

        $expected = [
            "Cidade",
            "EnderecoPostal",
            "GrupoDeSeguranca",
            "SessaoDeAcesso",
            "UsuarioDoDominio"
        ];
        $this->assertSame($expected, $obj->getDataTableList());
    }





    //
    // HASDATAMODEL | HASDATATABLE
    //

    public function test_method_hasdatatable()
    {
        $obj = $this->provider_factory();
        $this->assertTrue($obj->hasDataTable("Cidade"));
        $this->assertTrue($obj->hasDataTable("UsuarioDoDominio"));
        $this->assertFalse($obj->hasDataTable("InvalidTableName"));
    }





    //
    // CREATEDATAMODEL | CREATEDATATABLE
    //

    public function test_method_createdatatable_fails()
    {
        $fail = false;
        $obj = $this->provider_factory();

        try {
           $tbCidade = $obj->createDataTable("InvalidTableName");
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("The given data table name does not exist in this project [\"InvalidTableName\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_method_createdatatable()
    {
        $obj = $this->provider_factory();

        $tbCidade = $obj->createDataTable("Cidade");
        $this->assertTrue(is_a($tbCidade, DataTable::class));

        $this->assertTrue($tbCidade->hasField("Nome"));
        $this->assertTrue($tbCidade->hasField("Estado"));
        $this->assertFalse($tbCidade->hasField("invalidFieldName"));
    }

}
