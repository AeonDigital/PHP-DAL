<?php
declare (strict_types=1);

use PHPUnit\Framework\TestCase;
use AeonDigital\DataModel\Tests\Concrete\DataField as DataField;

require_once __DIR__ . "/../../phpunit.php";







class t01FieldTest extends TestCase
{





    public function test_constructor_ok()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String"
        ]);
        $this->assertTrue(is_a($obj, DataField::class));
    }





    //
    // NAME
    //

    public function test_constructor_fails_name_empty()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "",
                "type"                      => "String"
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid configuration. The attribute \"name\" is required.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_name_invalid()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "invalid|",
                "type"                      => "String"
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid given field name [\"invalid|\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok_name()
    {
        $testValue = "validName";

        $obj = new DataField([
            "name"                      => $testValue,
            "type"                      => "String"
        ]);
        $this->assertSame($testValue, $obj->getName());
    }





    //
    // DESCRIPTION
    //

    public function test_constructor_ok_description()
    {
        $testValue = "Descrição deste campo.";

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "description"               => $testValue
        ]);
        $this->assertSame($testValue, $obj->getDescription());
    }





    //
    // TYPE
    //

    public function test_constructor_fails_type_not_set()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName"
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid configuration. The attribute \"type\" is required.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_type_not_a_class()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "invalid_class_name"
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("The given \"type\" class does not exists.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_type_not_implements_interface()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => StdClass::class
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("The given \"type\" class does not implements the interface \"AeonDigital\\Interfaces\\SimpleType\\iSimpleType\".", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok_type()
    {
        $expectedValue = "AeonDigital\\SimpleType\\stString";

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String"
        ]);
        $this->assertSame($expectedValue, $obj->getType());
    }





    //
    // INPUTFORMAT
    //

    public function test_constructor_fails_inputformat_not_a_class()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "String",
                "inputFormat"               => "invalid_class_name"
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("The given \"inputFormat\" class does not exists [\"AeonDigital\DataFormat\Patterns\invalid_class_name\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_inputformat_not_implements_interface()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "String",
                "inputFormat"               => DateTime::class
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("The given \"inputFormat\" class does not implements the interface \"AeonDigital\Interfaces\DataFormat\iFormat\".", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok_inputformat()
    {
        $expectedValue = "AeonDigital\\DataFormat\\Patterns\\World\\Email";

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => "World.Email"
        ]);
        $this->assertSame($expectedValue, $obj->getInputFormat());
    }


    public function test_constructor_fails_inputformat_custom_lost_keys()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "String",
                "inputFormat"               => [
                    "name"  => "TRANSFORM"
                ]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Lost required key in the given input format rule.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_inputformat_custom_invalid_keys()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "String",
                "inputFormat"               => [
                    "name"          => "",
                    "length"        => null,
                    "check"         => null,
                    "removeFormat"  => null,
                    "format"        => null,
                    "storageFormat" => null
                ]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid given \"name\" of input format. Expected a not empty string.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");



        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "String",
                "inputFormat"               => [
                    "name"          => "valid",
                    "length"        => "invalid",
                    "check"         => null,
                    "removeFormat"  => null,
                    "format"        => null,
                    "storageFormat" => null
                ]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid given \"length\" of input format. Expected integer or null.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");



        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "String",
                "inputFormat"               => [
                    "name"          => "valid",
                    "length"        => 100,
                    "check"         => null,
                    "removeFormat"  => null,
                    "format"        => null,
                    "storageFormat" => null
                ]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid given \"check\" of input format. Expected callable.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");



        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "String",
                "inputFormat"               => [
                    "name"          => "valid",
                    "length"        => 100,
                    "check"         => function() { return true; },
                    "removeFormat"  => null,
                    "format"        => null,
                    "storageFormat" => null
                ]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid given \"removeFormat\" of input format. Expected callable.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");



        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "String",
                "inputFormat"               => [
                    "name"          => "valid",
                    "length"        => 100,
                    "check"         => function() { return true; },
                    "removeFormat"  => function($v) { return $v; },
                    "format"        => null,
                    "storageFormat" => null,
                ]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid given \"format\" of input format. Expected callable.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");



        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "String",
                "inputFormat"               => [
                    "name"          => "valid",
                    "length"        => 100,
                    "check"         => function() { return true; },
                    "removeFormat"  => function($v) { return $v; },
                    "format"        => function($v) { return $v; },
                    "storageFormat" => null,
                ]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid given \"storageFormat\" of input format. Expected callable.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok_inputformat_custom()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => [
                "name"          => "transformname",
                "length"        => 16,
                "check"         => function($v) { return (strpos($v, "z") === false); },
                "removeFormat"  => function($v) { return str_replace("!", "i", $v); },
                "format"        => function($v) { return strtoupper($v); },
                "storageFormat" => function($v) { return str_replace(["i", "I"], "!", $v); }
            ]
        ]);

        $this->assertSame("TRANSFORMNAME", $obj->getInputFormat());
        $this->assertSame(16, $obj->getLength());

        $this->assertFalse($obj->validateValue("invalid z"));

        $val = "valid string";
        $obj->setValue($val);
        $this->assertSame("VAL!D STR!NG", $obj->getValue());
        $this->assertSame("val!d str!ng", $obj->getStorageValue());
    }





    //
    // LENGTH
    //

    public function test_constructor_ok_length()
    {
        // Definindo "length" de forma direta.
        // Apenas funciona por que nenhum formato foi informado.
        $testValue = 16;

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => $testValue
        ]);
        $this->assertSame($testValue, $obj->getLength());



        // Definindo "length" de forma indireta.
        // O valor é herdado do formato indicado.
        $testValue = 64;

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => "World.Email"
        ]);
        $this->assertSame($testValue, $obj->getLength());



        // Definindo "length" de forma indireta.
        // O valor é "null" pois esta propriedade não se aplica
        // para tipos numéricos
        $testValue = null;

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
        ]);
        $this->assertSame($testValue, $obj->getLength());
    }





    //
    // MIN
    //

    public function test_constructor_fails_min_invalid()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "Int",
                "min"                       => "-"
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid min value.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok_min()
    {
        // Valor do parametro herdado de seu tipo simples.
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int"
        ]);
        $this->assertSame(-2147483648, $obj->getMin());


        // Valor do parametro definido de forma explicita.
        $testValue = 0;

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "min"                       => $testValue
        ]);
        $this->assertSame($testValue, $obj->getMin());


        // Valor explicito não será levado em conta pois
        // o tipo simples não é numérico nem DateTime
        $testValue = null;

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "min"                       => 10
        ]);
        $this->assertSame($testValue, $obj->getMin());


        // Valor explicito para um DateTime
        $testValue = new DateTime("1900-01-01 00:00:00");

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "DateTime",
            "min"                       => $testValue
        ]);
        $this->assertSame($testValue, $obj->getMin());


        // Valor explicito para um RealNumber
        $testValue = "-400000";

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Real",
            "min"                       => $testValue
        ]);
        $this->assertSame($testValue, $obj->getMin()->value());
    }





    //
    // MAX
    //

    public function test_constructor_fails_max_invalid()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "Int",
                "max"                       => "-"
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid max value.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok_max()
    {
        // Valor do parametro herdado de seu tipo simples.
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int"
        ]);
        $this->assertSame(2147483647, $obj->getMax());


        // Valor do parametro definido de forma explicita.
        $testValue = 0;

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "max"                       => $testValue
        ]);
        $this->assertSame($testValue, $obj->getMax());


        // Valor explicito não será levado em conta pois
        // o tipo simples não é numérico nem DateTime
        $testValue = null;

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "max"                       => 10
        ]);
        $this->assertSame($testValue, $obj->getMax());


        // Valor explicito para um DateTime
        $testValue = new DateTime("2200-12-31 23:59:59");

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "DateTime",
            "max"                       => $testValue
        ]);
        $this->assertSame($testValue, $obj->getMax());


        // Valor explicito para um RealNumber
        $testValue = "400000";

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Real",
            "max"                       => $testValue
        ]);
        $this->assertSame($testValue, $obj->getMax()->value());
    }





    //
    // ISREFERENCE | ISCOLLECTION
    //

    public function test_constructor_isreference()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int"
        ]);
        $this->assertFalse($obj->isReference());
    }


    public function test_constructor_iscollection()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int"
        ]);
        $this->assertFalse($obj->isCollection());
    }





    //
    // ALLOWNULL | ALLOWEMPTY | CONVERTEMPTYTONULL
    //

    public function test_constructor_ok_allownull()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int"
        ]);
        $this->assertTrue($obj->isAllowNull());


        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "allowNull"                 => true
        ]);
        $this->assertTrue($obj->isAllowNull());


        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "allowNull"                 => false
        ]);
        $this->assertFalse($obj->isAllowNull());
    }


    public function test_constructor_ok_allowempty()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int"
        ]);
        $this->assertTrue($obj->isAllowEmpty());


        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "allowEmpty"                => true
        ]);
        $this->assertTrue($obj->isAllowEmpty());


        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "allowEmpty"                => false
        ]);
        $this->assertFalse($obj->isAllowEmpty());
    }


    public function test_constructor_ok_convertemptytonull()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int"
        ]);
        $this->assertFalse($obj->isConvertEmptyToNull());


        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "convertEmptyToNull"        => true
        ]);
        $this->assertTrue($obj->isConvertEmptyToNull());


        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "convertEmptyToNull"        => false
        ]);
        $this->assertFalse($obj->isConvertEmptyToNull());
    }





    //
    // ISREADONLY
    //

    public function test_constructor_ok_isreadonly()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int"
        ]);
        $this->assertFalse($obj->isReadOnly());


        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "readOnly"                  => true
        ]);
        $this->assertTrue($obj->isReadOnly());
    }





    //
    // VALIDATEVALUE
    //

    public function test_method_validatevalue_invalidvalue_undefined()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val            = undefined;
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.value.not.allow.undefined", $validateState);
    }


    public function test_method_validatevalue_invalidvalue_allownull()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10,
            "allowNull"                 => false
        ]);

        $val            = null;
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.value.not.allow.null", $validateState);
    }


    public function test_method_validatevalue_invalidvalue_allowempty()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10,
            "allowEmpty"                => false
        ]);

        $val            = "";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.value.not.allow.empty", $validateState);
    }


    public function test_method_validatevalue_invalidvalue_array()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val            = [];
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.value.not.allow.array", $validateState);
    }


    public function test_method_validatevalue_invalidvalue_object()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val            = new StdClass();
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.value.invalid.type", $validateState);
    }


    public function test_method_validatevalue_invalidvalue_inputformat()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => "Brasil.CPF"
        ]);

        $val            = "invalid";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState);
    }


    public function test_method_validatevalue_invalidvalue_notparsetotype()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "DateTime",
        ]);

        $val            = "invalid";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.st.unexpected.type", $validateState);
    }





    public function test_method_validatevalue_constraint_enumerator()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "enumerator"                => [["1", "Janeiro"], ["2", "Fevereiro"], ["3", "Março"]]
        ]);

        $val            = 0;
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.value.constraint.enumerator.violated", $validateState);


        $val            = 1;
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);


        $val            = "2";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);
    }


    public function test_method_validatevalue_constraint_length()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val            = "invalid length string";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.value.constraint.length.violated", $validateState);


        $val            = "v length";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);
    }


    public function test_method_validatevalue_constraint_int_min_max()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "min"                       => 100,
            "max"                       => 200
        ]);

        // Verifica o limite inferior
        $val            = "99";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $validateState);


        $val            = "100";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);



        // Verifica o limite superior
        $val            = 200;
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);


        $val            = 201;
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $validateState);
    }


    public function test_method_validatevalue_constraint_real_min_max()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Real",
            "min"                       => "0.00001",
            "max"                       => "0.99999"
        ]);

        // Verifica o limite inferior
        $val            = "0.000009";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $validateState);


        $val            = "0.00001";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);



        // Verifica o limite superior
        $val            = 0.99999;
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);


        $val            = 0.999999;
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $validateState);
    }


    public function test_method_validatevalue_constraint_datetime_min_max()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "DateTime",
            "min"                       => "2000-01-01 00:00:00",
            "max"                       => "2010-12-31 23:59:59"
        ]);

        // Verifica o limite inferior
        $val            = "1999-12-31 23:59:59";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $validateState);


        $val            = "2000-01-01 00:00:00";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);



        // Verifica o limite superior
        $val            = new \DateTime("2010-12-31 23:59:59");
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);


        $val            = new \DateTime("2011-01-01 00:00:00");
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $validateState);
    }





    public function test_method_validatevalue_validvalue()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val            = "validvalue";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);



        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10,
            "allowNull"                 => true,
            "allowEmpty"                => false,
            "convertEmptyToNull"        => true
        ]);

        $val            = "";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);


        $val            = "test with a big length text";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.value.constraint.length.violated", $validateState);
    }


    public function test_method_validatevalue_validvalue_inputformat()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => "Brasil.CPF"
        ]);

        $val            = "invalid";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState);


        $val            = "189.208.640-98";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);


        $val            = "189.208.640-10";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState);





        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "DateTime",
            "inputFormat"               => "Brasil.Dates.Date"
        ]);

        $val            = "invalid";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState);


        $val            = "2018-01-20";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState);


        $val            = "20-01-2018";
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);


        $val            = new DateTime("2018-01-20");
        $r              = $obj->validateValue($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);
    }








    //
    // DEFAULT
    //

    public function test_constructor_fails_default_invalid()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "String",
                "inputFormat"               => "World.Email",
                "default"                   => "invalid email set"
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("The given \"default\" value is invalid.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok_default()
    {
        $validValue = "valid default value";

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "default"                   => $validValue
        ]);
        $this->assertSame($validValue, $obj->getDefault());


        $validValue = "2000-01-01";

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "DateTime",
            "default"                   => $validValue
        ]);
        $this->assertSame($validValue, $obj->getDefault()->format("Y-m-d"));


        $validValue = "NOW()";

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "DateTime",
            "default"                   => $validValue
        ]);
        $this->assertTrue(is_a($obj->getDefault(), "\DateTime"));
        $this->assertSame("NOW()", $obj->getDefault(true));
    }





    //
    // ENUMERATOR
    //

    public function test_constructor_fails_enumerator_empty_array()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "Int",
                "enumerator"                => []
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid enumerator value. The given array is empty.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_enumerator_assoc_array()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "Int",
                "enumerator"                => ["1" => "Janeiro", "2" => "Fevereiro", "3"]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid enumerator value. Can not be an assoc array.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_enumerator_invalid_multidimensional()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "Int",
                "enumerator"                => [["1", "Janeiro"], ["2", "Fevereiro"], ["3"]]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid enumerator value. Multidimensional arrays must have 2 values defined.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_enumerator_invalid_value()
    {
        $fail = false;
        try {
            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "Int",
                "enumerator"                => ["1", "invalid", "3"]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid enumerator value.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_enumerator_path_invalid()
    {
        $fail = false;
        try {
            $path = realpath(__DIR__ . "/../src/enum") . DIRECTORY_SEPARATOR . "invalidpath.php";

            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "String",
                "enumerator"                => $path
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("The target enumerator file description does not exist.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_enumerator_path_invalid_content()
    {
        $fail = false;
        try {
            $path = realpath(__DIR__ . "/enum") . DIRECTORY_SEPARATOR . "invalidEnum.php";

            $obj = new DataField([
                "name"                      => "validName",
                "type"                      => "String",
                "enumerator"                => $path
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("The target enumerator file does not have a valid array.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_ok_enumerator()
    {
        // É esperado que o array de enumeradores definido
        // tenha os valores "key" convertidos para seu tipo simples
        // que no caso são númerais inteiros.
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "enumerator"                => [["1", "Janeiro"], ["2", "Fevereiro"], ["3", "Março"]]
        ]);

        $expected = [
            [1, "Janeiro"],
            [2, "Fevereiro"],
            [3, "Março"]
        ];
        $this->assertSame($expected, $obj->getEnumerator());

        $expected = [1, 2, 3];
        $this->assertSame($expected, $obj->getEnumerator(true));


        // Deve passar na validação pois os numerais indicados
        // estão especificados entre os valores do enumerador.
        $val = 1;
        $r = $obj->validateValue($val);
        $this->assertTrue($r);


        $val = "3";
        $r = $obj->validateValue($val);
        $this->assertTrue($r);


        // Deve falhar nos testes abaixos pois os numerais
        // apresentados não estão entre os valores definidos no
        // enumerador do campo.
        $val = 0;
        $r = $obj->validateValue($val);
        $this->assertFalse($r);
        $this->assertSame("error.dm.field.value.constraint.enumerator.violated", $obj->getLastValidateState());



        $val = 4;
        $r = $obj->validateValue($val);
        $this->assertFalse($r);
        $this->assertSame("error.dm.field.value.constraint.enumerator.violated", $obj->getLastValidateState());
    }


    public function test_constructor_ok_enumerator_with_path()
    {
        // É esperado que o array de enumeradores definido
        // tenha os valores "key" convertidos para seu tipo simples
        // que no caso são númerais inteiros.
        $path = realpath(__DIR__ . "/enum") . DIRECTORY_SEPARATOR . "validEnum.php";

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "enumerator"                => $path
        ]);

        $expected = [
            "one", "two", "tree"
        ];
        $this->assertSame($expected, $obj->getEnumerator());
    }





    //
    // VALUE
    //

    public function test_constructor_ok_with_value_invalid()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => "World.Email",
            "value"                     => "invalid email set"
        ]);

        $this->assertSame("invalid email set", $obj->getRawValue());
        $this->assertSame("invalid email set", $obj->getValue());
        $this->assertSame(null, $obj->getStorageValue());

        $this->assertFalse($obj->isValid());
    }


    public function test_constructor_ok_value()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => "World.Email"
        ]);

        $this->assertTrue($obj->isValid());
        $this->assertSame("valid", $obj->getLastValidateState());

        $this->assertSame(undefined, $obj->getRawValue());
        $this->assertSame(undefined, $obj->getValue());
        $this->assertSame(null, $obj->getStorageValue());



        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => "World.Email",
            "value"                     => "VALID@EMAIL.com"
        ]);

        $this->assertTrue($obj->isValid());
        $this->assertSame("valid", $obj->getLastValidateState());

        $this->assertSame("VALID@EMAIL.com", $obj->getRawValue());
        $this->assertSame("valid@email.com", $obj->getValue());
        $this->assertSame("valid@email.com", $obj->getStorageValue());
    }





    //
    // SETVALUE | GETVALUE
    //

    public function test_method_setvalue_invalidvalue_undefined()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val            = undefined;
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("error.dm.field.value.not.allow.undefined", $validateState);

        $rawExpected        = undefined;
        $formatedExpected   = undefined;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_invalidvalue_allownull()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10,
            "allowNull"                 => false
        ]);

        $val            = null;
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.not.allow.undefined", $realState);
        $this->assertSame("error.dm.field.value.not.allow.null", $validateState);

        $rawExpected        = undefined;
        $formatedExpected   = undefined;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_invalidvalue_allowempty()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10,
            "allowEmpty"                => false
        ]);

        $val            = "";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("error.dm.field.value.not.allow.empty", $validateState);

        $rawExpected        = undefined;
        $formatedExpected   = undefined;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_invalidvalue_array()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val            = [];
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("error.dm.field.value.not.allow.array", $validateState);

        $rawExpected        = undefined;
        $formatedExpected   = undefined;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_invalidvalue_object()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val            = new StdClass();
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("error.dm.field.value.invalid.type", $validateState);

        $rawExpected        = undefined;
        $formatedExpected   = undefined;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_invalidvalue_inputformat()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => "Brasil.CPF"
        ]);

        $val            = "invalid";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.invalid.input.format", $realState);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = "invalid";
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_invalidvalue_notparsetotype()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "DateTime",
        ]);

        $val            = "invalid";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("error.st.unexpected.type", $validateState);

        $rawExpected        = undefined;
        $formatedExpected   = undefined;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }





    public function test_method_setvalue_constraint_enumerator()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "enumerator"                => [["1", "Janeiro"], ["2", "Fevereiro"], ["3", "Março"]]
        ]);

        $val            = 0;
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.constraint.enumerator.violated", $realState);
        $this->assertSame("error.dm.field.value.constraint.enumerator.violated", $validateState);

        $rawExpected        = 0;
        $formatedExpected   = 0;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());



        $val            = "1";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = "1";
        $formatedExpected   = 1;
        $storageExpected    = 1;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_constraint_length()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);

        $val            = "invalid length string";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.constraint.length.violated", $realState);
        $this->assertSame("error.dm.field.value.constraint.length.violated", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());



        $val            = "valid";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = $val;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_constraint_int_min_max()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Int",
            "min"                       => 100,
            "max"                       => 200
        ]);


        // Verifica o limite inferior
        $val            = 99;
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $realState);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());



        $val            = 100;
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = $val;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());



        // Verifica o limite superior
        $val            = "200";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = 200;
        $storageExpected    = 200;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());



        $val            = "201";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $realState);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = 201;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_constraint_real_min_max()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "Real",
            "min"                       => "0.00001",
            "max"                       => "0.99999"
        ]);


        // Verifica o limite inferior
        $val            = "0.000009";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $realState);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, (string)$obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());



        $val            = "0.00001";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = $val;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, (string)$obj->getValue());
        $this->assertSame($storageExpected, (string)$obj->getStorageValue());



        // Verifica o limite superior
        $val            = "0.99999";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = $val;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, (string)$obj->getValue());
        $this->assertSame($storageExpected, (string)$obj->getStorageValue());



        $val            = "0.999999";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $realState);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, (string)$obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_constraint_datetime_min_max()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "DateTime",
            "min"                       => "2000-01-01 00:00:00",
            "max"                       => "2010-12-31 23:59:59"
        ]);


        // Verifica o limite inferior
        $val            = "1999-12-31 23:59:59";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $realState);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue()->format("Y-m-d H:i:s"));
        $this->assertSame($storageExpected, $obj->getStorageValue());



        $val            = "2000-01-01 00:00:00";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = $val;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue()->format("Y-m-d H:i:s"));
        $this->assertSame($storageExpected, $obj->getStorageValue()->format("Y-m-d H:i:s"));



        // Verifica o limite superior
        $val            = "2010-12-31 23:59:59";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = $val;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue()->format("Y-m-d H:i:s"));
        $this->assertSame($storageExpected, $obj->getStorageValue()->format("Y-m-d H:i:s"));



        $val            = "2011-01-01 00:00:00";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $realState);
        $this->assertSame("error.dm.field.value.constraint.range.violated", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue()->format("Y-m-d H:i:s"));
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_constraint_readonly()
    {

        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "readOnly"                  => true
        ]);


        $val            = "valid set";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = $val;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());



        $val            = "invalid set";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("error.dm.field.value.constraint.read.only", $validateState);

        $rawExpected        = "valid set";
        $formatedExpected   = "valid set";
        $storageExpected    = "valid set";

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());





        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "readOnly"                  => true,
            "value"                     => "defined on constructor"
        ]);

        // Deve falhar na validação pois o valor já foi definido
        // na construção do objeto.
        $val            = "invalid set";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("error.dm.field.value.constraint.read.only", $validateState);

        $rawExpected        = "defined on constructor";
        $formatedExpected   = "defined on constructor";
        $storageExpected    = "defined on constructor";

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }




    public function test_method_setvalue_validvalue()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10
        ]);


        $val            = "validvalue";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = $val;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());




        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10,
            "allowNull"                 => true,
            "allowEmpty"                => false,
            "convertEmptyToNull"        => true
        ]);


        $val            = "";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = null;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());




        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "length"                    => 10,
            "allowNull"                 => true,
            "allowEmpty"                => false,
            "convertEmptyToNull"        => true
        ]);


        $val            = "invalid text length";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.constraint.length.violated", $realState);
        $this->assertSame("error.dm.field.value.constraint.length.violated", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = "invalid text length";
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }


    public function test_method_setvalue_validvalue_inputformat()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => "Brasil.CPF"
        ]);


        $val            = "invalid";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.invalid.input.format", $realState);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = "invalid";
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());



        $val            = "189208640-98";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = "189.208.640-98";
        $storageExpected    = "18920864098";

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());



        $val            = "189.208.640-10";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertFalse($v);
        $this->assertSame("error.dm.field.value.invalid.input.format", $realState);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = "189.208.640-10";
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());





        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "DateTime",
            "inputFormat"               => "Brasil.Dates.Date"
        ]);


        $val            = "invalid";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState);

        $rawExpected        = undefined;
        $formatedExpected   = undefined;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());



        $val            = "2018-01-20";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertFalse($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState);

        $rawExpected        = undefined;
        $formatedExpected   = undefined;
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());



        $val            = "20-01-2018";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = "20-01-2018";
        $formatedExpected   = "20-01-2018";
        $storageExpected    = new DateTime("20-01-2018");

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected->format("Y-m-d"), $obj->getStorageValue()->format("Y-m-d"));



        $val            = new DateTime("2018-01-20");
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = new DateTime("2018-01-20");
        $formatedExpected   = "20-01-2018";
        $storageExpected    = new DateTime("20-01-2018");

        $this->assertSame($rawExpected->format("Y-m-d"), $obj->getRawValue()->format("Y-m-d"));
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected->format("Y-m-d"), $obj->getStorageValue()->format("Y-m-d"));
    }


    public function test_method_setvalue_validvalue_storageformat()
    {
        $obj = new DataField([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => "World.Email"
        ]);

        $val            = "VALIDEMAIL@DOMAIN.COM";
        $r              = $obj->setValue($val);
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($r);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = "validemail@domain.com";
        $storageExpected    = "validemail@domain.com";

        $this->assertSame($rawExpected, $obj->getRawValue());
        $this->assertSame($formatedExpected, $obj->getValue());
        $this->assertSame($storageExpected, $obj->getStorageValue());
    }
}
