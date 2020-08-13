<?php return [
  'updateSet' => [
    'type' => 'alter',
    'currentName' => 'Cidade',
    'executeBefore' => [],
    'executeAfter' => []
  ],
  'tableName' => 'Estado',
  'columns' =>
  [
    [
      'updateSet' => [
        'type' => 'drop',
        'currentName' => 'Estado',
        'executeBefore' => [],
        'executeAfter' => []
      ],
      'name' => 'Estado',
    ],
    [
      'updateSet' => [
        'type' => 'alter',
        'currentName' => 'Capital',
        'executeBefore' => [],
        'executeAfter' => [
            'UPDATE Cidade SET Capital="Sim" WHERE Capital="1"',
            'UPDATE Cidade SET Capital="Não" WHERE Capital="0"',
        ]
      ],
      'name' => 'Capital',
      'description' => 'Indica se a cidade é capital do seu estado.',
      'type' => 'String',
      'length' => 4,
      'allowNull' => false,
    ],
    [
      'updateSet' => [
        'type' => 'add',
        'setAfter' => 'Capital',
        'currentName' => '',
        'executeBefore' => [],
        'executeAfter' => []
      ],
      'name' => 'Apresentacao',
      'description' => 'Breve texto de apresentação para a cidade.',
      'type' => 'String',
      'length' => 255,
      'allowNull' => true,
    ],
  ],
];
