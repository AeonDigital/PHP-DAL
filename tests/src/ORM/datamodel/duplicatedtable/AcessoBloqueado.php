<?php return array (
  'tableName' => 'Cidade',
  'alias' => 'ab',
  'description' => 'Registra o bloqueio de um usuário ou endereço IP.',
  'columns' => 
  array (
    0 => 
    array (
      'name' => 'Aplicacao',
      'description' => 'Aplicação na qual o bloqueio está sendo efetuado.',
      'type' => 'String',
      'length' => 32,
      'readOnly' => true
    ),
    1 => 
    array (
      'name' => 'Ip',
      'description' => 'Ip registrado no momento do bloqueio.',
      'type' => 'String',
      'length' => 64,
      'readOnly' => true,
    ),
    2 => 
    array (
      'name' => 'DataDoBloqueio',
      'description' => 'Data e hora do momento do bloqueio.',
      'type' => 'DateTime',
      'readOnly' => true,
      'default' => 'NOW()',
    ),
    3 => 
    array (
      'name' => 'DataDoDesbloqueio',
      'description' => 'Data e hora para o desbloqueio.',
      'type' => 'DateTime',
    ),
  ),
);
