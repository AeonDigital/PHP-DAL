.. rst-class:: phpdoctorst

.. role:: php(code)
	:language: php


DataTableFactory
================


.. php:namespace:: AeonDigital\ORM

.. php:class:: DataTableFactory


	.. rst-class:: phpdoc-description
	
		| Fábrica de tabelas de dados para um dado projeto.
		
	
	:Implements:
		:php:interface:`AeonDigital\\Interfaces\\ORM\\iDataTableFactory` 
	

Properties
----------

Methods
-------

.. rst-class:: public

	.. php:method:: public getDAL()
	
		.. rst-class:: phpdoc-description
		
			| Retorna o objeto ``DAL`` que está sendo usado por esta instância.
			
		
		
		:Returns: ‹ \\AeonDigital\\Interfaces\\DAL\\iDAL ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getProjectName()
	
		.. rst-class:: phpdoc-description
		
			| Nome do projeto.
			
			| Geralmente é o mesmo nome do banco de dados definido na instância ``iDAL`` usada.
			
		
		
		:Returns: ‹ string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getProjectDirectory()
	
		.. rst-class:: phpdoc-description
		
			| Retorna o caminho completo até o diretório onde estão os arquivos que descrevem os
			| modelos de dados utilizado por este projeto.
			
			| Dentro do mesmo diretório deve haver um outro chamado ``enum`` contendo os
			| enumeradores usados pelo projeto.
			
		
		
		:Returns: ‹ string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public recreateProjectDataFile()
	
		.. rst-class:: phpdoc-description
		
			| Cria um arquivo ``_projectData.php`` no diretório principal do projeto.
			
			| Este arquivo armazenará um array associativo contendo o nome das tabelas de dados
			| usadas no projeto e seus respectivos arquivos de configuração.
			| 
			| Caso o arquivo já exista, será substituído por um novo.
			
		
		
		:Returns: ‹ void ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getDataTableList()
	
		.. rst-class:: phpdoc-description
		
			| Retorna um array com a lista de todas as tabelas de dados existêntes neste projeto.
			
		
		
		:Returns: ‹ array ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public hasDataModel( $idName)
	
		.. rst-class:: phpdoc-description
		
			| Identifica se esta fábrica pode fornecer um objeto compatível com o nome do Identificador
			| passado.
			
		
		
		:Parameters:
			- ‹ string › **$idName** |br|
			  Identificador único do modelo de dados dentro do escopo definido.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public hasDataTable( $tableName)
	
		.. rst-class:: phpdoc-description
		
			| Identifica se no atual projeto existe uma tabela de dados com o nome passado.
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public createDataModel( $idName, $initialValues=null)
	
		.. rst-class:: phpdoc-description
		
			| Retorna um objeto ``iModel`` com as configurações equivalentes ao identificador único
			| do mesmo.
			
		
		
		:Parameters:
			- ‹ string › **$idName** |br|
			  Identificador único do modelo de dados dentro do escopo definido.
			- ‹ mixed › **$initialValues** |br|
			  Coleção de valores a serem setados para a nova instância que será retornada.

		
		:Returns: ‹ \\AeonDigital\\Interfaces\\DataModel\\iModel ›|br|
			  
		
		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso o nome da tabela seja inexistente.
		
	
	

.. rst-class:: public

	.. php:method:: public createDataTable( $tableName, $initialValues=null)
	
		.. rst-class:: phpdoc-description
		
			| Retorna uma tabela de dados correspondente ao nome informado no argumento ``$tableName``.
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados.
			- ‹ mixed › **$initialValues** |br|
			  Coleção de valores a serem setados para a nova instância que será retornada.

		
		:Returns: ‹ \\AeonDigital\\Interfaces\\ORM\\iTable ›|br|
			  
		
		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso o nome da tabela seja inexistente.
		
	
	

.. rst-class:: public

	.. php:method:: public __construct( $projectDirectory, $DAL)
	
		.. rst-class:: phpdoc-description
		
			| Inicia uma fábrica de DataTables para o projeto.
			
		
		
		:Parameters:
			- ‹ string › **$projectDirectory** |br|
			  Caminho completo até o local onde devem ser definidos os modelos de dados das
			  tabelas do projeto.
			- ‹ AeonDigital\\Interfaces\\DAL\\iDAL › **$DAL** |br|
			  Conexão que permite a manipulação do banco de dados alvo.

		
		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso algum valor passado não seja válido.
		
		:Throws: ‹ \Exception ›|br|
			  Caso não existam modelos de dados a serem carregados.
		
		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso algum valor passado não seja válido.
		
		:Throws: ‹ \Exception ›|br|
			  Caso não existam modelos de dados a serem carregados.
		
	
	

