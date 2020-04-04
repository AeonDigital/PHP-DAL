<?php return array (
  'tableName' => 'GrupoDeSeguranca',
  'alias' => 'gds',
  'description' => 'Define um perfil de segurança para um conjunto de usuários.',
  'columns' => 
  array (
    0 => 
    array (
      'name' => 'Ativo',
      'description' => 'Indica se este grupo está ativo ou não.',
      'type' => 'Bool',
      'default' => 1,
      'allowNull' => false,
    ),
    1 => 
    array (
      'name' => 'Aplicacao',
      'description' => 'Nome da aplicação para a qual este grupo é utilizado.',
      'type' => 'String',
      'length' => 32,
      'readOnly' => true,
      'enumerator' => '/enum/DomainApplication.php',
      'allowNull' => false,
    ),
    2 => 
    array (
      'name' => 'Nome',
      'description' => 'Nome para este grupo de segurança.',
      'type' => 'String',
      'inputFormat' => 'Upper',
      'length' => 32,
      'allowNull' => false,
    ),
    3 => 
    array (
      'name' => 'Descricao',
      'description' => 'Descrição do grupo de segurança.',
      'type' => 'String',
      'length' => 255,
    ),
    4 => 
    array (
      'name' => 'UseConnection',
      'description' => 'Identificador da conexão com o banco de dados que será utilizado pelos usuários deste grupo.',
      'type' => 'String',
      'inputFormat' => 'Upper',
      'length' => 32,
      'allowNull' => false,
    ),
    5 => 
    array (
      'name' => 'PoliticaPadrao',
      'description' => 'Indica a politica de segurança comum para as rotas [b (block) | f (free)].',
      'type' => 'String',
      'inputFormat' => 'Upper',
      'length' => 1,
      'default' => 'b',
      'allowNull' => false,
    ),
    6 => 
    array (
      'name' => 'UsuariosDoDominio',
      'description' => 'Coleção de usuários para este grupo de segurança.',
      'fkTableName' => 'UsuarioDoDominio[]',
      'fkDescription' => 'GrupoDeSeguranca em UsuarioDoDominio',
      'fkLinkTable' => true,
      'fkAllowNull' => false,
    ),
  ),
);
