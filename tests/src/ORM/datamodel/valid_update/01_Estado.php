<?php return [
  'updateSet' => [
    'type' => 'create',
    'currentName' => '',
    'executeBefore' => [],
    'executeAfter' => []
  ],
  'tableName' => 'Estado',
  'alias' => 'est',
  'description' => 'Coleção de Estados brasileiros.',
  'executeAfterCreateTable' => [],
  'columns' =>
  [
    [
      'name' => 'Nome',
      'description' => 'Nome do estado.',
      'type' => 'String',
      'length' => 64,
      'allowNull' => false,
    ],
    [
      'name' => 'Sigla',
      'description' => 'Sigla do estado.',
      'type' => 'String',
      'inputFormat' => 'Upper',
      'length' => 2,
      'allowNull' => false,
    ],
    [
      'name' => 'Cidades',
      'description' => 'Cidades deste estado.',
      'fkDescription' => 'Estado desta cidade',
      'fkTableName' => 'Cidade[]',
      'fkOnDelete' => 'CASCADE',
    ],
  ],
];
