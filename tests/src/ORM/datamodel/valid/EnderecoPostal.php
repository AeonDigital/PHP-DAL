<?php return array (
  'tableName' => 'EnderecoPostal',
  'alias' => 'ep',
  'description' => 'Endereço postal.',
  'columns' => 
  array (
    0 => 
    array (
      'name' => 'CEP',
      'description' => 'Código de endereçamento postal.',
      'type' => 'String',
      'inputFormat' => 'Brasil.ZipCode',
      'length' => 10,
      'allowNull' => false,
    ),
    1 => 
    array (
      'name' => 'TipoDeEndereco',
      'description' => 'Indica se o endereço é residencial, comercial, ou de outra natureza qualquer.',
      'type' => 'String',
      'length' => 32,
      'allowNull' => false,
    ),
    2 => 
    array (
      'name' => 'TipoDeLogradouro',
      'description' => 'Tipo de logradouro (rua, avenida, travessa...).',
      'type' => 'String',
      'length' => 32,
      'allowNull' => false,
    ),
    3 => 
    array (
      'name' => 'Logradouro',
      'description' => 'Nome do logradouro.',
      'type' => 'String',
      'length' => 128,
      'allowNull' => false,
    ),
    4 => 
    array (
      'name' => 'Numero',
      'description' => 'Número da residência.',
      'type' => 'Int',
      'max' => 99999,
      'allowNull' => false,
    ),
    5 => 
    array (
      'name' => 'Complemento',
      'description' => 'Complemento do endereço.',
      'type' => 'String',
      'length' => 128,
    ),
    6 => 
    array (
      'name' => 'Bairro',
      'description' => 'Nome do bairro.',
      'type' => 'String',
      'length' => 128,
      'allowNull' => false,
    ),
    7 => 
    array (
      'name' => 'Referencia',
      'description' => 'Referência para o endereço.',
      'type' => 'String',
      'length' => 255,
    ),
    /*8 =>
    array(
      'name' => 'Cidade',
      'description' => 'Cidade correlacionada com este EnderecoPostal.',
      'fkTableName' => 'Cidade',
      'fkOnDelete' => 'CASCADE',
    ),*/
  ),
);
