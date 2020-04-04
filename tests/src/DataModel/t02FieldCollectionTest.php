<?php
declare (strict_types=1);

use PHPUnit\Framework\TestCase;
use AeonDigital\DataModel\Tests\Concrete\DataFieldCollection as DataFieldCollection;

require_once __DIR__ . "/../../phpunit.php";







class t02FieldCollectionTest extends TestCase
{





    //
    // ISCOLLECTION
    //

    public function test_constructor_iscollection()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String"
        ]);
        $this->assertTrue($obj->isCollection());
    }





    //
    // VALIDATEVALUE
    //

    public function test_method_validatevalue_invalidvalue_notarray()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val        = "not array";
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame([], $validateState);
        $this->assertSame("error.dm.field.collection.expected.array", $collectionState);
    }


    public function test_method_validatevalue_invalidvalue_undefined()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val        = [undefined];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["error.dm.field.collection.member.not.allow.undefined"], $validateState);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);
    }


    public function test_method_validatevalue_invalidvalue_notallownull()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val        = [null];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["error.dm.field.collection.member.not.allow.null"], $validateState);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);
    }


    public function test_method_validatevalue_invalidvalue_notallowempty()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val        = [""];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["error.dm.field.collection.member.not.allow.empty"], $validateState);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);
    }


    public function test_method_validatevalue_empty_array()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val        = [];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame([], $validateState);
        $this->assertSame("valid", $collectionState);
    }


    public function test_method_validatevalue_validvalue()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val        = ["validvalue"];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid"], $validateState);
        $this->assertSame("valid", $collectionState);



        // Deve passar pois a coleção informada é válida
        $val        = ["val1", "val2"];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid", "valid"], $validateState);
        $this->assertSame("valid", $collectionState);



        // Deve falhar e expor no array de erros
        // a coleção de resultados da validação encontrada
        $val        = ["val1", "val2", undefined, null, ""];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(5, count($validateState));
        $this->assertSame("valid", $validateState[0]);
        $this->assertSame("valid", $validateState[1]);
        $this->assertSame("error.dm.field.collection.member.not.allow.undefined", $validateState[2]);
        $this->assertSame("error.dm.field.collection.member.not.allow.null", $validateState[3]);
        $this->assertSame("error.dm.field.collection.member.not.allow.empty", $validateState[4]);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);



        // Deve falhar e expor no array de erros
        // a coleção de resultados da validação encontrada
        $val        = ["val1", "val2", new StdClass(), new StdClass()];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(4, count($validateState));
        $this->assertSame("valid", $validateState[0]);
        $this->assertSame("valid", $validateState[1]);
        $this->assertSame("error.dm.field.value.invalid.type", $validateState[2]);
        $this->assertSame("error.dm.field.value.invalid.type", $validateState[3]);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);


        // Deve passar pois a coleção informada é válida
        $val        = ["val1", "val2", "big value to this field"];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $validateCanSet     = $obj->getLastValidateCanSet();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid", "valid", "error.dm.field.value.constraint.length.violated"], $validateState);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);
    }





    //
    // DEFAULT
    //

    public function test_constructor_fails_default_invalid()
    {
        $fail = false;
        try {
            $obj = new DataFieldCollection([
                "name"                      => "validName",
                "type"                      => "String",
                "default"                   => "invalid value"
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("The given \"default\" value is invalid.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok_default()
    {
        // Valor não definido, esperado "[]"
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
        ]);
        $this->assertSame([], $obj->getDefault());



        // Valor definido explicitamente.
        $testValue = ["validvalue"];

        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "default"                   => $testValue,
        ]);
        $this->assertSame($testValue, $obj->getDefault());
        $this->assertTrue(is_array($obj->getDefault()));
        $this->assertSame(1, count($obj->getDefault()));
        $this->assertSame("validvalue", $obj->getDefault()[0]);


        // Valor definido explicitamente.
        $testValue = ["20-01-2018"];

        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "DateTime",
            "inputFormat"               => "Brasil.Dates.Date",
            "default"                   => $testValue,
        ]);
        $this->assertTrue(is_array($obj->getDefault()));
        $this->assertSame(1, count($obj->getDefault()));
        $this->assertTrue(is_a($obj->getDefault()[0], "\DateTime"));
        $this->assertSame($testValue[0], $obj->getDefault()[0]->format("d-m-Y"));
        $this->assertSame($testValue[0], $obj->getStorageValue()[0]->format("d-m-Y"));


        // Valor definido explicitamente.
        $testValue = [new \DateTime("2018-01-20")];

        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "DateTime",
            "default"                   => $testValue,
        ]);
        $this->assertTrue(is_array($obj->getDefault()));
        $this->assertSame(1, count($obj->getDefault()));
        $this->assertTrue(is_a($obj->getDefault()[0], "\DateTime"));
        $this->assertSame($testValue[0], $obj->getDefault()[0]);
        $this->assertSame($testValue[0], $obj->getValue()[0]);
    }





    //
    // ENUMERATOR
    //

    public function test_constructor_ok_enumerator()
    {
        $enum = ["val1", "val2", "val3"];

        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "enumerator"                => $enum
        ]);
        $this->assertSame($enum, $obj->getEnumerator());




        $val        = ["val1"];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid"], $validateState);
        $this->assertSame("valid", $collectionState);





        $val        = ["val1", "val3", "val3"];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid", "valid", "valid"], $validateState);
        $this->assertSame("valid", $collectionState);





        $val        = ["val1", "val0", "val3"];
        $expected   = null;

        $r                  = $obj->validateValue($val);
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(3, count($validateState));
        $this->assertSame("valid", $validateState[0]);
        $this->assertSame("error.dm.field.value.constraint.enumerator.violated", $validateState[1]);
        $this->assertSame("valid", $validateState[2]);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);
    }





    //
    // SETVALUE | GETVALUE
    //

    public function test_method_setvalue_invalidvalue_notarray()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);


        $val                = "not array";

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame([], $validateState);
        $this->assertSame("error.dm.field.collection.expected.array", $collectionState);

        $rawExpected        = [];
        $formatedExpected   = [];
        $storageExpected    = [];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_invalidvalue_undefined()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);


        $val        = [undefined];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["error.dm.field.collection.member.not.allow.undefined"], $validateState);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);


        $rawExpected        = [];
        $formatedExpected   = [];
        $storageExpected    = [];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_invalidvalue_notallownull()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);


        $val                = [null];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["error.dm.field.collection.member.not.allow.null"], $validateState);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);


        $rawExpected        = [];
        $formatedExpected   = [];
        $storageExpected    = [];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_invalidvalue_notallowempty()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);


        $val                = [""];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["error.dm.field.collection.member.not.allow.empty"], $validateState);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);


        $rawExpected        = [];
        $formatedExpected   = [];
        $storageExpected    = [];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_empty_array()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);


        $val                = [];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame([], $validateState);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = [];
        $formatedExpected   = [];
        $storageExpected    = [];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_validvalue()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);


        $val                = ["validvalue"];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame(["valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid"], $validateState);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = ["validvalue"];
        $formatedExpected   = ["validvalue"];
        $storageExpected    = ["validvalue"];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());





        // Deve passar pois a coleção informada é válida
        $val        = ["val1", "val2"];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame(["valid", "valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid", "valid"], $validateState);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = ["val1", "val2"];
        $formatedExpected   = ["val1", "val2"];
        $storageExpected    = ["val1", "val2"];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());







        // Deve falhar e expor no array de erros
        // a coleção de resultados da validação encontrada
        $val        = ["val1", "val2", undefined, null, ""];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame(["valid", "valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(5, count($validateState));
        $this->assertSame("valid", $validateState[0]);
        $this->assertSame("valid", $validateState[1]);
        $this->assertSame("error.dm.field.collection.member.not.allow.undefined", $validateState[2]);
        $this->assertSame("error.dm.field.collection.member.not.allow.null", $validateState[3]);
        $this->assertSame("error.dm.field.collection.member.not.allow.empty", $validateState[4]);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);

        $rawExpected        = ["val1", "val2"];
        $formatedExpected   = ["val1", "val2"];
        $storageExpected    = ["val1", "val2"];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());






        // Deve falhar e expor no array de erros
        // a coleção de resultados da validação encontrada
        $val        = ["val1", "val2", new StdClass(), new StdClass()];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame(["valid", "valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(4, count($validateState));
        $this->assertSame("valid", $validateState[0]);
        $this->assertSame("valid", $validateState[1]);
        $this->assertSame("error.dm.field.value.invalid.type", $validateState[2]);
        $this->assertSame("error.dm.field.value.invalid.type", $validateState[3]);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);

        $rawExpected        = ["val1", "val2"];
        $formatedExpected   = ["val1", "val2"];
        $storageExpected    = ["val1", "val2"];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());





        // Deve falhar na validação pois o valor quebra
        // o tamanho máximo do campo
        $val        = ["valid with big length"];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame(["error.dm.field.value.constraint.length.violated"], $realState);
        $this->assertSame("error.dm.field.collection.invalid.member", $collRealState);
        $this->assertSame(["error.dm.field.value.constraint.length.violated"], $validateState);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);

        $rawExpected        = ["valid with big length"];
        $formatedExpected   = ["valid with big length"];
        $storageExpected    = [];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_mixedtests()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => "Brasil.CPF"
        ]);

        // Deve passar na validação pois o valor é válido
        $val                = [];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame([], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame([], $validateState);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = [];
        $formatedExpected   = [];
        $storageExpected    = [];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());





        // Deve passar na validação pois o valor é válido
        $val                = ["189208640-98"];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame(["valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid"], $validateState);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = ["189208640-98"];
        $formatedExpected   = ["189.208.640-98"];
        $storageExpected    = ["18920864098"];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());





        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => "Brasil.Dates.Date"
        ]);

        // Deve passar na validação pois o valor é válido
        $val                = ["07-08-2018"];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame(["valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid"], $validateState);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = ["07-08-2018"];
        $formatedExpected   = ["07-08-2018"];
        $storageExpected    = ["07-08-2018"];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }





    //
    // DISTINCT
    //

    public function test_constructor_distinct()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String"
        ]);
        $this->assertTrue($obj->isCollection());
        $this->assertFalse($obj->collectionIsDistinct());


        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "distinct"                  => true
        ]);
        $this->assertTrue($obj->isCollection());
        $this->assertTrue($obj->collectionIsDistinct());
    }


    public function test_method_validatevalue_distinct()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "distinct"                  => true
        ]);
        $this->assertSame(true, $obj->isCollection());
        $this->assertSame(true, $obj->collectionIsDistinct());


        // Deve passar pois a coleção informada é válida e a
        // validação está preparada para este tipo de valor.
        $val                = ["val1", "val2"];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame(["valid", "valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid", "valid"], $validateState);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = ["val1", "val2"];
        $formatedExpected   = ["val1", "val2"];
        $storageExpected    = ["val1", "val2"];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());






        // Deve falhar pois a coleção informada possui valores
        // repetidos.
        $val                = ["val1", "val2", "val3", "val2"];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame(["valid", "valid", "valid", "valid"], $realState);
        $this->assertSame("error.dm.field.collection.constraint.distinct.violated", $collRealState);
        $this->assertSame(["valid", "valid", "valid", "valid"], $validateState);
        $this->assertSame("error.dm.field.collection.constraint.distinct.violated", $collectionState);


        $rawExpected        = ["val1", "val2", "val3", "val2"];
        $formatedExpected   = ["val1", "val2", "val3", "val2"];
        $storageExpected    = [];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());






        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "DateTime",
            "inputFormat"               => "Brasil.Dates.Date",
            "distinct"                  => true
        ]);
        $this->assertSame(true, $obj->isCollection());
        $this->assertSame(true, $obj->collectionIsDistinct());


        // Deve passar pois o valor corresponde ao
        // formato esperado.
        $val                = ["20-01-2018"];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame(["valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid"], $validateState);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = ["20-01-2018"];
        $formatedExpected   = ["20-01-2018"];
        $storageExpected    = [new DateTime("2018-01-20")];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected[0]->format("Y-m-d H:i:s"), $obj->getStorageValue()[0]->format("Y-m-d H:i:s"));





        $val                = [new DateTime("2018-01-20")];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame(["valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid"], $validateState);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = [new DateTime("2018-01-20")];
        $formatedExpected   = ["20-01-2018"];
        $storageExpected    = [new DateTime("2018-01-20")];

        $this->assertSame($rawExpected[0]->format("Y-m-d H:i:s"), $obj->getRawValue()[0]->format("Y-m-d H:i:s"));
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected[0]->format("Y-m-d H:i:s"), $obj->getStorageValue()[0]->format("Y-m-d H:i:s"));





        // Deve falhar pois, mesmos os valores estando definidos em
        // tipos de dados diferentes, internamente eles são verificados
        // e identificados como iguais
        $val                = ["20-01-2018", new DateTime("2018-01-20")];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame(["valid", "valid"], $realState);
        $this->assertSame("error.dm.field.collection.constraint.distinct.violated", $collRealState);
        $this->assertSame(["valid", "valid"], $validateState);
        $this->assertSame("error.dm.field.collection.constraint.distinct.violated", $collectionState);


        $rawExpected        = ["20-01-2018", new DateTime("2018-01-20")];
        $formatedExpected   = ["20-01-2018", "20-01-2018"];
        $storageExpected    = [];

        $this->assertSame($rawExpected[0], $obj->getRawValue()[0]);
        $this->assertSame($rawExpected[1]->format("Y-m-d H:i:s"), $obj->getRawValue()[1]->format("Y-m-d H:i:s"));
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }





    //
    // ADDVALUE
    //

    public function test_method_addvalue_general()
    {
        // Testa o funcionamento geral do método addValue
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "DateTime",
            "inputFormat"               => "Brasil.Dates.Date",
            "distinct"                  => true
        ]);



        // Deve funcionar pois o valor a ser adicionado é válido.
        $val                = "20-01-2018";

        $r                  = $obj->collectionAddValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame(["valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid"], $validateState);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = ["20-01-2018"];
        $formatedExpected   = ["20-01-2018"];
        $storageExpected    = [new DateTime("2018-01-20")];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected[0]->format("Y-m-d H:i:s"), $obj->getStorageValue()[0]->format("Y-m-d H:i:s"));




        // Deve incrementar a coleção mas vai torna-la inválida
        $val                = new DateTime("2018-01-20");

        $r                  = $obj->collectionAddValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame(["valid", "valid"], $realState);
        $this->assertSame("error.dm.field.collection.constraint.distinct.violated", $collRealState);
        $this->assertSame(["valid", "valid"], $validateState);
        $this->assertSame("error.dm.field.collection.constraint.distinct.violated", $collectionState);


        $rawExpected        = ["20-01-2018", $val];
        $formatedExpected   = ["20-01-2018", "20-01-2018"];
        $storageExpected    = [];

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected[0], $obj->getValue()[0]);
        $this->assertSame($formatedExpected[1], $obj->getValue()[1]);
        $this->assertSame($storageExpected, $obj->getStorageValue());




        // Deve substituir a coleção mas
        // falhar na validação e ao mesmo tempo setar a coleção como
        // inválida por possuir em um de seus membros um valor totalmente inválido.
        $val                = ["20-01-2018", new DateTime("2018-01-20"), "invalid value"];

        $r                  = $obj->setValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame(["valid", "valid"], $realState);
        $this->assertSame("error.dm.field.collection.constraint.distinct.violated", $collRealState);
        $this->assertSame(["valid", "valid", "error.dm.field.value.invalid.input.format"], $validateState);
        $this->assertSame("error.dm.field.collection.invalid.member", $collectionState);


        $rawExpected        = ["20-01-2018", new DateTime("2018-01-20")];
        $formatedExpected   = ["20-01-2018", "20-01-2018"];
        $storageExpected    = [];

        $this->assertSame($rawExpected[0], $obj->getRawValue()[0]);
        $this->assertSame($rawExpected[1]->format("Y-m-d H:i:s"), $obj->getRawValue()[1]->format("Y-m-d H:i:s"));
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());





        // Remove a primeira ocorrencia do valor repetido
        $obj->collectionUnsetValue("20-01-2018");

        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($v);
        $this->assertSame(["valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid"], $validateState);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = [new DateTime("2018-01-20")];
        $formatedExpected   = ["20-01-2018"];
        $storageExpected    = [new DateTime("2018-01-20")];

        $this->assertSame($rawExpected[0]->format("Y-m-d H:i:s"), $obj->getRawValue()[0]->format("Y-m-d H:i:s"));
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected[0]->format("Y-m-d H:i:s"), $obj->getStorageValue()[0]->format("Y-m-d H:i:s"));




        // Adiciona um novo valor, válido desta vez.
        // Deve funcionar pois o valor a ser adicionado é válido.
        $val                = "21-01-2018";

        $r                  = $obj->collectionAddValue($val);
        $v                  = $obj->isValid();
        $realState          = $obj->getState();
        $collRealState      = $obj->collectionGetState();
        $validateState      = $obj->getLastValidateState();
        $collectionState    = $obj->collectionGetLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame(["valid", "valid"], $realState);
        $this->assertSame("valid", $collRealState);
        $this->assertSame(["valid", "valid"], $validateState);
        $this->assertSame("valid", $collectionState);


        $rawExpected        = [new DateTime("2018-01-20"), "21-01-2018"];
        $formatedExpected   = ["20-01-2018", "21-01-2018"];
        $storageExpected    = [new DateTime("2018-01-20"), new DateTime("2018-01-21")];

        $this->assertSame($rawExpected[0]->format("Y-m-d H:i:s"), $obj->getRawValue()[0]->format("Y-m-d H:i:s"));
        $this->assertSame($rawExpected[1], $obj->getRawValue()[1]);
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected[0]->format("Y-m-d H:i:s"), $obj->getStorageValue()[0]->format("Y-m-d H:i:s"));

        $this->assertSame($rawExpected[0]->format("Y-m-d H:i:s"), $obj->getRawValue()[0]->format("Y-m-d H:i:s"));
        $this->assertSame($rawExpected[1], $obj->getRawValue()[1]);
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected[0]->format("Y-m-d H:i:s"), $obj->getStorageValue()[0]->format("Y-m-d H:i:s"));
        $this->assertSame($storageExpected[1]->format("Y-m-d H:i:s"), $obj->getStorageValue()[1]->format("Y-m-d H:i:s"));
    }





    //
    // GETINDEXOFVALUE
    //

    public function test_method_getindexofvalue()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String"
        ]);

        $val = ["val1", "val2"];
        $r = $obj->setValue($val);

        $this->assertSame(null, $obj->collectionGetIndexOfValue("unexistent"));
        $this->assertSame(0, $obj->collectionGetIndexOfValue("val1"));
        $this->assertSame(1, $obj->collectionGetIndexOfValue("val2"));
    }





    //
    // COUNTOCURRENCIESOFVALUE
    //

    public function test_method_countoccurrenciesofvalue()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String"
        ]);

        $val = ["val1", "val2", "val2"];
        $r = $obj->setValue($val);

        $this->assertSame(0, $obj->collectionCountOccurrenciesOfValue("unexistent"));
        $this->assertSame(1, $obj->collectionCountOccurrenciesOfValue("val1"));
        $this->assertSame(2, $obj->collectionCountOccurrenciesOfValue("val2"));


        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String"
        ]);
        $this->assertSame(0, $obj->collectionCountOccurrenciesOfValue("val1"));
    }





    //
    // HASVALUE
    //

    public function test_method_hasvalue()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String"
        ]);

        $val = ["val1", "val2"];
        $r = $obj->setValue($val);

        $this->assertSame(false, $obj->collectionHasValue("unexistent"));
        $this->assertSame(true, $obj->collectionHasValue("val1"));
        $this->assertSame(true, $obj->collectionHasValue("val2"));
    }





    //
    // COUNT
    //

    public function test_method_count()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String"
        ]);

        $this->assertSame(0, $obj->collectionCount());
        $val = ["val1", "val2"];
        $r = $obj->setValue($val);
        $this->assertSame(2, $obj->collectionCount());
    }





    //
    // UNSETVALUE
    //

    public function test_method_unsetvalue()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String"
        ]);

        $val = ["val1", "val2", "val2"];
        $r = $obj->setValue($val);
        $this->assertSame($val, $obj->getValue());

        $obj->collectionUnsetValue("val1");
        $this->assertSame(["val2", "val2"], $obj->getValue());

        $obj->collectionUnsetValue("val2", true);
        $this->assertSame([], $obj->getValue());
    }





    //
    // UNSETINDEX
    //

    public function test_method_unsetindex()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String"
        ]);

        $val = ["val1", "val2", "val3", "val4"];
        $r = $obj->setValue($val);
        $this->assertSame($val, $obj->getValue());

        $obj->collectionUnsetIndex(10);
        $this->assertSame($val, $obj->getValue());

        $obj->collectionUnsetIndex(2);
        $this->assertSame(["val1", "val2", "val4"], $obj->getValue());
    }





    //
    // ACCEPTEDACCOUNT | GETMIN | GETMAX
    //

    public function test_constructor_acceptedcount_fails_invalid()
    {
        $fail = false;
        try {
            $obj = new DataFieldCollection([
                "name"                      => "validName",
                "type"                      => "String",
                "acceptedCount"             => "-"
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid \"acceptedCount\".", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_acceptedcount_null()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String"
        ]);
        $this->assertSame(null, $obj->collectionGetAcceptedCount());
        $this->assertSame(null, $obj->collectionGetMin());
        $this->assertSame(null, $obj->collectionGetMax());


        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "acceptedCount"             => ""
        ]);
        $this->assertSame(null, $obj->collectionGetAcceptedCount());
        $this->assertSame(null, $obj->collectionGetMin());
        $this->assertSame(null, $obj->collectionGetMax());
    }


    public function test_constructor_acceptedcount_sucess()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "acceptedCount"             => "2|3|5|*3|*5|0,20"
        ]);

        $acceptedCount = [
            "exactValues"   => [2, 3, 5],
            "multiples"     => [3, 5],
            "min"           => 0,
            "max"           => 20
        ];

        $this->assertSame($acceptedCount, $obj->collectionGetAcceptedCount());
        $this->assertSame(0, $obj->collectionGetMin());
        $this->assertSame(20, $obj->collectionGetMax());
    }


    public function test_property_acceptedcount_validate()
    {
        $obj = new DataFieldCollection([
            "name"                      => "validName",
            "type"                      => "String",
            "acceptedCount"             => "2|3|5|*3|*5|2,10"
        ]);


        $testValuesAndExpectedReturn = [
            // Falha pois exige ao menos 2 itens para a coleção ser válida.
            [
                ["val1"],
                false, "error.dm.field.collection.constraint.range.violated"
            ],
            // Passa pois exige ao menos 2 itens.
            // 2 também é um dos números especificados de forma exata
            [
                ["val1", "val2"],
                true, "valid"
            ],
            // Passa pois permite um array com exatos 3 itens, ou um múltiplo de 3
            [
                ["val1", "val2", "val3"],
                true, "valid"
            ],
            // Não passa pois não está especificado de forma explicita
            // e 4 não é um múltiplo de 3 nem de 5
            [
                ["val1", "val2", "val3", "val4"],
                false, "error.dm.field.collection.constraint.accepted.count.violated"
            ],
            // Passa pois permite um array com exatos 5 itens, ou um múltiplo de 5
            [
                ["val1", "val2", "val3", "val4", "val5"],
                true, "valid"
            ],
            // Passa pois permite um múltiplo de 3
            [
                ["val1", "val2", "val3", "val4", "val5", "val6"],
                true, "valid"
            ],
            // Não passa pois não está especificado de forma explicita
            // e 7 não é um múltiplo de 3 nem de 5
            [
                ["val1", "val2", "val3", "val4", "val5", "val6", "val7"],
                false, "error.dm.field.collection.constraint.accepted.count.violated"
            ],
            // Não passa pois não está especificado de forma explicita
            // e 8 não é um múltiplo de 3 nem de 5
            [
                ["val1", "val2", "val3", "val4", "val5", "val6", "val7", "val8"],
                false, "error.dm.field.collection.constraint.accepted.count.violated"
            ],
            // Passa pois permite um múltiplo de 3
            [
                ["val1", "val2", "val3", "val4", "val5", "val6", "val7", "val8", "val9"],
                true, "valid"
            ],
            // Passa pois permite um múltiplo de 5
            [
                ["val1", "val2", "val3", "val4", "val5", "val6", "val7", "val8", "val9", "val10"],
                true, "valid"
            ],
            // Falha pois exige no máximo 10 itens para a coleção ser válida.
            [
                ["val1", "val2", "val3", "val4", "val5", "val6", "val7", "val8", "val9", "val10", "val11"],
                false, "error.dm.field.collection.constraint.range.violated"
            ]
        ];


        foreach ($testValuesAndExpectedReturn as $i => $rules) {
            $val        = $rules[0];
            $result     = $rules[1];
            $resultErr  = $rules[2];


            $r = $obj->validateValue($val, true);
            $state = $obj->collectionGetLastValidateState();
            $this->assertSame($resultErr, $state);
            $this->assertSame($result, $r);
        }
    }

}
