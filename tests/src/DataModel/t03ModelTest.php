<?php
declare (strict_types=1);

use PHPUnit\Framework\TestCase;
use AeonDigital\DataModel\Tests\Concrete\DataField as DataField;
use AeonDigital\DataModel\Tests\Concrete\DataFieldCollection as DataFieldCollection;
use AeonDigital\DataModel\Tests\Concrete\DataModel as DataModel;

require_once __DIR__ . "/../../phpunit.php";





class t03ModelTest extends TestCase
{




    private function provider_model_user()
    {
        return new DataModel([
            "name"                      => "validModelName",
            "fields"                    => [
                new DataField([
                    "name"                      => "name",
                    "type"                      => "String",
                    "length"                    => 16,
                    "allowNull"                 => false,
                    "allowEmpty"                => false
                ]),
                new DataField([
                    "name"                      => "email",
                    "type"                      => "String",
                    "inputFormat"               => "World.Email"
                ]),
                new DataField([
                    "name"                      => "cpf",
                    "type"                      => "String",
                    "inputFormat"               => "Brasil.CPF"
                ])
            ]
        ]);
    }





    //
    // CONSTRUCTOR
    //

    public function test_constructor_ok()
    {
        $obj = new DataModel([
            "name"                      => "validModelName",
            "fields"                    => [
                new DataField([
                    "name"                      => "validFieldName",
                    "type"                      => "String"
                ])
            ]
        ]);
        $this->assertTrue(is_a($obj, DataModel::class));
    }


    public function test_constructor_fails_fields_not_an_array()
    {
        $fail = false;
        try {
            $obj = new DataModel([
                "name"                      => "validModelName",
                "fields"                    => "not array"
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid given fields. Must be an array of \"iField\" objects.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_fields_empty_array()
    {
        $fail = false;
        try {
            $obj = new DataModel([
                "name"                      => "validModelName",
                "fields"                    => []
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("At least one field must be defined.", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_name_empty()
    {
        $fail = false;
        try {
            $obj = new DataModel([
                "name"                      => "",
                "fields"                    => [
                    new DataField([
                        "name"                      => "validFieldName",
                        "type"                      => "String"
                    ])
                ]
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
            $obj = new DataModel([
                "name"                      => "invalid|",
                "fields"                    => [
                    new DataField([
                        "name"                      => "validFieldName",
                        "type"                      => "String"
                    ])
                ]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Invalid given field name [\"invalid|\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_constructor_fails_field_duplicated()
    {
        $fail = false;
        try {
            $obj = new DataModel([
                "name"                      => "validModelName",
                "fields"                    => [
                    new DataField([
                        "name"                      => "validFieldName",
                        "type"                      => "String"
                    ]),
                    new DataField([
                        "name"                      => "validFieldName",
                        "type"                      => "String"
                    ])
                ]
            ]);
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Field name duplicated [\"validFieldName\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }





    //
    // NAME
    //

    public function test_property_name_sucess()
    {
        $testValue = "validName";

        $obj = new DataModel([
            "name"                      => $testValue,
            "fields"                    => [
                new DataField([
                    "name"                      => "validFieldName",
                    "type"                      => "String"
                ])
            ]
        ]);
        $this->assertSame($testValue, $obj->getName());
    }





    //
    // DESCRIPTION
    //

    public function test_property_description_sucess()
    {
        $testValue = "Descrição deste campo.";

        $obj = new DataModel([
            "name"                      => "validModelName",
            "description"               => $testValue,
            "fields"                    => [
                new DataField([
                    "name"                      => "validFieldName",
                    "type"                      => "String"
                ])
            ]
        ]);
        $this->assertSame($testValue, $obj->getDescription());
    }





    //
    // HASFIELD | COUNTFIELDS | GETFIELDNAMES | GETINITIALDATAMODEL
    //

    public function test_method_hasfield()
    {
        $obj = new DataModel([
            "name"                      => "validModelName",
            "fields"                    => [
                new DataField([
                    "name"                      => "validFieldName_01",
                    "type"                      => "String"
                ]),
                new DataField([
                    "name"                      => "validFieldName_02",
                    "type"                      => "String"
                ])
            ]
        ]);

        $this->assertTrue($obj->hasField("validFieldName_01"));
        $this->assertTrue($obj->hasField("validFieldName_02"));
        $this->assertFalse($obj->hasField("validFieldName_03"));
    }


    public function test_method_countfields()
    {
        $obj = new DataModel([
            "name"                      => "validModelName",
            "fields"                    => [
                new DataField([
                    "name"                      => "validFieldName_01",
                    "type"                      => "String"
                ]),
                new DataField([
                    "name"                      => "validFieldName_02",
                    "type"                      => "String"
                ])
            ]
        ]);

        $this->assertSame(2, $obj->countFields());
    }


    public function test_method_getfieldnames()
    {
        $obj = new DataModel([
            "name"                      => "validModelName",
            "fields"                    => [
                new DataField([
                    "name"                      => "validFieldName_01",
                    "type"                      => "String"
                ]),
                new DataField([
                    "name"                      => "validFieldName_02",
                    "type"                      => "String"
                ])
            ]
        ]);

        $this->assertSame(["validFieldName_01", "validFieldName_02"], $obj->getFieldNames());
    }


    public function test_method_getinitialdatamodel()
    {
        $obj = new DataModel([
            "name"                      => "validModelName",
            "fields"                    => [
                new DataField([
                    "name"                      => "name",
                    "type"                      => "String",
                    "length"                    => 100,
                    "allowNull"                 => false,
                    "allowEmpty"                => false
                ]),
                new DataField([
                    "name"                      => "email",
                    "type"                      => "String",
                    "inputFormat"               => "World.Email",
                    "default"                   => "email@valid.com"
                ]),
                new DataField([
                    "name"                      => "cpf",
                    "type"                      => "String",
                    "inputFormat"               => "Brasil.CPF"
                ])
            ]
        ]);



        $val = [
            "name"  => undefined,
            "email" => "email@valid.com",
            "cpf"   => undefined
        ];
        $r = $obj->getInitialDataModel();
        $this->assertTrue(is_array($r));
        $this->assertSame(3, count($r));
        $this->assertTrue(key_exists("name", $r));
        $this->assertTrue(key_exists("email", $r));
        $this->assertTrue(key_exists("cpf", $r));
        $this->assertSame($val["name"], $r["name"]);
        $this->assertSame($val["email"], $r["email"]);
        $this->assertSame($val["cpf"], $r["cpf"]);
    }





    //
    // VALIDATEVALUES
    //

    public function test_method_validatevalues_invalidvalue_notiterable()
    {
        $obj = $this->provider_model_user();

        $val            = "Not iterable value";
        $r              = $obj->validateValues($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.model.expected.iterable.object", $validateState);
    }


    public function test_method_validatevalues_invalidvalue_unespectedfield()
    {
        $obj = $this->provider_model_user();

        $val            = [
            "name"          => "valid value 01",
            "unespected"    => "valid@email.com",
            "cpf"           => "189.208.640-98"
        ];
        $r              = $obj->validateValues($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertTrue(is_array($validateState));
        $this->assertSame(3, count($validateState));
        $this->assertSame("valid", $validateState["name"]);
        $this->assertSame("error.dm.model.unespected.field.name", $validateState["unespected"]);
        $this->assertSame("valid", $validateState["cpf"]);
    }


    public function test_method_validatevalues_invalidvalue_emptyiterable()
    {
        $obj = $this->provider_model_user();

        $val            = [];
        $r              = $obj->validateValues($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertSame("error.dm.model.value.not.allow.empty.object", $validateState);
    }


    public function test_method_validatevalues_invalidvalue_invalidfields()
    {
        $obj = $this->provider_model_user();

        $val            = [
            "name"  => "",
            "email" => "invalid email",
            "cpf"   => "189.208.640-00"
        ];
        $r              = $obj->validateValues($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertTrue(is_array($validateState));
        $this->assertSame(3, count($validateState));
        $this->assertSame("error.dm.field.value.not.allow.empty", $validateState["name"]);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState["email"]);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState["cpf"]);
    }


    public function test_method_validatevalues_invalidvalue_checkall()
    {
        $obj = $this->provider_model_user();

        $val = [
            "email" => "invalid email",
            "cpf"   => "189.208.640-00"
        ];
        $r              = $obj->validateValues($val, true);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($validateCanSet);
        $this->assertTrue(is_array($validateState));
        $this->assertSame(3, count($validateState));
        $this->assertSame("error.dm.field.value.not.allow.undefined", $validateState["name"]);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState["email"]);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState["cpf"]);
    }


    public function test_method_validatevalues_invalidvalues_canset()
    {
        $obj = $this->provider_model_user();

        $val = [
            "name"  => "big and invalid name",
            "email" => "invalid email",
            "cpf"   => "189.208.640-90"
        ];
        $r              = $obj->validateValues($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertTrue(is_array($validateState));
        $this->assertSame(3, count($validateState));
        $this->assertSame("error.dm.field.value.constraint.length.violated", $validateState["name"]);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState["email"]);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState["cpf"]);
    }


    public function test_method_validatevalues()
    {
        $obj = $this->provider_model_user();

        $val = [
            "name"  => "valid value 01",
            "email" => "valid@email.com",
            "cpf"   => "189.208.640-98"
        ];
        $r              = $obj->validateValues($val, true);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $validateState);
    }






    //
    // SETFIELDVALUE | GETFIELDVALUE
    //

    public function test_method_setget_fieldvalue_fail_field_non_exist()
    {
        $obj = $this->provider_model_user();

        $fail = false;
        try {
            $r1 = $obj->setFieldValue("fieldNameNonExist", "field value 01");
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Non-existent field name [\"fieldNameNonExist\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_method_setget_values_fails_magicsetget()
    {
        $obj = $this->provider_model_user();

        $fail = false;
        try {
            $e = $obj->undefinedPropertie;
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Non-existent field name [\"undefinedPropertie\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");


        $fail = false;
        try {
            $obj->undefinedPropertie = "e";
        } catch (\Exception $ex) {
            $fail = true;
            $this->assertSame("Non-existent field name [\"undefinedPropertie\"].", $ex->getMessage());
        }
        $this->assertTrue($fail, "Test must fail");
    }


    public function test_method_setget_fieldvalue()
    {
        $obj = $this->provider_model_user();


        // Avalia as condições iniciais
        // Esperado que apenas "name" seja inválido pois é exigido que
        // ele não seja nulo.
        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($initi);
        $this->assertFalse($v);
        $this->assertTrue(is_array($realState));
        $this->assertSame(3, count($realState));
        $this->assertSame("error.dm.field.value.not.allow.undefined", $realState["name"]);
        $this->assertSame("valid", $realState["email"]);
        $this->assertSame("valid", $realState["cpf"]);
        $this->assertSame(null, $validateState);




        $val            = "invalid email";
        $r              = $obj->setFieldValue("email", $val);
        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($initi);
        $this->assertFalse($v);
        $this->assertTrue($validateCanSet);
        $this->assertTrue(is_array($realState));
        $this->assertSame(3, count($realState));
        $this->assertSame("error.dm.field.value.not.allow.undefined", $realState["name"]);
        $this->assertSame("error.dm.field.value.invalid.input.format", $realState["email"]);
        $this->assertSame("valid", $realState["cpf"]);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = "invalid email";
        $storageExpected    = null;

        $this->assertSame($rawExpected, $obj->getFieldRawValue("email"));
        $this->assertSame($formatedExpected, $obj->getFieldValue("email"));
        $this->assertSame($storageExpected, $obj->getFieldStorageValue("email"));



        $val            = "VALIDEMAIL@DOMAIN.COM";
        $r              = $obj->setFieldValue("email", $val);
        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertFalse($initi);
        $this->assertFalse($v);
        $this->assertTrue($validateCanSet);
        $this->assertTrue(is_array($realState));
        $this->assertSame(3, count($realState));
        $this->assertSame("error.dm.field.value.not.allow.undefined", $realState["name"]);
        $this->assertSame("valid", $realState["email"]);
        $this->assertSame("valid", $realState["cpf"]);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = "validemail@domain.com";
        $storageExpected    = "validemail@domain.com";

        $this->assertSame($rawExpected, $obj->getFieldRawValue("email"));
        $this->assertSame($formatedExpected, $obj->getFieldValue("email"));
        $this->assertSame($storageExpected, $obj->getFieldStorageValue("email"));



        $val            = "519.212.420-43";
        $r              = $obj->setFieldValue("cpf", $val);
        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertFalse($initi);
        $this->assertFalse($v);
        $this->assertTrue($validateCanSet);
        $this->assertTrue(is_array($realState));
        $this->assertSame(3, count($realState));
        $this->assertSame("error.dm.field.value.not.allow.undefined", $realState["name"]);
        $this->assertSame("valid", $realState["email"]);
        $this->assertSame("valid", $realState["cpf"]);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = "519.212.420-43";
        $storageExpected    = "51921242043";

        $this->assertSame($rawExpected, $obj->getFieldRawValue("cpf"));
        $this->assertSame($formatedExpected, $obj->getFieldValue("cpf"));
        $this->assertSame($storageExpected, $obj->getFieldStorageValue("cpf"));




        $val            = "valid name 01";
        $r              = $obj->setFieldValue("name", $val);
        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertFalse($initi);
        $this->assertTrue($v);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected        = $val;
        $formatedExpected   = $val;
        $storageExpected    = $val;

        $this->assertSame($rawExpected, $obj->getFieldRawValue("name"));
        $this->assertSame($formatedExpected, $obj->getFieldValue("name"));
        $this->assertSame($storageExpected, $obj->getFieldStorageValue("name"));
    }





    //
    // SETVALUES | GETVALUES | MAGIC SET/GET
    //

    public function test_method_setget_values_incompatible_model()
    {
        $obj = $this->provider_model_user();


        // Avalia as condições iniciais
        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($initi);
        $this->assertFalse($v);
        $this->assertTrue(is_array($realState));
        $this->assertSame(3, count($realState));
        $this->assertSame("error.dm.field.value.not.allow.undefined", $realState["name"]);
        $this->assertSame("valid", $realState["email"]);
        $this->assertSame("valid", $realState["cpf"]);
        $this->assertSame(null, $validateState);



        $val            = [
            "name"          => "valid name",
            "invalidfield"  => "invalid value",
            "cpf"           => "519.212.420-43"
        ];
        $r              = $obj->setValues($val);
        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($initi);
        $this->assertFalse($v);
        $this->assertFalse($validateCanSet);
        $this->assertTrue(is_array($realState));
        $this->assertSame(3, count($realState));
        $this->assertSame("error.dm.field.value.not.allow.undefined", $realState["name"]);
        $this->assertSame("valid", $realState["email"]);
        $this->assertSame("valid", $realState["cpf"]);
        $this->assertSame("valid", $validateState["name"]);
        $this->assertSame("error.dm.model.unespected.field.name", $validateState["invalidfield"]);
        $this->assertSame("valid", $validateState["cpf"]);

        $rawExpected = [
            "name"  => undefined,
            "email"  => undefined,
            "cpf"  => undefined
        ];
        $formatedExpected = [
            "name"  => undefined,
            "email"  => undefined,
            "cpf"  => undefined
        ];
        $storageExpected = [
            "name"  => null,
            "email"  => null,
            "cpf"  => null
        ];

        $this->assertSame($rawExpected, $obj->getRawValues());
        $this->assertSame($formatedExpected, $obj->getValues());
        $this->assertSame($storageExpected, $obj->getStorageValues());
    }


    public function test_method_setget_values_01()
    {
        $obj = $this->provider_model_user();


        // Avalia as condições iniciais
        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($initi);
        $this->assertFalse($v);
        $this->assertTrue(is_array($realState));
        $this->assertSame(3, count($realState));
        $this->assertSame("error.dm.field.value.not.allow.undefined", $realState["name"]);
        $this->assertSame("valid", $realState["email"]);
        $this->assertSame("valid", $realState["cpf"]);
        $this->assertSame(null, $validateState);



        $val            = [
            "name"          => "valid name",
            "email"         => "VALIDEMAIL@domain.com",
            "cpf"           => "519212420-43"
        ];
        $r              = $obj->setValues($val);
        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertTrue($r);
        $this->assertFalse($initi);
        $this->assertTrue($v);
        $this->assertTrue($validateCanSet);
        $this->assertSame("valid", $realState);
        $this->assertSame("valid", $validateState);

        $rawExpected = [
            "name"          => "valid name",
            "email"         => "VALIDEMAIL@domain.com",
            "cpf"           => "519212420-43"
        ];
        $formatedExpected = [
            "name"          => "valid name",
            "email"         => "validemail@domain.com",
            "cpf"           => "519.212.420-43"
        ];
        $storageExpected = [
            "name"          => "valid name",
            "email"         => "validemail@domain.com",
            "cpf"           => "51921242043"
        ];

        $this->assertSame($rawExpected, $obj->getRawValues());
        $this->assertSame($formatedExpected, $obj->getValues());
        $this->assertSame($storageExpected, $obj->getStorageValues());
    }


    public function test_method_setget_values_02()
    {
        $obj = $this->provider_model_user();


        // Avalia as condições iniciais
        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($initi);
        $this->assertFalse($v);
        $this->assertTrue(is_array($realState));
        $this->assertSame(3, count($realState));
        $this->assertSame("error.dm.field.value.not.allow.undefined", $realState["name"]);
        $this->assertSame("valid", $realState["email"]);
        $this->assertSame("valid", $realState["cpf"]);
        $this->assertSame(null, $validateState);



        $val            = [
            "name"          => "invalid name with a big name",
            "email"         => "VALIDEMAIL@domain.com",
            "cpf"           => "519212420-40"
        ];


        $r              = $obj->validateValues($val);
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertTrue($validateCanSet);
        $this->assertTrue(is_array($validateState));
        $this->assertSame(3, count($validateState));
        $this->assertSame("error.dm.field.value.constraint.length.violated", $validateState["name"]);
        $this->assertSame("valid", $validateState["email"]);
        $this->assertSame("error.dm.field.value.invalid.input.format", $validateState["cpf"]);



        $r              = $obj->setValues($val);
        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();
        $validateCanSet = $obj->getLastValidateCanSet();

        $this->assertFalse($r);
        $this->assertFalse($initi);
        $this->assertFalse($v);
        $this->assertTrue($validateCanSet);
        $this->assertTrue(is_array($realState));
        $this->assertSame(3, count($realState));
        $this->assertSame("error.dm.field.value.constraint.length.violated", $realState["name"]);
        $this->assertSame("valid", $realState["email"]);
        $this->assertSame("error.dm.field.value.invalid.input.format", $realState["cpf"]);
        $this->assertSame($realState, $validateState);

        $rawExpected = [
            "name"          => "invalid name with a big name",
            "email"         => "VALIDEMAIL@domain.com",
            "cpf"           => "519212420-40"
        ];
        $formatedExpected = [
            "name"          => "invalid name with a big name",
            "email"         => "validemail@domain.com",
            "cpf"           => "519212420-40"
        ];
        $storageExpected = [
            "name"          => null,
            "email"         => "validemail@domain.com",
            "cpf"           => null
        ];

        $this->assertSame($rawExpected, $obj->getRawValues());
        $this->assertSame($formatedExpected, $obj->getValues());
        $this->assertSame($storageExpected, $obj->getStorageValues());
    }



    public function test_method_setget_values_magicsetget()
    {
        $obj = $this->provider_model_user();


        // Avalia as condições iniciais
        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();
        $validateState  = $obj->getLastValidateState();

        $this->assertTrue($initi);
        $this->assertFalse($v);
        $this->assertTrue(is_array($realState));
        $this->assertSame(3, count($realState));
        $this->assertSame("error.dm.field.value.not.allow.undefined", $realState["name"]);
        $this->assertSame("valid", $realState["email"]);
        $this->assertSame("valid", $realState["cpf"]);
        $this->assertSame(null, $validateState);



        $obj->name = "valid name";
        $obj->email = "VALIDEMAIL@domain.com";
        $obj->cpf = "519212420-43";


        $initi          = $obj->isInitial();
        $v              = $obj->isValid();
        $realState      = $obj->getState();


        $this->assertFalse($initi);
        $this->assertTrue($v);
        $this->assertSame("valid", $realState);

        $rawExpected = [
            "name"          => "valid name",
            "email"         => "VALIDEMAIL@domain.com",
            "cpf"           => "519212420-43"
        ];
        $formatedExpected = [
            "name"          => "valid name",
            "email"         => "validemail@domain.com",
            "cpf"           => "519.212.420-43"
        ];
        $storageExpected = [
            "name"          => "valid name",
            "email"         => "validemail@domain.com",
            "cpf"           => "51921242043"
        ];

        $this->assertSame($rawExpected, $obj->getRawValues());
        $this->assertSame($formatedExpected, $obj->getValues());
        $this->assertSame($storageExpected, $obj->getStorageValues());



        $fNames     = [];
        $fValues    = [];
        foreach ($obj as $fieldName => $fieldValue) {
            $this->assertSame($obj->getFieldValue($fieldName), $fieldValue);
        }
    }





    //
    // MAGIC SET/GET GETFIELD
    //

    public function test_method_getfield()
    {
        $obj = $this->provider_model_user();

        $this->assertSame("AeonDigital\\SimpleType\\stString", $obj->_name->getType());
        $this->assertSame("AeonDigital\\SimpleType\\stString", $obj->_email->getType());
        $this->assertSame("AeonDigital\\SimpleType\\stString", $obj->_cpf->getType());
        $this->assertSame("AeonDigital\\DataFormat\\Patterns\\Brasil\\CPF", $obj->_cpf->getInputFormat());
    }
}
