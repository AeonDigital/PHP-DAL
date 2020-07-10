<?php return [
  'tableName' => 'GrupoDeSeguranca',
  'alias' => 'gds',
  'description' => 'Define um perfil de segurança para um conjunto de usuários.',
  'columns' =>
  [
    [
      'name' => 'Ativo',
      'description' => 'Indica se este grupo está ativo ou não.',
      'type' => 'Bool',
      'default' => 1,
      'allowNull' => false,
    ],
    [
      'name' => 'Aplicacao',
      'description' => 'Nome da aplicação para a qual este grupo é utilizado.',
      'type' => 'String',
      'length' => 32,
      'readOnly' => true,
      'enumerator' => '/enum/DomainApplication.php',
      'allowNull' => false,
    ],
    [
      'name' => 'Nome',
      'description' => 'Nome para este grupo de segurança.',
      'type' => 'String',
      'inputFormat' => 'Upper',
      'length' => 32,
      'allowNull' => false,
    ],
    [
      'name' => 'Descricao',
      'description' => 'Descrição do grupo de segurança.',
      'type' => 'String',
      'length' => 255,
    ],
    [
      'name' => 'UseConnection',
      'description' => 'Identificador da conexão com o banco de dados que será utilizado pelos usuários deste grupo.',
      'type' => 'String',
      'inputFormat' => 'Upper',
      'length' => 32,
      'allowNull' => false,
    ],
    [
      'name' => 'PoliticaPadrao',
      'description' => 'Indica a politica de segurança comum para as rotas [b (block) | f (free)].',
      'type' => 'String',
      'inputFormat' => 'Upper',
      'length' => 1,
      'default' => 'b',
      'allowNull' => false,
    ],
    [
      'name' => 'UsuariosDoDominio',
      'description' => 'Coleção de usuários para este grupo de segurança.',
      'fkTableName' => 'UsuarioDoDominio[]',
      'fkDescription' => 'GrupoDeSeguranca em UsuarioDoDominio',
      'fkLinkTable' => true,
      'fkAllowNull' => false,
      'fkUnique' => true,
      'fkLinkTableColumns' => [
        [
          'name' => 'Padrao',
          'description' => 'Informa se este é o Grupo de Segurança padrão para este usuário.',
          'type' => 'Bool',
          'default' => 0,
          'allowNull' => false,
        ]
      ]
    ],
  ],
];
