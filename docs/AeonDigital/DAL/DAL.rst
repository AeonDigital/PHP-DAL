.. rst-class:: phpdoctorst

.. role:: php(code)
	:language: php


DAL
===


.. php:namespace:: AeonDigital\DAL

.. php:class:: DAL


	.. rst-class:: phpdoc-description
	
		| Classe que permite o acesso a um banco de dados utilizando o PDO do PHP.
		
	
	:Parent:
		:php:class:`AeonDigital\\BObject`
	
	:Implements:
		:php:interface:`AeonDigital\\Interfaces\\DAL\\iDAL` 
	

Properties
----------

Methods
-------

.. rst-class:: public

	.. php:method:: public getConnection()
	
		.. rst-class:: phpdoc-description
		
			| Retorna o objeto ``dbConnection`` desta instância.
			
		
		
		:Returns: ‹ \\PDO ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getDBType()
	
		.. rst-class:: phpdoc-description
		
			| Retorna o tipo do banco de dados utilizado.
			
		
		
		:Returns: ‹ string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getDBHost()
	
		.. rst-class:: phpdoc-description
		
			| Retorna o host da conexão com o banco de dados.
			
		
		
		:Returns: ‹ string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getDBName()
	
		.. rst-class:: phpdoc-description
		
			| Retorna o nome do banco de dados que esta conexão está apta a acessar.
			
		
		
		:Returns: ‹ string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public replaceConnection( $oConnection)
	
		.. rst-class:: phpdoc-description
		
			| Substitui a conexão desta instância pela do objeto passado.
			
		
		
		:Parameters:
			- ‹ AeonDigital\\Interfaces\\DAL\\iDAL › **$oConnection** |br|
			  Objeto que contêm a conexão que passará a ser usada por esta instância.

		
		:Returns: ‹ void ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public executeInstruction( $strSQL, $parans=null)
	
		.. rst-class:: phpdoc-description
		
			| Prepara e executa um comando SQL.
			
		
		
		:Parameters:
			- ‹ string › **$strSQL** |br|
			  Instrução a ser executada.
			- ‹ ?array › **$parans** |br|
			  Array associativo contendo as chaves e respectivos valores que serão
			  substituídos na instrução SQL.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getDataTable( $strSQL, $parans=null)
	
		.. rst-class:: phpdoc-description
		
			| Executa uma instrução SQL e retorna os dados obtidos.
			
		
		
		:Parameters:
			- ‹ string › **$strSQL** |br|
			  Instrução a ser executada.
			- ‹ ?array › **$parans** |br|
			  Array associativo contendo as chaves e respectivos valores que serão
			  substituídos na instrução SQL.

		
		:Returns: ‹ ?array ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getDataRow( $strSQL, $parans=null)
	
		.. rst-class:: phpdoc-description
		
			| Executa uma instrução SQL e retorna apenas a primeira linha de dados obtidos.
			
		
		
		:Parameters:
			- ‹ string › **$strSQL** |br|
			  Instrução a ser executada.
			- ‹ ?array › **$parans** |br|
			  Array associativo contendo as chaves e respectivos valores que serão
			  substituídos na instrução SQL.

		
		:Returns: ‹ ?array ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getDataColumn( $strSQL, $parans=null, $castTo=&#34;string&#34;)
	
		.. rst-class:: phpdoc-description
		
			| Executa uma instrução SQL e retorna apenas a coluna da primeira linha de dados
			| obtidos. O valor ``null`` será retornado caso a consulta não traga resultados.
			
		
		
		:Parameters:
			- ‹ string › **$strSQL** |br|
			  Instrução a ser executada.
			- ‹ ?array › **$parans** |br|
			  Array associativo contendo as chaves e respectivos valores que serão
			  substituídos na instrução SQL.
			- ‹ string › **$castTo** |br|
			  Indica o tipo que o valor resgatado deve ser retornado.
			  Esperado: ``bool``, ``int``, ``float``, ``real``, ``datetime``, ``string``.

		
		:Returns: ‹ ?mixed ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getCountOf( $strSQL, $parans=null)
	
		.. rst-class:: phpdoc-description
		
			| Efetua uma consulta SQL do tipo ``COUNT`` e retorna seu resultado.
			
			| A consulta passada deve sempre trazer o resultado da contagem em um ``alias`` chamado ``count``.
			| 
			| \`\`\` sql
			|      SELECT COUNT(id) as count FROM TargetTable WHERE column=:column;
			| \`\`\`
			
		
		
		:Parameters:
			- ‹ string › **$strSQL** |br|
			  Instrução a ser executada.
			- ‹ ?array › **$parans** |br|
			  Array associativo contendo as chaves e respectivos valores que serão
			  substituídos na instrução SQL.

		
		:Returns: ‹ int ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public isExecuted()
	
		.. rst-class:: phpdoc-description
		
			| Indica se a última instrução foi corretamente executada.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public countAffectedRows()
	
		.. rst-class:: phpdoc-description
		
			| Retorna a quantidade de linhas afetadas pela última instrução SQL executada ou a
			| quantidade de linhas retornadas pela mesma.
			
		
		
		:Returns: ‹ int ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getLastError()
	
		.. rst-class:: phpdoc-description
		
			| Retorna a mensagem de erro referente a última instrução SQL executada. Não
			| havendo erro, retorna ``null``.
			
		
		
		:Returns: ‹ ?string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getLastPK( $tableName, $pkName)
	
		.. rst-class:: phpdoc-description
		
			| Retorna o último valor definido para o último registro inserido na tabela de dado
			| alvo.
			
			| Tem efeito sobre chaves primárias do tipo ``AUTO INCREMENT``.
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados.
			- ‹ string › **$pkName** |br|
			  Nome da chave primária a ser usada.

		
		:Returns: ‹ ?int ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public countRowsFrom( $tableName, $pkName)
	
		.. rst-class:: phpdoc-description
		
			| Efetua a contagem da totalidade de registros existentes na tabela de dados indicada.
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados.
			- ‹ string › **$pkName** |br|
			  Nome da chave primária da tabela.

		
		:Returns: ‹ int ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public countRowsWith( $tablename, $colName, $colValue)
	
		.. rst-class:: phpdoc-description
		
			| Efetua a contagem de registros existentes na tabela de dados indicada que
			| corresponda com o valor passado para a coluna indicada.
			
		
		
		:Parameters:
			- ‹ string › **$colName** |br|
			  Nome da coluna a ser usada.
			- ‹ mixed › **$colValue** |br|
			  Valor a ser pesquisado.

		
		:Returns: ‹ int ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public hasRowsWith( $tablename, $colName, $colValue)
	
		.. rst-class:: phpdoc-description
		
			| Verifica se existe na tabela de dados indicada um ou mais registros que possua na
			| coluna indicada o valor passado.
			
		
		
		:Parameters:
			- ‹ string › **$colName** |br|
			  Nome da coluna a ser usada.
			- ‹ mixed › **$colValue** |br|
			  Valor a ser pesquisado.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public insertInto( $tableName, $rowData)
	
		.. rst-class:: phpdoc-description
		
			| Efetua uma instrução ``INSERT INTO`` na tabela de dados alvo para cada um dos
			| itens existentes no array associativo passado.
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados.
			- ‹ array › **$rowData** |br|
			  Array associativo mapeando colunas e valores a serem utilizados na
			  intrução SQL.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public updateSet( $tableName, $rowData, $pkName)
	
		.. rst-class:: phpdoc-description
		
			| Efetua uma instrução ``UPDATE SET`` na tabela de dados alvo para cada um dos
			| itens existentes no array associativo passado.
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados.
			- ‹ array › **$rowData** |br|
			  Array associativo mapeando colunas e valores a serem utilizados na
			  intrução SQL.
			- ‹ string › **$pkName** |br|
			  Nome da chave primária a ser usada.
			  Seu respectivo valor deve estar entre aqueles constantes em ``$rowData``.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public insertOrUpdate( $tableName, $rowData, $pkName)
	
		.. rst-class:: phpdoc-description
		
			| Efetua uma instrução ``INSERT INTO`` ou ``UPDATE SET`` conforme a existência ou não
			| da chave primária entre os dados passados para uso na instrução SQL.
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados.
			- ‹ array › **$rowData** |br|
			  Array associativo mapeando colunas e valores a serem utilizados na
			  intrução SQL.
			- ‹ string › **$pkName** |br|
			  Nome da chave primária a ser usada.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public selectFrom( $tableName, $pkName, $pk, $columnNames=null)
	
		.. rst-class:: phpdoc-description
		
			| Seleciona 1 única linha de registro da tabela de dados alvo a partir da chave
			| primária indicada e retorna um array associativo contendo cada uma das colunas
			| de dados indicados.
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados.
			- ‹ string › **$pkName** |br|
			  Nome da chave primária a ser usada.
			- ‹ int › **$pk** |br|
			  Valor da chave primária.
			- ‹ ?array › **$columnNames** |br|
			  Array contendo o nome de cada uma das colunas de dados a serem retornadas.
			  Usando ``null`` todas serão retornadas.

		
		:Returns: ‹ ?array ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public deleteFrom( $tableName, $pkName, $pk)
	
		.. rst-class:: phpdoc-description
		
			| Efetua uma instrução ``DELETE FROM`` para a tabela de dados alvo usando o nome e
			| valor da chave primária definida.
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados.
			- ‹ string › **$pkName** |br|
			  Nome da chave primária a ser usada.
			- ‹ int › **$pk** |br|
			  Valor da chave primária.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public inTransaction()
	
		.. rst-class:: phpdoc-description
		
			| Indica se o modo de transação está aberto.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public beginTransaction()
	
		.. rst-class:: phpdoc-description
		
			| Inicia o modo de transação, dando ao desenvolvedor a responsabilidade de efetuar
			| o commit ou rollback conforme a necessidade.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public commit()
	
		.. rst-class:: phpdoc-description
		
			| Efetiva as transações realizadas desde que o modo de transação foi aberto.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public rollBack()
	
		.. rst-class:: phpdoc-description
		
			| Efetua o rollback das transações feitas desde que o modo de transação foi aberto.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public __construct( $dbType, $dbHost, $dbName, $dbUserName, $dbUserPassword, $dbSSLCA=null, $dbConnectionString=null, $oConnection=null)
	
		.. rst-class:: phpdoc-description
		
			| Inicia uma nova instância de conexão com um banco de dados.
			
		
		
		:Parameters:
			- ‹ string › **$dbType** |br|
			  Tipo do banco de dados.
			  Esperao um dos tipos: ``mysql``, ``mssqlserver``, ``oracle``, ``postgree``.
			- ‹ string › **$dbHost** |br|
			  Host da conexão com o banco de dados.
			- ‹ string › **$dbName** |br|
			  Nome da base de dados à qual a conexão será feita.
			- ‹ string › **$dbUserName** |br|
			  Credencial ``user`` para a efetuar a conexão.
			- ‹ string › **$dbUserPassword** |br|
			  Credencial ``password`` para efetuar a conexão.
			- ‹ ?string › **$dbSSLCA** |br|
			  Caminho para o certificado que deve ser usado no caso de uma
			  conexão usando ``ssl``.
			- ‹ ?string › **$dbConnectionString** |br|
			  String de conexão a ser usada.
			  Se não for definida, usará as regras internas para contruir uma.
			- ‹ ?\\AeonDigital\\DAL\\iConnection › **$oConnection** |br|
			  Instância de um objeto que terá sua conexão compartilhada
			  com a nova instância que está sendo criada.

		
		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso algum valor passado não seja válido.
		
	
	

