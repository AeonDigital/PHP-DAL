<?php return array (
  'tableName' => 'Cidade',
  'alias' => 'samealias',
  'description' => 'Coleção de cidades brasileiras.',
  'columns' => 
  array (
    0 => 
    array (
      'name' => 'Nome',
      'description' => 'Nome da cidade.',
      'type' => 'String',
      'length' => 128,
    ),
    1 => 
    array (
      'name' => 'Estado',
      'description' => 'Sigla do estado.',
      'type' => 'String',
      'inputFormat' => 'Upper',
      'length' => 2,
    ),
    2 => 
    array (
      'name' => 'Capital',
      'description' => 'Indica se a cidade é capital do seu estado.',
      'type' => 'Bool',
    ),
  ),
);
