<?php
declare (strict_types = 1);

use PHPUnit\Framework\TestCase;

use AeonDigital\ORM\DataColumn as DataColumn;

require_once __DIR__ . "/../../phpunit.php";






class t01DataColumnTest extends TestCase
{





    private function provider_field_name()
    {
        return new DataColumn([
            "name"                      => "name",
            "type"                      => "String",
            "length"                    => 100,
            "allowNull"                 => false,
            "allowEmpty"                => false
        ]);
    }
    private function provider_field_cpf()
    {
        return new DataColumn([
            "name"                      => "cpf",
            "type"                      => "String",
            "inputFormat"               => "Brasil.CPF",
            "allowNull"                 => false,
            "allowEmpty"                => false
        ]);
    }
    private function provider_field_email()
    {
        return new DataColumn([
            "name"                      => "email",
            "type"                      => "String",
            "inputFormat"               => "World.Email",
            "unique"                    => true
        ]);
    }





    //
    // CONSTRUCTOR
    //

    public function test_constructor_ok()
    {
        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String"
        ]);
        $this->assertTrue(is_a($obj, DataColumn::class));
    }





    //
    // UNIQUE
    //

    public function test_constructor_ok_unique()
    {
        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String"
        ]);
        $this->assertFalse($obj->isUnique());


        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String",
            "unique"                    => true
        ]);
        $this->assertTrue($obj->isUnique());
    }





    //
    // AUTOINCREMENT
    //

    public function test_constructor_ok_autoincrement()
    {
        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String"
        ]);
        $this->assertFalse($obj->isAutoIncrement());


        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String",
            "autoIncrement"             => true
        ]);
        $this->assertTrue($obj->isAutoIncrement());
    }





    //
    // PRIMARYKEY
    //

    public function test_constructor_ok_primarykey()
    {
        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String"
        ]);
        $this->assertFalse($obj->isPrimaryKey());


        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String",
            "primaryKey"                => true
        ]);
        $this->assertTrue($obj->isPrimaryKey());
    }





    //
    // FOREIGNKEY
    //

    public function test_constructor_ok_foreignkey()
    {
        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String"
        ]);
        $this->assertFalse($obj->isForeignKey());
    }





    //
    // INDEX
    //

    public function test_constructor_ok_index()
    {
        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String"
        ]);
        $this->assertFalse($obj->isIndex());


        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String",
            "index"                     => true
        ]);
        $this->assertTrue($obj->isIndex());
    }





    //
    // INPUTFORMAT
    // Métodos especiais de transformação
    //

    public function test_constructor_ok_inputformat_upper()
    {
        $testValue = "UPPER";

        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => $testValue
        ]);
        $this->assertSame($testValue, $obj->getInputFormat());

        $val = "valid value";
        $r = $obj->setValue($val);
        $this->assertTrue($r);
        $this->assertSame(strtoupper($val), $obj->getValue(true));
        $this->assertSame(strtoupper($val), $obj->getValue());
    }


    public function test_constructor_ok_inputformat_lower()
    {
        $testValue = "LOWER";

        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => $testValue
        ]);
        $this->assertSame($testValue, $obj->getInputFormat());

        $val = "VALid Name";
        $r = $obj->setValue($val);
        $this->assertTrue($r);
        $this->assertSame(strtolower($val), $obj->getValue(true));
        $this->assertSame(strtolower($val), $obj->getValue());
    }


    public function test_constructor_ok_inputformat_names_ptbr()
    {
        $testValue = "NAMES_PTBR";

        $obj = new DataColumn([
            "name"                      => "validName",
            "type"                      => "String",
            "inputFormat"               => $testValue
        ]);
        $this->assertSame($testValue, $obj->getInputFormat());

        $val = "o nome próprio de MUITOS artigos À serem testados";
        $exp = "O Nome Próprio de Muitos Artigos à Serem Testados";
        $r = $obj->setValue($val);
        $this->assertTrue($r);
        $this->assertSame($exp, $obj->getValue(true));
        $this->assertSame($exp, $obj->getValue());
    }
}
