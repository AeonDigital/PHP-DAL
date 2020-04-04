<?php return array (
  'Cidade' => 
  array (
    'modelFilePath' => 'Cidade.php',
    'ormInstructions' => 
    array (
      'select' => 'SELECT Id, Nome, Estado, Capital FROM Cidade WHERE Id=:Id;',
      'selectChild' => 
      array (
        'EnderecosPostais' => 
        array (
          'select' => 'SELECT Id as fkId FROM EnderecoPostal WHERE Cidade_Id=:Id;',
          'oColumnFK' => NULL,
          'linkTableName' => NULL,
          'linkTableColumns' => NULL,
        ),
      ),
      'selectParentId' => 
      array (
      ),
      'attatchWith' => 
      array (
        'EnderecoPostal' => 'UPDATE EnderecoPostal SET Cidade_Id=:thisId WHERE Id=:tgtId;',
      ),
      'detachWith' => 
      array (
        'EnderecoPostal' => 'UPDATE EnderecoPostal SET Cidade_Id=null WHERE Id=:tgtId;',
      ),
      'detachWithAll' => 
      array (
        'EnderecoPostal' => 'UPDATE EnderecoPostal SET Cidade_Id=null WHERE Cidade_Id=:thisId;',
      ),
      'oColumn' => 
      array (
      ),
      'singleFK' => 
      array (
      ),
      'collectionFK' => 
      array (
      ),
    ),
  ),
  'EnderecoPostal' => 
  array (
    'modelFilePath' => 'EnderecoPostal.php',
    'ormInstructions' => 
    array (
      'select' => 'SELECT Id, CEP, TipoDeEndereco, TipoDeLogradouro, Logradouro, Numero, Complemento, Bairro, Referencia FROM EnderecoPostal WHERE Id=:Id;',
      'selectChild' => 
      array (
      ),
      'selectParentId' => 
      array (
        'Cidade' => 'SELECT Cidade_Id FROM EnderecoPostal WHERE Id=:thisId;',
      ),
      'attatchWith' => 
      array (
        'Cidade' => 'UPDATE EnderecoPostal SET Cidade_Id=:tgtId WHERE Id=:thisId;',
      ),
      'detachWith' => 
      array (
        'Cidade' => 'UPDATE EnderecoPostal SET Cidade_Id=null WHERE Id=:thisId;',
      ),
      'detachWithAll' => 
      array (
        'Cidade' => 'UPDATE EnderecoPostal SET Cidade_Id=null WHERE Id=:thisId;',
      ),
      'oColumn' => 
      array (
      ),
      'singleFK' => 
      array (
      ),
      'collectionFK' => 
      array (
      ),
    ),
  ),
  'GrupoDeSeguranca' => 
  array (
    'modelFilePath' => 'GrupoDeSeguranca.php',
    'ormInstructions' => 
    array (
      'select' => 'SELECT Id, Ativo, Aplicacao, Nome, Descricao, UseConnection, PoliticaPadrao FROM GrupoDeSeguranca WHERE Id=:Id;',
      'selectChild' => 
      array (
        'UsuariosDoDominio' => 
        array (
          'select' => 'SELECT UsuarioDoDominio_Id as fkId FROM udd_to_gds WHERE GrupoDeSeguranca_Id=:Id;',
          'oColumnFK' => NULL,
          'linkTableName' => 'udd_to_gds',
          'linkTableColumns' => 
          array (
            0 => 'GrupoDeSeguranca_Id',
            1 => 'UsuarioDoDominio_Id',
          ),
        ),
      ),
      'selectParentId' => 
      array (
      ),
      'attatchWith' => 
      array (
        'UsuarioDoDominio' => 'INSERT INTO udd_to_gds (GrupoDeSeguranca_Id, UsuarioDoDominio_Id) VALUES (:thisId, :tgtId);',
      ),
      'detachWith' => 
      array (
        'UsuarioDoDominio' => 'DELETE FROM udd_to_gds WHERE GrupoDeSeguranca_Id=:thisId AND UsuarioDoDominio_Id=:tgtId;',
      ),
      'detachWithAll' => 
      array (
        'UsuarioDoDominio' => 'DELETE FROM udd_to_gds WHERE GrupoDeSeguranca_Id=:thisId;',
      ),
      'oColumn' => 
      array (
      ),
      'singleFK' => 
      array (
      ),
      'collectionFK' => 
      array (
      ),
    ),
  ),
  'SessaoDeAcesso' => 
  array (
    'modelFilePath' => 'SessaoDeAcesso.php',
    'ormInstructions' => 
    array (
      'select' => 'SELECT Id, DataDoLogin, Login, Aplicacao, ProfileInUse, SessionTimeOut, Ip, Browser, Locale, SessionRenew, SessionID FROM SessaoDeAcesso WHERE Id=:Id;',
      'selectChild' => 
      array (
      ),
      'selectParentId' => 
      array (
        'UsuarioDoDominio' => 'SELECT Id FROM UsuarioDoDominio WHERE SessaoDeAcesso_Id=:thisId;',
      ),
      'attatchWith' => 
      array (
        'UsuarioDoDominio' => 'UPDATE UsuarioDoDominio SET SessaoDeAcesso_Id=:thisId WHERE Id=:tgtId;',
      ),
      'detachWith' => 
      array (
        'UsuarioDoDominio' => 'UPDATE UsuarioDoDominio SET SessaoDeAcesso_Id=null WHERE Id=:tgtId;',
      ),
      'detachWithAll' => 
      array (
        'UsuarioDoDominio' => 'UPDATE UsuarioDoDominio SET SessaoDeAcesso_Id=null WHERE SessaoDeAcesso_Id=:thisId;',
      ),
      'oColumn' => 
      array (
      ),
      'singleFK' => 
      array (
      ),
      'collectionFK' => 
      array (
      ),
    ),
  ),
  'UsuarioDoDominio' => 
  array (
    'modelFilePath' => 'UsuarioDoDominio.php',
    'ormInstructions' => 
    array (
      'select' => 'SELECT Id, Ativo, Locale, DataDeRegistro, Nome, Genero, Login, ShortLogin, Senha, DataDeDefinicaoDeSenha, Apresentacao, EmailContato, ValorInteiro, ValorFloat, ValorReal FROM UsuarioDoDominio WHERE Id=:Id;',
      'selectChild' => 
      array (
        'Sessao' => 
        array (
          'select' => 'SELECT SessaoDeAcesso_Id as fkId FROM UsuarioDoDominio WHERE Id=:Id;',
          'oColumnFK' => NULL,
          'linkTableName' => NULL,
          'linkTableColumns' => NULL,
        ),
        'GruposDeSeguranca' => 
        array (
          'select' => 'SELECT GrupoDeSeguranca_Id as fkId FROM udd_to_gds WHERE UsuarioDoDominio_Id=:Id;',
          'oColumnFK' => NULL,
          'linkTableName' => 'udd_to_gds',
          'linkTableColumns' => 
          array (
            0 => 'UsuarioDoDominio_Id',
            1 => 'GrupoDeSeguranca_Id',
          ),
        ),
      ),
      'selectParentId' => 
      array (
      ),
      'attatchWith' => 
      array (
        'SessaoDeAcesso' => 'UPDATE UsuarioDoDominio SET SessaoDeAcesso_Id=:tgtId WHERE Id=:thisId;',
        'GrupoDeSeguranca' => 'INSERT INTO udd_to_gds (UsuarioDoDominio_Id, GrupoDeSeguranca_Id) VALUES (:thisId, :tgtId);',
      ),
      'detachWith' => 
      array (
        'SessaoDeAcesso' => 'UPDATE UsuarioDoDominio SET SessaoDeAcesso_Id=null WHERE Id=:thisId;',
        'GrupoDeSeguranca' => 'DELETE FROM udd_to_gds WHERE UsuarioDoDominio_Id=:thisId AND GrupoDeSeguranca_Id=:tgtId;',
      ),
      'detachWithAll' => 
      array (
        'SessaoDeAcesso' => 'UPDATE UsuarioDoDominio SET SessaoDeAcesso_Id=null WHERE Id=:thisId;',
        'GrupoDeSeguranca' => 'DELETE FROM udd_to_gds WHERE UsuarioDoDominio_Id=:thisId;',
      ),
      'oColumn' => 
      array (
      ),
      'singleFK' => 
      array (
      ),
      'collectionFK' => 
      array (
      ),
    ),
  ),
);