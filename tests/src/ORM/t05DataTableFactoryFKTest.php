<?php
declare (strict_types = 1);

use PHPUnit\Framework\TestCase;

use AeonDigital\ORM\DataTableFactory as DataTableFactory;
use AeonDigital\ORM\DataTable as DataTable;
use AeonDigital\DAL\DAL as DAL;

require_once __DIR__ . "/../../phpunit.php";




class t05DataTableFactoryFKTest extends TestCase
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

    public function test_constructor_ok()
    {
        $tgtPath = to_system_path(realpath(__DIR__) . "/datamodel/valid");
        $obj = new DataTableFactory($tgtPath, $this->provider_connection());
        $this->assertTrue(is_a($obj, DataTableFactory::class));

        $Usuario = $obj->createDataTable("UsuarioDoDominio");
        $this->assertTrue(is_a($Usuario, DataTable::class));


        // Efetua o SET de valores para as propriedades comuns
        // de um usuário.
        $Usuario->Ativo = true;
        $Usuario->Locale = "PT-BR";
        $Usuario->Nome = "Teste";

        $this->assertSame(true, $Usuario->Ativo);
        $this->assertSame("pt-BR", $Usuario->Locale);
        $this->assertSame("Teste", $Usuario->Nome);
        $this->assertSame(undefined, $Usuario->Sessao);
        $this->assertSame([], $Usuario->GruposDeSeguranca);


        // Adiciona uma nova seção de dados e adiciona informações
        // em suas propriedades básicas
        $Usuario->newSessao();
        $Usuario->Sessao->Login = "userlogin";
        $Usuario->Sessao->Aplicacao = "app01";
        $Usuario->Sessao->ProfileInUse = "profile";

        $this->assertSame("userlogin", $Usuario->Sessao->Login);
        $this->assertSame("app01", $Usuario->Sessao->Aplicacao);
        $this->assertSame("PROFILE", $Usuario->Sessao->ProfileInUse);


        // Adiciona 2 grupos de segurança na respectiva coleção do
        // objeto usuário.
        $Usuario->addGruposDeSeguranca(2);
        $this->assertSame(2, count($Usuario->GruposDeSeguranca));

        // Preenche as propriedades dos grupos de segurança
        $Usuario->GruposDeSeguranca(0)->Nome = "G01";
        $Usuario->GruposDeSeguranca(0)->Aplicacao = "App01";

        $Usuario->GruposDeSeguranca(1)->Nome = "G02";
        $Usuario->GruposDeSeguranca(1)->Aplicacao = "App02";

        $this->assertSame("G01",    $Usuario->GruposDeSeguranca(0)->Nome);
        $this->assertSame("App01",  $Usuario->GruposDeSeguranca(0)->Aplicacao);
        $this->assertSame("G02",    $Usuario->GruposDeSeguranca(1)->Nome);
        $this->assertSame("App02",  $Usuario->GruposDeSeguranca(1)->Aplicacao);

        $this->assertSame("G01",    $Usuario->GruposDeSeguranca[0]->Nome);
        $this->assertSame("App01",  $Usuario->GruposDeSeguranca[0]->Aplicacao);
        $this->assertSame("G02",    $Usuario->GruposDeSeguranca[1]->Nome);
        $this->assertSame("App02",  $Usuario->GruposDeSeguranca[1]->Aplicacao);
    }
}
