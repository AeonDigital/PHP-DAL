<?php
declare (strict_types=1);

use PHPUnit\Framework\TestCase;
use AeonDigital\DataModel\Tests\Concrete\DataFieldModel as DataFieldModel;
use AeonDigital\DataModel\Tests\Concrete\ModelFactory as ModelFactory;
use AeonDigital\DataModel\Tests\Concrete\DataModel as DataModel;

require_once __DIR__ . "/../../phpunit.php";





class t05FieldModelTest extends TestCase
{





    private function provider_field_model_aplicacao()
    {
        return new DataFieldModel([
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
        $obj = new DataFieldModel([
            "name"                      => "refAplicacao",
            "modelName"                 => "Aplicacao"
        ],
        new ModelFactory());
        $this->assertTrue(is_a($obj, DataFieldModel::class));
        $this->assertTrue($obj->isReference());
    }


    public function test_constructor_fails_invalidmodelname()
    {
        $fail = false;
        try {
            $obj = new DataFieldModel([
                "name"                      => "refAplicacao",
                "modelName"                 => "InvalidModel"
            ],
            new ModelFactory());
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("The data model to be used is not provided by the \"iModelFactory\" instance [\"InvalidModel\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }





    //
    // GETMODELNAME
    //

    public function test_method_getmodelname()
    {
        $obj = $this->provider_field_model_aplicacao();
        $this->assertSame("Aplicacao", $obj->getModelName());
    }





    //
    // GETMODEL
    //

    public function test_method_getmodel()
    {
        $obj = $this->provider_field_model_aplicacao();
        $Aplicacao = $obj->getModel();
        $this->assertTrue(is_a($Aplicacao, DataModel::class));
    }





    //
    // VALIDATEVALUE
    //

    public function test_method_validatevalue_invalidvalue_undefined()
    {
        $obj = $this->provider_field_model_aplicacao();

        $val            = undefined;
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.value.not.allow.undefined", $validateState);
    }


    public function test_method_validatevalue_invalidvalue_null()
    {
        $obj = $this->provider_field_model_aplicacao();

        $val            = null;
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.value.not.allow.null", $validateState);
    }


    public function test_method_validatevalue_invalidvalue_empty()
    {
        $obj = $this->provider_field_model_aplicacao();

        $val            = "";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.value.not.allow.empty", $validateState);
    }


    public function test_method_validatevalue_iterableobject()
    {
        $obj = $this->provider_field_model_aplicacao();

        $val            = [
            "Nome" => "app01",
            "Descricao" => "descri01",
            "CNPJ" => "25.453.717/0001-10"
        ];
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);
    }


    public function test_method_validatevalue_imodel()
    {
        $obj = $this->provider_field_model_aplicacao();

        // Inicia um novo modelo de dados
        $inst = $obj->getModel();

        // Avalia as condições iniciais
        $initi          = $inst->isInitial();
        $v              = $inst->isValid();
        $realState      = $inst->getState();
        $validateState  = $inst->getLastValidateState();


        $this->assertTrue($initi);
        $this->assertFalse($v);
        $this->assertTrue(is_array($realState));
        $this->assertSame(3, count($realState));
        $this->assertSame("error.dm.field.value.not.allow.undefined", $realState["Nome"]);
        $this->assertSame("error.dm.field.value.not.allow.undefined", $realState["Descricao"]);
        $this->assertSame("valid", $realState["CNPJ"]);
        $this->assertSame(null, $validateState);



        $val            = [
            "Nome"          => "valid name",
            "Descricao"     => "uma descrição qualquer",
            "CNPJ"          => "25.453.717/0001-10"
        ];
        $r              = $inst->setValues($val);
        $initi          = $inst->isInitial();
        $v              = $inst->isValid();
        $realState      = $inst->getState();
        $validateState  = $inst->getLastValidateState();
        $validateCanSet = $inst->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertFalse($initi);
        $this->assertTrue($v);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected = [
            "Nome"          => "valid name",
            "Descricao"     => "uma descrição qualquer",
            "CNPJ"          => "25.453.717/0001-10"
        ];
        $formatedExpected = [
            "Nome"          => "valid name",
            "Descricao"     => "uma descrição qualquer",
            "CNPJ"          => "25.453.717/0001-10"
        ];
        $storageExpected = [
            "Nome"          => "valid name",
            "Descricao"     => "uma descrição qualquer",
            "CNPJ"          => "25453717000110"
        ];

        $this->assertSame($rawExpected, $inst->getRawValues());
        $this->assertSame($formatedExpected, $inst->getValues());
        $this->assertSame($storageExpected, $inst->getStorageValues());




        $val            = $inst;
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);
    }






    //
    // SETVALUE | GETVALUE
    //

    public function test_method_setvalue_validvalue()
    {
        $obj = $this->provider_field_model_aplicacao();

        $val = $obj->getValue();
        $this->assertSame(undefined, $val);




        $val = [
            "Nome" => "app01",
            "Descricao" => "descri01",
            "CNPJ" => "25.453.717/0001-10"
        ];
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = [
            "Nome" => "app01",
            "Descricao" => "descri01",
            "CNPJ" => "25453717000110"
        ];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());




        // Reset não deve funcionar pois o campo é readonly
        $val = [
            "Nome" => "app02",
            "Descricao" => "descri02",
            "CNPJ" => "25.453.717/0001-10"
        ];
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertFalse($validateCanSet);
        $this->assertSame("valid", $realState);
        $this->assertSame("error.dm.field.value.constraint.read.only", $validateState);

        $rawExpected        = [
            "Nome" => "app01",
            "Descricao" => "descri01",
            "CNPJ" => "25.453.717/0001-10"
        ];
        $formatedExpected   = [
            "Nome" => "app01",
            "Descricao" => "descri01",
            "CNPJ" => "25.453.717/0001-10"
        ];
        $storageExpected    = [
            "Nome" => "app01",
            "Descricao" => "descri01",
            "CNPJ" => "25453717000110"
        ];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_with_model()
    {
        $obj = $this->provider_field_model_aplicacao();

        $val = $obj->getValue();
        $this->assertSame(undefined, $val);

        $inst = $obj->getModel();
        $inst->setValues([
            "Nome" => "app03",
            "Descricao" => "descri03",
            "CNPJ" => "25453717/0001-10"
        ]);


        $r              = $obj->setValue($inst);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $inst;
        $formatedExpected   = [
            "Nome" => "app03",
            "Descricao" => "descri03",
            "CNPJ" => "25.453.717/0001-10"
        ];
        $storageExpected    = [
            "Nome" => "app03",
            "Descricao" => "descri03",
            "CNPJ" => "25453717000110"
        ];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }

}
