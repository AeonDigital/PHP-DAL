<?php return [
  'tableName' => 'EnderecoPostal',
  'alias' => 'ep',
  'description' => 'Endereço postal.',
  'columns' =>
  [
    [
      'name' => 'CEP',
      'description' => 'Código de endereçamento postal.',
      'type' => 'String',
      'inputFormat' => 'Brasil.ZipCode',
      'length' => 10,
      'allowNull' => false,
    ],
    [
      'name' => 'TipoDeEndereco',
      'description' => 'Indica se o endereço é residencial, comercial, ou de outra natureza qualquer.',
      'type' => 'String',
      'length' => 32,
      'allowNull' => false,
    ],
    [
      'name' => 'TipoDeLogradouro',
      'description' => 'Tipo de logradouro (rua, avenida, travessa...).',
      'type' => 'String',
      'length' => 32,
      'allowNull' => false,
    ],
    [
      'name' => 'Logradouro',
      'description' => 'Nome do logradouro.',
      'type' => 'String',
      'length' => 128,
      'allowNull' => false,
    ],
    [
      'name' => 'Numero',
      'description' => 'Número da residência.',
      'type' => 'Int',
      'max' => 99999,
      'allowNull' => false,
    ],
    [
      'name' => 'Complemento',
      'description' => 'Complemento do endereço.',
      'type' => 'String',
      'length' => 128,
    ],
    [
      'name' => 'Bairro',
      'description' => 'Nome do bairro.',
      'type' => 'String',
      'length' => 128,
      'allowNull' => false,
    ],
    [
      'name' => 'Referencia',
      'description' => 'Referência para o endereço.',
      'type' => 'String',
      'length' => 255,
    ]
  ],
];
