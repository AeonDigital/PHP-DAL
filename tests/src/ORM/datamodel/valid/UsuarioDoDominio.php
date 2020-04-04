<?php return array (
  'tableName' => 'UsuarioDoDominio',
  'alias' => 'udd',
  'description' => 'Conta de um usuário que pode efetuar login em aplicações do domínio.',
  'columns' => 
  array (
    0 => 
    array (
      'name' => 'Ativo',
      'description' => 'Indica se a conta do usuário está ativa para o domínio.',
      'type' => 'Bool',
      'default' => true,
      'allowNull' => false,
    ),
    1 => 
    array (
      'name' => 'Locale',
      'description' => 'Locale padrão para o usuário.',
      'type' => 'String',
      'inputFormat' => 'World.Locale',
      'length' => 5,
      'default' => 'pt-BR',
      'enumerator' => '/enum/Locale.php',
      'allowNull' => false,
    ),
    2 => 
    array (
      'name' => 'DataDeRegistro',
      'description' => 'Data e hora deste registro.',
      'type' => 'DateTime',
      'readOnly' => true,
      'default' => 'NOW()',
      'allowNull' => false,
    ),
    3 => 
    array (
      'name' => 'Nome',
      'description' => 'Nome do usuário.',
      'type' => 'String',
      'length' => 128,
      'allowNull' => false,
      'allowEmpty' => false,
    ),
    4 => 
    array (
      'name' => 'Genero',
      'description' => 'Gênero do usuário.',
      'type' => 'String',
      'length' => 32,
      'enumerator' => '/enum/Gender.php',
      'allowNull' => false,
    ),
    5 => 
    array (
      'name' => 'Login',
      'description' => 'Login do usuário.',
      'type' => 'String',
      'inputFormat' => 'World.Email',
      'length' => 64,
      'readOnly' => true,
      'unique' => true,
      'allowNull' => false,
      'index' => true,
    ),
    6 => 
    array (
      'name' => 'ShortLogin',
      'description' => 'Login curto.',
      'type' => 'String',
      'inputFormat' => 'Lower',
      'length' => 32,
      'unique' => true,
      'allowNull' => false,
      'index' => true,
    ),
    7 => 
    array (
      'name' => 'Senha',
      'description' => 'Senha de acesso.',
      'type' => 'String',
      'inputFormat' => 'World.Password',
      'length' => 255,
      'allowNull' => false,
    ),
    8 => 
    array (
      'name' => 'DataDeDefinicaoDeSenha',
      'description' => 'Data e hora da definição da senha atual.',
      'type' => 'DateTime',
      'default' => 'NOW()',
      'allowNull' => false,
    ),
    9 => 
    array (
      'name' => 'Apresentacao',
      'description' => 'Texto de apresentação do usuário.',
      'type' => 'String'
    ),
    10 => 
    array (
      'name' => 'EmailContato',
      'description' => 'Email para contato.',
      'type' => 'String',
      'inputFormat' => 'World.Email',
      'length' => 64,
      'allowNull' => false,
    ),
    11 => 
    array (
      'name' => 'ValorInteiro',
      'description' => 'Valor inteiro para testes.',
      'type' => 'Int',
      'min' => 0,
      'max' => 100000,
      'default' => 500,
      'allowNull' => false,
    ),
    12 => 
    array (
      'name' => 'ValorFloat',
      'description' => 'Valor float para testes.',
      'type' => 'Float',
      'min' => 0,
      'max' => 10,
      'default' => 8.556,
      'allowNull' => false,
    ),
    13 => 
    array (
      'name' => 'ValorReal',
      'description' => 'Valor real para testes.',
      'type' => 'Real',
      'min' => 0,
      'max' => 10,
      'default' => 7.778,
      'allowNull' => false,
    ),
    14 => 
    array (
      'name' => 'Sessao',
      'description' => 'Sessão atualmente aberta.',
      'fkTableName' => 'SessaoDeAcesso',
    ),
    15 => 
    array (
      'name' => 'GruposDeSeguranca',
      'description' => 'Coleção dos grupos de segurança que este usuário está apto a utilizar.',
      'fkTableName' => 'GrupoDeSeguranca[]',
      'fkDescription' => 'UsuarioDoDominio em GrupoDeSeguranca',
      'fkLinkTable' => true,
      'fkAllowNull' => false,
    ),
  ),
);
