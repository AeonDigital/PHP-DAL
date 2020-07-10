<?php return [
  'tableName' => 'Cidade',
  'alias' => 'cid',
  'description' => 'Coleção de cidades brasileiras.',
  //'executeAfterCreateTable' => array(0 => 'ALTER TABLE Cidade ADD CONSTRAINT uc_cid_Nome_Estado_Capital UNIQUE (Nome, Estado, Capital);'),
  'uniqueMultipleKeys' => [
    [
      'Nome', 1 => 'Estado', 2 => 'Capital'
    ]
  ],
  'columns' =>
  [
    [
      'name' => 'Nome',
      'description' => 'Nome da cidade.',
      'type' => 'String',
      'length' => 128,
      'allowNull' => false,
    ],
    [
      'name' => 'Estado',
      'description' => 'Sigla do estado.',
      'type' => 'String',
      'inputFormat' => 'Upper',
      'length' => 2,
      'allowNull' => false,
    ],
    [
      'name' => 'Capital',
      'description' => 'Indica se a cidade é capital do seu estado.',
      'type' => 'Bool',
      'allowNull' => false,
    ],
    [
      'name' => 'EnderecosPostais',
      'description' => 'Endereços postais da cidade.',
      'fkDescription' => 'Cidade correlacionada com este EnderecoPostal',
      'fkTableName' => 'EnderecoPostal[]',
      'fkOnDelete' => 'CASCADE',
    ],
  ],
];
