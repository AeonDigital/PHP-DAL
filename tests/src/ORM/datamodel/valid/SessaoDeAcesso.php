<?php return array (
  'tableName' => 'SessaoDeAcesso',
  'alias' => 'sda',
  'description' => 'Define uma sessão de acesso para um usuário que efetuou login.',
  'columns' => 
  array (
    0 => 
    array (
      'name' => 'DataDoLogin',
      'description' => 'Data e hora do login.',
      'type' => 'DateTime',
      'readOnly' => true,
      'default' => 'NOW()',
      'allowNull' => false,
    ),
    1 => 
    array (
      'name' => 'Login',
      'description' => 'Login com o qual a sessão foi autenticada.',
      'type' => 'String',
      'length' => 64,
      'readOnly' => true,
      'allowNull' => false,
    ),
    2 => 
    array (
      'name' => 'Aplicacao',
      'description' => 'Aplicação na qual o usuário efetuou o login.',
      'type' => 'String',
      'length' => 32,
      'readOnly' => true,
      'enumerator' => '/enum/DomainApplication.php',
      'allowNull' => false,
    ),
    3 => 
    array (
      'name' => 'ProfileInUse',
      'description' => 'Perfil de segurança do usuário sendo usado no momento.',
      'type' => 'String',
      'inputFormat' => 'Upper',
      'length' => 32,
      'allowNull' => false,
    ),
    4 => 
    array (
      'name' => 'SessionTimeOut',
      'description' => 'Data e hora para o fim da sessão.',
      'type' => 'DateTime',
      'allowNull' => false,
    ),
    5 => 
    array (
      'name' => 'Ip',
      'description' => 'Ip do usuário no momento do login.',
      'type' => 'String',
      'length' => 64,
      'readOnly' => true,
      'allowNull' => false,
    ),
    6 => 
    array (
      'name' => 'Browser',
      'description' => 'Identificação do nevegador do usuário no momento do login.',
      'type' => 'String',
      'length' => 256,
      'readOnly' => true,
      'allowNull' => false,
    ),
    7 => 
    array (
      'name' => 'Locale',
      'description' => 'Locale do usuário.',
      'type' => 'String',
      'inputFormat' => 'World.Locale',
      'length' => 5,
      'default' => 'pt-BR',
      'enumerator' => '/enum/Locale.php',
      'allowNull' => false,
    ),
    8 => 
    array (
      'name' => 'SessionRenew',
      'description' => 'Indica se a sessão é renovada automaticamente a cada iteração do usuário.',
      'type' => 'Bool',
      'default' => true,
      'allowNull' => false,
    ),
    9 => 
    array (
      'name' => 'SessionID',
      'description' => 'ID da sessão do usuário.',
      'type' => 'String',
      'length' => 160,
      'unique' => true,
      'allowNull' => false,
    ),
  ),
);
