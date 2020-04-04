<?php
declare (strict_types=1);

use PHPUnit\Framework\TestCase;
use AeonDigital\DataModel\Tests\Concrete\DataModel as DataModel;
use AeonDigital\DataModel\Tests\Concrete\DataFieldCollection as DataFieldCollection;
use AeonDigital\DataModel\Tests\Concrete\ModelFactory as ModelFactory;

require_once __DIR__ . "/../../phpunit.php";





class t04ModelFactoryTest extends TestCase
{





    public function test_constructor_ok()
    {
        $obj = new ModelFactory();
        $this->assertTrue(is_a($obj, ModelFactory::class));
    }


    public function test_method_hasdatamodel()
    {
        $obj = new ModelFactory();

        $this->assertTrue($obj->hasDataModel("Aplicacao"));
        $this->assertTrue($obj->hasDataModel("FormularioDeContato"));
        $this->assertFalse($obj->hasDataModel("DoesNotExist"));
    }


    public function test_method_createdatamodel()
    {
        $obj = new ModelFactory();

        $Aplicacao = $obj->createDataModel("Aplicacao");
        $this->assertTrue(is_a($Aplicacao, DataModel::class));

        $Aplicacao->Nome = "GuideLine";
        $Aplicacao->Descricao = "Descricao da aplicação";

        $values = $Aplicacao->getValues();
        $this->assertTrue(key_exists("Nome", $values));
        $this->assertTrue(key_exists("Descricao", $values));
        $this->assertSame("GuideLine", $Aplicacao->Nome);
        $this->assertSame("Descricao da aplicação", $Aplicacao->Descricao);





        $Formulario = $obj->createDataModel("FormularioDeContato");
        $this->assertTrue(is_a($Formulario, DataModel::class));
        $Formulario->Nome = "Teste";
        $Formulario->Email = "teste@teste.com";
        $Formulario->Destinatarios = ["val1", "val2", "val3"];

        $values = $Formulario->getValues();
        $this->assertTrue(key_exists("Nome", $values));
        $this->assertTrue(key_exists("Email", $values));
        $this->assertTrue(key_exists("Destinatarios", $values));
        $this->assertSame("Teste", $Formulario->Nome);
        $this->assertSame("teste@teste.com", $Formulario->Email);
        $this->assertSame(["val1", "val2", "val3"], $Formulario->Destinatarios);




        $Aplicacao = $obj->createDataModel("Aplicacao", ["Nome" => "GuideLine", "Descricao" => "Descricao da aplicação"]);
        $this->assertTrue(is_a($Aplicacao, DataModel::class));

        $values = $Aplicacao->getValues();
        $this->assertTrue(key_exists("Nome", $values));
        $this->assertTrue(key_exists("Descricao", $values));
        $this->assertSame("GuideLine", $Aplicacao->Nome);
        $this->assertSame("Descricao da aplicação", $Aplicacao->Descricao);

    }
}
