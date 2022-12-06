<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use AeonDigital\ORM\DataTableFactory as DataTableFactory;
use AeonDigital\DAL\DAL as DAL;
use AeonDigital\ORM\Schema as Schema;

require_once __DIR__ . "/../../phpunit.php";



class t07DataTableORMTest extends TestCase
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
                $con["dbUserPassword"]
            );
        }
        return $this->useConnection;
    }



    private function provider_factory(): DataTableFactory
    {
        $tgtPath = to_system_path(realpath(__DIR__) . "/datamodel/valid");
        $factory = new DataTableFactory($tgtPath, $this->provider_connection());

        $schema = new Schema($factory);
        if ($schema->listDataBaseTables() === null) {
            $schema->executeCreateSchema();
        }

        return $factory;
    }



    private function provider_truncateTable($factory, $tableName)
    {
        $strSQL = "DELETE FROM $tableName;";
        $r = $factory->getDAL()->executeInstruction($strSQL);
        $this->assertTrue($r);

        $strSQL = "ALTER TABLE $tableName AUTO_INCREMENT=1";
        $r = $factory->getDAL()->executeInstruction($strSQL);
        $this->assertTrue($r);
    }





    //
    // COUNTROWS | HASID
    //

    public function test_methods_countrows()
    {
        $obj = $this->provider_factory();

        $this->provider_truncateTable($obj, "Cidade");
        $Cidade = $obj->createDataTable("Cidade");
        $c      = $Cidade->countRows();
        $this->assertSame(0, $c);



        $strSQL = " INSERT INTO
                        Cidade
                            (Nome, Estado, Capital)
                        VALUES
                            ('Cidade01', 'AA', 1),
                            ('Cidade02', 'BB', 0),
                            ('Cidade03', 'BB', 0),
                            ('Cidade04', 'AA', 0);";
        $r = $obj->getDAL()->executeInstruction($strSQL);
        $this->assertTrue($r);

        $c = $Cidade->countRows();
        $this->assertSame(4, $c);


        $this->assertTrue($Cidade->hasId(1));
        $this->assertTrue($Cidade->hasId(2));
        $this->assertTrue($Cidade->hasId(3));
        $this->assertTrue($Cidade->hasId(4));
        $this->assertFalse($Cidade->hasId(5));



        $this->provider_truncateTable($obj, "Cidade");
        $Cidade = $obj->createDataTable("Cidade");
        $c      = $Cidade->countRows();
        $this->assertSame(0, $c);


        //
        $this->assertNull($Cidade->getLastDALError());
    }





    //
    // SAVE | INSERT | UPDATE -> simple object
    //

    private function provider_prepare_single_cidade()
    {
        $obj = $this->provider_factory();

        $this->provider_truncateTable($obj, "Cidade");
        $Cidade = $obj->createDataTable("Cidade");
        $c      = $Cidade->countRows();
        $this->assertSame(0, $c);


        $this->provider_truncateTable($obj, "EnderecoPostal");
        $EnderecoPostal = $obj->createDataTable("EnderecoPostal");
        $c              = $EnderecoPostal->countRows();
        $this->assertSame(0, $c);


        $newCidade = [
            "Nome"      => "Cidade00",
            "Estado"    => "AA",
            "Capital"   => false
        ];
        $r = $Cidade->setValues($newCidade);
        $v = $Cidade->isValid();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertEquals("valid", $Cidade->getState());

        return $Cidade;
    }


    public function test_method_save_new_simple_object()
    {
        $Cidade = $this->provider_prepare_single_cidade();

        $r = $Cidade->insert();
        $this->assertTrue($r);
        $this->assertSame(1, $Cidade->Id);
    }


    public function test_method_save_update_simple_object()
    {
        $Cidade = $this->provider_prepare_single_cidade();

        // Testa a falha de um update quando o Id ainda não está definido.
        $r = $Cidade->update();
        $this->assertFalse($r);


        // Insere o novo objeto e ganha um Id
        $r = $Cidade->insert();
        $this->assertTrue($r);
        $this->assertSame(1, $Cidade->Id);


        // Altera os dados do mesmo
        $Cidade->Nome = "Cidade Atualizada";
        $Cidade->Capital = true;

        // Testa a falha de um insert quando o Id já está definido.
        $r = $Cidade->insert();
        $this->assertFalse($r);


        // Atualiza os dados
        $r = $Cidade->update();
        $this->assertTrue($r);
    }





    //
    // SAVE | INSERT | UPDATE -> single reference
    // with 1-1 reference
    //

    private function provider_prepare_usuario_with_sessao()
    {
        $obj = $this->provider_factory();

        $this->provider_truncateTable($obj, "UsuarioDoDominio");
        $Usuario        = $obj->createDataTable("UsuarioDoDominio");
        $c              = $Usuario->countRows();
        $this->assertSame(0, $c);


        $this->provider_truncateTable($obj, "SessaoDeAcesso");
        $SessaoDeAcesso = $obj->createDataTable("SessaoDeAcesso");
        $c              = $SessaoDeAcesso->countRows();
        $this->assertSame(0, $c);


        $newUsuario = [
            "Nome"          => "Usuario 01",
            "Genero"        => "Cis",
            "Login"         => "userlogin@email.com",
            "ShortLogin"    => "userlogin",
            "Senha"         => "12345678",
            "EmailContato"  => "usercontato@email.com",
            "ValorReal"     => 10,
            "Sessao"        => [
                "Login"             => "userlogin@email.com",
                "Aplicacao"         => "Application",
                "ProfileInUse"      => "admin",
                "SessionTimeOut"    => new DateTime(),
                "Ip"                => "1.1.1.1",
                "Browser"           => "test",
                "SessionID"         => "uniqueIDSession"
            ]
        ];
        $r = $Usuario->setValues($newUsuario);
        $v = $Usuario->isValid();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertEquals("valid", $Usuario->getState());

        return $Usuario;
    }


    public function test_method_save_new_relation_single()
    {
        $Usuario = $this->provider_prepare_usuario_with_sessao();

        $r = $Usuario->insert();
        $this->assertTrue($r);
        $this->assertSame(1, $Usuario->Id);
        $this->assertSame(1, $Usuario->Sessao()->Id);
    }


    public function test_method_save_update_relation_single()
    {
        $Usuario = $this->provider_prepare_usuario_with_sessao();

        $r = $Usuario->insert();
        $this->assertTrue($r);
        $this->assertSame(1, $Usuario->Id);
        $this->assertSame(1, $Usuario->Sessao()->Id);

        $Usuario->Nome = "Novo Nome de usuário";
        $Usuario->Sessao()->ProfileInUse = "DEV";

        $r = $Usuario->update();
        $this->assertTrue($r);
        $this->assertSame("Novo Nome de usuário", $Usuario->Nome);
        $this->assertSame("DEV", $Usuario->Sessao()->ProfileInUse);
    }





    //
    // SAVE | INSERT | UPDATE -> collection reference
    // with 1-N reference
    //

    private function provider_prepare_cidade_with_enderecopostal()
    {
        $obj = $this->provider_factory();

        $this->provider_truncateTable($obj, "Cidade");
        $Cidade             = $obj->createDataTable("Cidade");
        $c                  = $Cidade->countRows();
        $this->assertSame(0, $c);


        $this->provider_truncateTable($obj, "EnderecoPostal");
        $EnderecoPostal     = $obj->createDataTable("EnderecoPostal");
        $c                  = $EnderecoPostal->countRows();
        $this->assertSame(0, $c);


        $newCidade = [
            "Nome"      => "Cidade00",
            "Estado"    => "AA",
            "Capital"   => false,
            "EnderecosPostais" => [
                [
                    "CEP"               => "99.888-777",
                    "TipoDeEndereco"    => "Residencial",
                    "TipoDeLogradouro"  => "Rua",
                    "Logradouro"        => "Lugar de amar a Luiza",
                    "Numero"            => 33,
                    "Complemento"       => "-",
                    "Bairro"            => "Bairro das Ruas",
                    "Referencia"        => "--"
                ],
                [
                    "CEP"               => "77888999",
                    "TipoDeEndereco"    => "Residencial",
                    "TipoDeLogradouro"  => "Rua",
                    "Logradouro"        => "Lugar das Casas 01",
                    "Numero"            => 333,
                    "Complemento"       => null,
                    "Bairro"            => "Bairro das Ruas",
                    "Referencia"        => null
                ]
            ]
        ];
        $r = $Cidade->setValues($newCidade);
        $v = $Cidade->isValid();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertEquals("valid", $Cidade->getState());

        return $Cidade;
    }


    public function test_method_save_new_relation_collection()
    {
        $Cidade = $this->provider_prepare_cidade_with_enderecopostal();

        $r = $Cidade->insert();
        $this->assertTrue($r);
        $this->assertSame(1, $Cidade->Id);
        $this->assertSame(1, $Cidade->EnderecosPostais(0)->Id);
        $this->assertSame(2, $Cidade->EnderecosPostais(1)->Id);
    }


    public function test_method_save_update_relation_collection()
    {
        $Cidade = $this->provider_prepare_cidade_with_enderecopostal();

        // Insere o novo objeto e ganha um Id
        $r = $Cidade->insert();
        $this->assertTrue($r);
        $this->assertSame(1, $Cidade->Id);
        $this->assertSame(1, $Cidade->EnderecosPostais(0)->Id);
        $this->assertSame(2, $Cidade->EnderecosPostais(1)->Id);

        // Altera os dados
        $Cidade->Nome = "Cidade Atualizada";
        $Cidade->Capital = true;
        $Cidade->EnderecosPostais(0)->Numero = 555;
        $Cidade->EnderecosPostais(1)->Complemento = "Novo complemento";

        // Atualiza os dados
        $r = $Cidade->update();
        $this->assertTrue($r);
    }





    //
    // SAVE | INSERT | UPDATE -> bidirecional collection reference
    // with N-N reference
    //

    private function provider_prepare_usuario_with_grupodeseguranca()
    {
        $obj = $this->provider_factory();
        $this->provider_truncateTable($obj, "udd_to_gds");


        $this->provider_truncateTable($obj, "UsuarioDoDominio");
        $Usuario            = $obj->createDataTable("UsuarioDoDominio");
        $c                  = $Usuario->countRows();
        $this->assertSame(0, $c);


        $this->provider_truncateTable($obj, "SessaoDeAcesso");


        $this->provider_truncateTable($obj, "GrupoDeSeguranca");
        $GrupoDeSeguranca   = $obj->createDataTable("GrupoDeSeguranca");
        $c                  = $GrupoDeSeguranca->countRows();
        $this->assertSame(0, $c);


        $newUsuario = [
            "Nome"          => "Usuario LT",
            "Genero"        => "Cis",
            "Login"         => "userlogin@email.com",
            "ShortLogin"    => "userlogin",
            "Senha"         => "12345678",
            "EmailContato"  => "usercontato@email.com",
            "ValorReal"     => 10,
            "Sessao"        => [
                "Login"             => "userlogin@email.com",
                "Aplicacao"         => "Application",
                "ProfileInUse"      => "admin",
                "SessionTimeOut"    => new DateTime(),
                "Ip"                => "1.1.1.1",
                "Browser"           => "test",
                "SessionID"         => "uniqueIDSession"
            ],
            "GruposDeSeguranca" => [
                [
                    "Aplicacao"         => "Application",
                    "Nome"              => "G01",
                    "UseConnection"     => "defCon"
                ],
                [
                    "Aplicacao"         => "Application",
                    "Nome"              => "G02",
                    "UseConnection"     => "defCon",
                ]
            ]
        ];
        $r = $Usuario->setValues($newUsuario);
        $v = $Usuario->isValid();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertEquals("valid", $Usuario->getState());

        return $Usuario;
    }


    public function test_method_save_new_relation_bidirecional_collection()
    {
        $Usuario = $this->provider_prepare_usuario_with_grupodeseguranca();

        $r = $Usuario->insert();
        $this->assertTrue($r);
        $this->assertSame(1, $Usuario->Id);
        $this->assertSame(1, $Usuario->GruposDeSeguranca(0)->Id);
        $this->assertSame(2, $Usuario->GruposDeSeguranca(1)->Id);
    }


    public function test_method_save_update_relation_bidirecional_collection()
    {
        $Usuario = $this->provider_prepare_usuario_with_grupodeseguranca();

        $r = $Usuario->insert();
        $this->assertTrue($r);
        $this->assertSame(1, $Usuario->Id);
        $this->assertSame(1, $Usuario->GruposDeSeguranca(0)->Id);
        $this->assertSame(2, $Usuario->GruposDeSeguranca(1)->Id);

        // Altera os dados
        $Usuario->Nome = "User LT Atualizado";
        $Usuario->Ativo = false;
        $Usuario->GruposDeSeguranca(0)->Nome = "GLT01";
        $Usuario->GruposDeSeguranca(1)->UseConnection = "DEF01CON02";

        // Atualiza os dados
        $r = $Usuario->update();
        $this->assertTrue($r);
    }





    //
    // SELECT
    //

    private function provider_prepare_registers_to_test()
    {
        $obj = $this->provider_factory();
        $this->provider_truncateTable($obj, "udd_to_gds");


        $this->provider_truncateTable($obj, "UsuarioDoDominio");
        $Usuario            = $obj->createDataTable("UsuarioDoDominio");
        $c                  = $Usuario->countRows();
        $this->assertSame(0, $c);


        $this->provider_truncateTable($obj, "GrupoDeSeguranca");
        $GrupoDeSeguranca   = $obj->createDataTable("GrupoDeSeguranca");
        $c                  = $GrupoDeSeguranca->countRows();
        $this->assertSame(0, $c);


        $this->provider_truncateTable($obj, "SessaoDeAcesso");
        $SessaoDeAcesso     = $obj->createDataTable("SessaoDeAcesso");
        $c                  = $SessaoDeAcesso->countRows();
        $this->assertSame(0, $c);



        $newUsuario = [
            "Nome"          => "Usuario Select",
            "Genero"        => "Cis",
            "Login"         => "userlogin@email.com",
            "ShortLogin"    => "userlogin",
            "Senha"         => "12345678",
            "EmailContato"  => "usercontato@email.com",
            "ValorReal"     => 10,
            "Sessao"        => [
                "Login"             => "userlogin@email.com",
                "Aplicacao"         => "Application",
                "ProfileInUse"      => "adminSelect",
                "SessionTimeOut"    => new DateTime(),
                "Ip"                => "1.1.1.1",
                "Browser"           => "test",
                "SessionID"         => "uniqueIDSession"
            ],
            "GruposDeSeguranca" => [
                [
                    "Aplicacao"         => "Application",
                    "Nome"              => "SG01",
                    "UseConnection"     => "defCon"
                ],
                [
                    "Aplicacao"         => "Application",
                    "Nome"              => "SG02",
                    "UseConnection"     => "defCon",
                ]
            ]
        ];
        $r = $Usuario->setValues($newUsuario);
        $v = $Usuario->isValid();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertEquals("valid", $Usuario->getState());

        $r = $Usuario->insert();
        $this->assertTrue($r);







        $this->provider_truncateTable($obj, "Cidade");
        $Cidade             = $obj->createDataTable("Cidade");
        $c                  = $Cidade->countRows();
        $this->assertSame(0, $c);


        $this->provider_truncateTable($obj, "EnderecoPostal");
        $EnderecoPostal     = $obj->createDataTable("EnderecoPostal");
        $c                  = $EnderecoPostal->countRows();
        $this->assertSame(0, $c);


        $newCidade = [
            "Nome"      => "Cidade Select",
            "Estado"    => "AA",
            "Capital"   => false,
            "EnderecosPostais" => [
                [
                    "CEP"               => "99.888-777",
                    "TipoDeEndereco"    => "Residencial",
                    "TipoDeLogradouro"  => "Rua",
                    "Logradouro"        => "Lugar de amar a Luiza",
                    "Numero"            => 33,
                    "Complemento"       => "-",
                    "Bairro"            => "Bairro das Ruas",
                    "Referencia"        => "--"
                ],
                [
                    "CEP"               => "77888999",
                    "TipoDeEndereco"    => "Residencial",
                    "TipoDeLogradouro"  => "Rua",
                    "Logradouro"        => "Lugar das Casas 01",
                    "Numero"            => 333,
                    "Complemento"       => null,
                    "Bairro"            => "Bairro das Ruas",
                    "Referencia"        => null
                ]
            ]
        ];
        $r = $Cidade->setValues($newCidade);
        $v = $Cidade->isValid();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertEquals("valid", $Cidade->getState());

        $r = $Cidade->insert();
        $this->assertTrue($r);


        return $obj;
    }


    public function test_method_select()
    {
        $obj = $this->provider_prepare_registers_to_test();

        // Inicia uma nova instância
        $Cidade = $obj->createDataTable("Cidade");

        // Tenta carregar um Id que não existe
        $r = $Cidade->select(1);
        $this->assertTrue($r);
        $this->assertSame(1, $Cidade->Id);
        $this->assertSame("Cidade Select", $Cidade->Nome);
        $this->assertSame([], $Cidade->EnderecosPostais());

        // Carrega os objetos filhos [ coleção 1-N ]
        $r = $Cidade->loadEnderecosPostais();
        $this->assertTrue($r);
        $this->assertSame(2, count($Cidade->EnderecosPostais()));

        $this->assertSame("99.888-777", $Cidade->EnderecosPostais(0)->CEP);
        $this->assertSame("77.888-999", $Cidade->EnderecosPostais(1)->CEP);





        $Usuario = $obj->createDataTable("UsuarioDoDominio");
        $r = $Usuario->select(1);
        $this->assertTrue($r);
        $this->assertSame(1, $Usuario->Id);
        $this->assertSame("Usuario Select", $Usuario->Nome);
        $this->assertSame(undefined, $Usuario->Sessao());
        $this->assertSame([], $Usuario->GruposDeSeguranca());

        // Carrega o objeto filho [ coleção 1-1 ]
        $r = $Usuario->loadSessao();
        $this->assertTrue($r);
        $this->assertSame(1, $Usuario->Sessao()->Id);
        $this->assertSame("ADMINSELECT", $Usuario->Sessao()->ProfileInUse);

        // Carrega os objetos filhos [ coleção bidirecional N-N ]
        $r = $Usuario->loadGruposDeSeguranca();
        $this->assertTrue($r);
        $this->assertSame(2, count($Usuario->GruposDeSeguranca()));
        $this->assertSame(1, $Usuario->GruposDeSeguranca(0)->Id);
        $this->assertSame("SG01", $Usuario->GruposDeSeguranca(0)->Nome);
        $this->assertSame(2, $Usuario->GruposDeSeguranca(1)->Id);
        $this->assertSame("SG02", $Usuario->GruposDeSeguranca(1)->Nome);
    }





    //
    // DELETE
    //

    public function test_method_delete()
    {
        $obj = $this->provider_prepare_registers_to_test();

        $Usuario = $obj->createDataTable("UsuarioDoDominio");
        $r = $Usuario->select(1);
        $this->assertTrue($r);

        $r = $Usuario->loadSessao();
        $this->assertTrue($r);
        $r = $Usuario->loadGruposDeSeguranca();
        $this->assertTrue($r);


        $this->assertSame(1, $Usuario->Id);
        $this->assertSame("Usuario Select", $Usuario->Nome);
        $this->assertSame(1, $Usuario->Sessao()->Id);
        $this->assertSame("ADMINSELECT", $Usuario->Sessao()->ProfileInUse);
        $this->assertSame(2, count($Usuario->GruposDeSeguranca()));
        $this->assertSame(1, $Usuario->GruposDeSeguranca(0)->Id);
        $this->assertSame("SG01", $Usuario->GruposDeSeguranca(0)->Nome);
        $this->assertSame(2, $Usuario->GruposDeSeguranca(1)->Id);
        $this->assertSame("SG02", $Usuario->GruposDeSeguranca(1)->Nome);


        $r = $Usuario->delete();
        $this->assertTrue($r);
        $this->assertSame(0, $Usuario->Id);
        $this->assertTrue($Usuario->Sessao()->isInitial());
        $this->assertSame([], $Usuario->GruposDeSeguranca());
    }





    //
    // ATTATCH | DETACH
    //

    private function provider_prepare_attachdetach_to_test()
    {
        $obj = $this->provider_prepare_registers_to_test();

        $Usuario            = $obj->createDataTable("UsuarioDoDominio");
        $GrupoDeSeguranca   = $obj->createDataTable("GrupoDeSeguranca");
        $SessaoDeAcesso     = $obj->createDataTable("SessaoDeAcesso");


        $newUsuario = [
            "Nome"          => "Usuario AttatchDetach",
            "Genero"        => "Cis",
            "Login"         => "attachdetach@email.com",
            "ShortLogin"    => "attachdetach",
            "Senha"         => "12345678",
            "EmailContato"  => "usercontato@email.com",
            "ValorReal"     => 10,
            "Sessao"        => [
                "Login"             => "attachdetach@email.com",
                "Aplicacao"         => "Application",
                "ProfileInUse"      => "adminSelect",
                "SessionTimeOut"    => new DateTime(),
                "Ip"                => "1.1.1.1",
                "Browser"           => "test",
                "SessionID"         => "anotherIDSession"
            ],
            "GruposDeSeguranca" => [
                [
                    "Aplicacao"         => "Application",
                    "Nome"              => "SG01-01",
                    "UseConnection"     => "defCon"
                ],
                [
                    "Aplicacao"         => "Application",
                    "Nome"              => "SG02-02",
                    "UseConnection"     => "defCon",
                ]
            ]
        ];
        $r = $Usuario->setValues($newUsuario);
        $v = $Usuario->isValid();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertEquals("valid", $Usuario->getState());

        $r = $Usuario->insert();
        $this->assertTrue($r);







        $Cidade         = $obj->createDataTable("Cidade");
        $EnderecoPostal = $obj->createDataTable("EnderecoPostal");


        $newCidade = [
            "Nome"      => "Cidade AttatchDetach",
            "Estado"    => "BB",
            "Capital"   => false,
            "EnderecosPostais" => [
                [
                    "CEP"               => "99.888-777",
                    "TipoDeEndereco"    => "Residencial",
                    "TipoDeLogradouro"  => "Rua",
                    "Logradouro"        => "AttatchDetach 01",
                    "Numero"            => 99,
                    "Complemento"       => "-",
                    "Bairro"            => "Bairro das Ruas",
                    "Referencia"        => "--"
                ],
                [
                    "CEP"               => "77888999",
                    "TipoDeEndereco"    => "Residencial",
                    "TipoDeLogradouro"  => "Rua",
                    "Logradouro"        => "AttatchDetach 02",
                    "Numero"            => 56,
                    "Complemento"       => null,
                    "Bairro"            => "Bairro das Ruas",
                    "Referencia"        => null
                ]
            ]
        ];
        $r = $Cidade->setValues($newCidade);
        $v = $Cidade->isValid();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertEquals("valid", $Cidade->getState());

        $r = $Cidade->insert();
        $this->assertTrue($r);


        return $obj;
    }


    public function test_method_attatch_detach()
    {
        $obj = $this->provider_prepare_attachdetach_to_test();

        // Gera uma nova sessão de acesso desvinculada de qualquer usuário
        $SessaoDeAcesso = $obj->createDataTable("SessaoDeAcesso");
        $newSession = [
            "Login"             => "userlogin@email.com",
            "Aplicacao"         => "Application",
            "ProfileInUse"      => "adminSelect",
            "SessionTimeOut"    => new DateTime(),
            "Ip"                => "1.1.1.1",
            "Browser"           => "test",
            "SessionID"         => "oneMoreSession"
        ];
        $r = $SessaoDeAcesso->setValues($newSession);
        $v = $SessaoDeAcesso->isValid();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertEquals("valid", $SessaoDeAcesso->getState());

        $r = $SessaoDeAcesso->insert();
        $this->assertTrue($r);





        // A partir do objeto filho,
        // Modifica seu dono em uma relação 1-1
        $SessaoDeAcesso = $obj->createDataTable("SessaoDeAcesso");
        $r = $SessaoDeAcesso->select(1);
        $this->assertTrue($r);
        $r = $SessaoDeAcesso->attachWith("UsuarioDoDominio", $SessaoDeAcesso->Id);
        $this->assertTrue($r);

        // A partir do objeto dono,
        // Retoma para si o vínculo com o objeto filho em uma relação 1-1
        $Usuario = $obj->createDataTable("UsuarioDoDominio");
        $r = $Usuario->select(2);
        $this->assertTrue($r);
        $r = $Usuario->attachWith("SessaoDeAcesso", 2);
        $this->assertTrue($r);

        // Desfaz o vinculo totalmente
        // Não precisa denominar o Id pois é uma relação 1-1
        // Neste caso, a exclusão não ocorre por força da constraint que exige que
        // os registros de 'UsuarioDoDominio' possuam um registro do tipo 'SessaoDeAcesso'
        $r = $Usuario->detachWith("SessaoDeAcesso");
        $this->assertFalse($r);





        // A partir de um objeto dono,
        // Adiciona novos itens em sua coleção
        $Cidade = $obj->createDataTable("Cidade");
        $r = $Cidade->select(1);
        $this->assertTrue($r);
        $r = $Cidade->attachWith("EnderecoPostal", 3);
        $this->assertTrue($r);
        $r = $Cidade->attachWith("EnderecoPostal", 4);
        $this->assertTrue($r);

        // A partir de um objeto filho
        // Redefine seu vínculo a um novo pai
        $EnderecoPostal = $obj->createDataTable("EnderecoPostal");
        $r = $EnderecoPostal->select(1);
        $this->assertTrue($r);
        $r = $EnderecoPostal->attachWith("Cidade", 2);
        $this->assertTrue($r);

        // A partir de um objeto filho
        // Remove seu vínculo com o objeto pai
        $r = $EnderecoPostal->detachWith("Cidade");
        $this->assertTrue($r);

        // A partir de um objeto dono,
        // Remove 1 item em especial da coleção
        $r = $Cidade->detachWith("EnderecoPostal", 2);
        $this->assertTrue($r);

        // A partir de um objeto dono,
        // Remove todos os itens da coleção
        $r = $Cidade->detachWith("EnderecoPostal");
        $this->assertTrue($r);





        // A partir de 1 dos lados de uma relação N-N,
        // Adiciona novo item para a coleção
        $r = $Usuario->attachWith("GrupoDeSeguranca", 1);
        $this->assertTrue($r);

        // A partir do outro lado da relação N-N,
        // Adiciona novo item para a coleção
        $GrupoDeSeguranca = $obj->createDataTable("GrupoDeSeguranca");
        $r = $GrupoDeSeguranca->select(2);
        $this->assertTrue($r);
        $r = $GrupoDeSeguranca->attachWith("UsuarioDoDominio", 2);
        $this->assertTrue($r);

        // Remove AMBOS relacionamentos recentemente criados.
        $r = $Usuario->detachWith("GrupoDeSeguranca", 1);
        $this->assertTrue($r);
        $r = $GrupoDeSeguranca->detachWith("UsuarioDoDominio", 2);
        $this->assertTrue($r);

        // Remove todos os vínculos de todos os lados.
        $r = $Usuario->detachWith("GrupoDeSeguranca");
        $this->assertTrue($r);
        $r = $GrupoDeSeguranca->detachWith("UsuarioDoDominio");
        $this->assertTrue($r);
    }





    //
    // SELECTPARENIDOF
    //

    public function test_method_selectparentidof()
    {
        $obj = $this->provider_prepare_attachdetach_to_test();

        // Testa a partir de um FILHO em uma
        // relação 1-1
        $SessaoDeAcesso = $obj->createDataTable("SessaoDeAcesso");
        $r = $SessaoDeAcesso->select(2);
        $this->assertTrue($r);
        $pId = $SessaoDeAcesso->selectParentIdOf("UsuarioDoDominio");
        $this->assertSame(2, $pId);


        // Testa a partir de um FILHO em uma
        // relação 1-N
        $EnderecoPostal = $obj->createDataTable("EnderecoPostal");
        $r = $EnderecoPostal->select(2);
        $this->assertTrue($r);
        $pId = $EnderecoPostal->selectParentIdOf("Cidade");
        $this->assertSame(1, $pId);
    }





    //
    // SELECT WITH CHILDS
    // DELETE CHILDS
    //

    public function test_method_select_with_childs()
    {
        $obj = $this->provider_prepare_attachdetach_to_test();

        $Usuario = $obj->createDataTable("UsuarioDoDominio");
        $r = $Usuario->select(2, true);
        $this->assertTrue($r);

        $this->assertSame(2, $Usuario->Id);
        $this->assertSame(2, $Usuario->Sessao()->Id);
        $this->assertSame(2, count($Usuario->GruposDeSeguranca()));

        $r = $Usuario->deleteGruposDeSeguranca();
        $this->assertTrue($r);
        $this->assertSame([], $Usuario->GruposDeSeguranca());

        // Não é possível excluir este regstro por que a tabela
        // 'UsuarioDoDominio' exige referencia a um registro do tipo 'SessaoDeAcesso'
        $r = $Usuario->deleteSessao();
        $this->assertFalse($r);
        $this->assertSame(2, $Usuario->Sessao()->Id);
    }
}
