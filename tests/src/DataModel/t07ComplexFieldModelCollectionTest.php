<?php
declare (strict_types=1);

use PHPUnit\Framework\TestCase;
use AeonDigital\DataModel\Tests\Concrete\ModelFactory as ModelFactory;
use AeonDigital\DataModel\Tests\Concrete\DataModel as DataModel;

require_once __DIR__ . "/../../phpunit.php";






class t07ComplexFieldModelCollectionTest extends TestCase
{





    public function test_provider_objects()
    {
        $factory = new ModelFactory();

        $Aplicacao              = $factory->createDataModel("Aplicacao");
        $FormularioDeContato    = $factory->createDataModel("FormularioDeContato");
        $Usuario                = $factory->createDataModel("Usuario");
        $GrupoDeSeguranca       = $factory->createDataModel("GrupoDeSeguranca");


        $this->assertTrue(is_a($Aplicacao, DataModel::class));
        $this->assertSame("Aplicacao", $Aplicacao->getName());

        $this->assertTrue(is_a($FormularioDeContato, DataModel::class));
        $this->assertSame("FormularioDeContato", $FormularioDeContato->getName());

        $this->assertTrue(is_a($Usuario, DataModel::class));
        $this->assertSame("Usuario", $Usuario->getName());

        $this->assertTrue(is_a($GrupoDeSeguranca, DataModel::class));
        $this->assertSame("GrupoDeSeguranca", $GrupoDeSeguranca->getName());
    }






    //
    // SETVALUE | GETVALUE
    //

    public function test_method_setget_values_with_magicmethods()
    {
        $factory = new ModelFactory();

        $Usuario = $factory->createDataModel("Usuario");
        $Usuario->Nome = "User01";
        $Usuario->Login = "login01";

        $v      = $Usuario->isValid();
        $state  = $Usuario->getState();

        $this->assertTrue($v);
        $this->assertSame("valid", $state);

        $this->assertSame("User01", $Usuario->Nome);
        $this->assertSame("login01", $Usuario->Login);
        $this->assertSame(undefined, $Usuario->GrupoDeSeguranca);
        $this->assertSame(undefined, $Usuario->GrupoDeSeguranca());



        // Testa o autoset de uma instância "reference".
        $Usuario->newGrupoDeSeguranca();
        $this->assertNotSame(undefined, $Usuario->GrupoDeSeguranca);
        $this->assertTrue(is_a($Usuario->GrupoDeSeguranca(), DataModel::class));
        $this->assertSame(undefined, $Usuario->GrupoDeSeguranca->ApplicationName);
        $this->assertSame(undefined, $Usuario->GrupoDeSeguranca->NomeDoGrupo);

        $Usuario->GrupoDeSeguranca->ApplicationName = "App01";
        $Usuario->GrupoDeSeguranca->NomeDoGrupo = "Grupo01";

        $this->assertSame("App01", $Usuario->GrupoDeSeguranca->ApplicationName);
        $this->assertSame("Grupo01", $Usuario->GrupoDeSeguranca->NomeDoGrupo);


        $Usuario->newGrupoDeSeguranca(["ApplicationName" => "App02", "NomeDoGrupo" => "Grupo02"]);
        $this->assertSame("App02", $Usuario->GrupoDeSeguranca->ApplicationName);
        $this->assertSame("Grupo02", $Usuario->GrupoDeSeguranca->NomeDoGrupo);



        // Testa o autoset de uma instância "reference" que seja também uma "collection"
        $this->assertSame([], $Usuario->GrupoDeSeguranca->Usuarios);

        // Adiciona 2 novos itens na coleção
        $Usuario->GrupoDeSeguranca->addUsuarios(2);
        $this->assertSame(2, count($Usuario->GrupoDeSeguranca->Usuarios));
        $this->assertTrue(is_a($Usuario->GrupoDeSeguranca->Usuarios[0], DataModel::class));
        $this->assertTrue(is_a($Usuario->GrupoDeSeguranca->Usuarios[1], DataModel::class));

        // Seta os valores os objetos "Usuarios" pertencente ao "GrupoDeSeguranca".
        $Usuario->GrupoDeSeguranca->Usuarios(0)->Nome = "Usuario Filho 01";
        $Usuario->GrupoDeSeguranca->Usuarios(0)->Login = "loginfilho01";

        $Usuario->GrupoDeSeguranca->Usuarios(1)->Nome = "Usuario Filho 02";
        $Usuario->GrupoDeSeguranca->Usuarios(1)->Login = "loginfilho02";

        $this->assertSame("Usuario Filho 01", $Usuario->GrupoDeSeguranca->Usuarios(0)->Nome);
        $this->assertSame("loginfilho01", $Usuario->GrupoDeSeguranca->Usuarios(0)->Login);
        $this->assertSame("Usuario Filho 02", $Usuario->GrupoDeSeguranca->Usuarios(1)->Nome);
        $this->assertSame("loginfilho02", $Usuario->GrupoDeSeguranca->Usuarios(1)->Login);
        $this->assertSame("Usuario Filho 01", $Usuario->GrupoDeSeguranca->Usuarios[0]->Nome);
        $this->assertSame("loginfilho01", $Usuario->GrupoDeSeguranca->Usuarios[0]->Login);
        $this->assertSame("Usuario Filho 02", $Usuario->GrupoDeSeguranca->Usuarios[1]->Nome);
        $this->assertSame("loginfilho02", $Usuario->GrupoDeSeguranca->Usuarios[1]->Login);



        $val = $Usuario->getValues();
        $this->assertSame("User01", $val["Nome"]);
        $this->assertSame("login01", $val["Login"]);

        $this->assertSame("App02", $val["GrupoDeSeguranca"]["ApplicationName"]);
        $this->assertSame("Grupo02", $val["GrupoDeSeguranca"]["NomeDoGrupo"]);

        $this->assertSame("Usuario Filho 01", $val["GrupoDeSeguranca"]["Usuarios"][0]["Nome"]);
        $this->assertSame("loginfilho01", $val["GrupoDeSeguranca"]["Usuarios"][0]["Login"]);

        $this->assertSame("Usuario Filho 02", $val["GrupoDeSeguranca"]["Usuarios"][1]["Nome"]);
        $this->assertSame("loginfilho02", $val["GrupoDeSeguranca"]["Usuarios"][1]["Login"]);
    }


    public function test_method_setget_values()
    {
        $factory = new ModelFactory();
        $Usuario = $factory->createDataModel("Usuario");

        $setVal = [
            "Nome"  => "User 01",
            "Login" => "login01",
            "GrupoDeSeguranca" => [
                "ApplicationName"    => "App01",
                "NomeDoGrupo"       => "Grupo01",
                "Usuarios"          => [
                    [
                        "Nome" => "Usuario filho 1",
                        "Login" => "loginfilho1"
                    ],
                    [
                        "Nome" => "Usuario filho 2",
                        "Login" => "loginfilho2"
                    ]
                ]
            ]
        ];

        $r              = $Usuario->setValues($setVal);
        $initi          = $Usuario->isInitial();
        $v              = $Usuario->isValid();
        $realState      = $Usuario->getState();
        $validateState  = $Usuario->getLastValidateState();
        $validateCanSet = $Usuario->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertFalse($initi);
        $this->assertTrue($v);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $realState);
        $this->assertSame($realState, $validateState);


        $val = $Usuario->getValues();
        $this->assertSame("User 01", $val["Nome"]);
        $this->assertSame("login01", $val["Login"]);

        $this->assertSame("App01", $val["GrupoDeSeguranca"]["ApplicationName"]);
        $this->assertSame("Grupo01", $val["GrupoDeSeguranca"]["NomeDoGrupo"]);

        $this->assertSame("Usuario filho 1", $val["GrupoDeSeguranca"]["Usuarios"][0]["Nome"]);
        $this->assertSame("loginfilho1", $val["GrupoDeSeguranca"]["Usuarios"][0]["Login"]);

        $this->assertSame("Usuario filho 2", $val["GrupoDeSeguranca"]["Usuarios"][1]["Nome"]);
        $this->assertSame("loginfilho2", $val["GrupoDeSeguranca"]["Usuarios"][1]["Login"]);
    }

}
