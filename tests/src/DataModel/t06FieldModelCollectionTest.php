<?php
declare (strict_types=1);

use PHPUnit\Framework\TestCase;
use AeonDigital\DataModel\Tests\Concrete\DataFieldModelCollection as DataFieldModelCollection;
use AeonDigital\DataModel\Tests\Concrete\ModelFactory as ModelFactory;
use AeonDigital\DataModel\Tests\Concrete\DataModel as DataModel;

require_once __DIR__ . "/../../phpunit.php";





class t06FieldModelCollectionTest extends TestCase
{





    private function provider_field_model_aplicacao()
    {
        return new DataFieldModelCollection([
            "name"                      => "refAplicacao",
            "modelName"                 => "Aplicacao",
            "allowNull"                 => false,
            "readOnly"                  => true
        ],
        new ModelFactory());
    }





    //
    // CONSTRUCTOR
    //

    public function test_constructor_ok()
    {
        $obj = new DataFieldModelCollection([
            "name"                      => "refAplicacao",
            "modelName"                 => "Aplicacao"
        ],
        new ModelFactory());
        $this->assertTrue(is_a($obj, DataFieldModelCollection::class));
        $this->assertTrue($obj->isReference());
        $this->assertTrue($obj->isCollection());
    }





    //
    // VALIDATEVALUE
    //

    public function test_method_validatevalue_invalidvalue_undefined()
    {
        $obj = $this->provider_field_model_aplicacao();

        $val        = undefined;
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame([], $validateState);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.collection.expected.array", $collectionState);
    }


    public function test_method_validatevalue_invalidvalue_null()
    {
        $obj = $this->provider_field_model_aplicacao();

        $val        = null;
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame([], $validateState);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.collection.expected.array", $collectionState);
    }


    public function test_method_validatevalue_invalidvalue_empty()
    {
        $obj = $this->provider_field_model_aplicacao();

        $val        = "";
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame([], $validateState);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.collection.expected.array", $collectionState);
    }


    public function test_method_validatevalue_invalidvalue_canset()
    {
        $obj = $this->provider_field_model_aplicacao();

        $val        = [
            [
                "Nome" => "app01",
                "Descricao" => "descri01",
                "CNPJ" => "25.453.717/0001-10"
            ],
            [
                "Nome" => "app01",
                "Descricao" => "descri01",
                "CNPJ" => "25.453.717/0001-11"
            ]
        ];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);

        $this->assertSame(2, count($validateState));
        $this->assertSame("valid", $validateState[0]);

        $this->assertTrue(array_is_assoc($validateState[1]));
        $this->assertTrue(key_exists("Nome", $validateState[1]));
        $this->assertTrue(key_exists("Descricao", $validateState[1]));
        $this->assertTrue(key_exists("CNPJ", $validateState[1]));

        $this->assertSame("valid", $validateState[1]["Nome"]);
        $this->assertSame("valid", $validateState[1]["Descricao"]);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState[1]["CNPJ"]);

        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);
    }


    public function test_method_validatevalue()
    {
        $obj = $this->provider_field_model_aplicacao();

        $val        = [
            [
                "Nome" => "app01",
                "Descricao" => "descri01",
                "CNPJ" => "25.453.717/0001-10"
            ],
            [
                "Nome" => "app02",
                "Descricao" => "descri02",
                "CNPJ" => "84.701.148/0001-43"
            ]
        ];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);

        $this->assertSame(2, count($validateState));
        $this->assertSame("valid", $validateState[0]);
        $this->assertSame("valid", $validateState[1]);

        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $collectionState);
    }






    //
    // SETVALUE | GETVALUE
    //

    public function test_method_setvalue_validvalue()
    {
        $obj = $this->provider_field_model_aplicacao();


        $val = [
            [
                "Nome" => "app01",
                "Descricao" => "descri01",
                "CNPJ" => "25.453.717/0001-10"
            ],
            [
                "Nome" => "app02",
                "Descricao" => "descri02",
                "CNPJ" => "84.701.148/0001-43"
            ]
        ];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame(["valid", "valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid", "valid"], $validateState);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = [
            [
                "Nome" => "app01",
                "Descricao" => "descri01",
                "CNPJ" => "25453717000110"
            ],
            [
                "Nome" => "app02",
                "Descricao" => "descri02",
                "CNPJ" => "84701148000143"
            ]
        ];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }
}
