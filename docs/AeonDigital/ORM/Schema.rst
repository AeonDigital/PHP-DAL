.. rst-class:: phpdoctorst

.. role:: php(code)
	:language: php


Schema
======


.. php:namespace:: AeonDigital\ORM

.. php:class:: Schema


	.. rst-class:: phpdoc-description
	
		| Classe que cria ou atualiza um schema em um banco de dados.
		
	
	:Implements:
		:php:interface:`AeonDigital\\Interfaces\\ORM\\iSchema` 
	

Properties
----------

Methods
-------

.. rst-class:: public

	.. php:method:: public generateCreateSchemaFiles()
	
		.. rst-class:: phpdoc-description
		
			| A partir das informações contidas na fábrica de tabelas de dados para o projeto em
			| uso, gera um arquivo ``_schema.php`` contendo todas as instruções SQL necessárias
			| para a criação dos modelos no banco de dados alvo.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
		:Throws: ‹ \Exception ›|br|
			  Quando a configuração de uma linkTable não está correta.
		
	
	

.. rst-class:: public

	.. php:method:: public listDataBaseTables()
	
		.. rst-class:: phpdoc-description
		
			| Retorna uma coleção de arrays contendo o nome e a descrição de cada uma das
			| tabelas do atual banco de dados (mesmo aquelas que não estão mapeadas).
			
			| \`\`\` php
			|      // O array retornado é uma coleção de entradas conforme o exemplo abaixo:
			|      $arr = [
			|          string  &#34;tableName&#34;         Nome da tabela.
			|          string  &#34;tableDescription&#34;  Descrição da tabela.
			|          int     &#34;tableRows&#34;         Contagem de registros na tabela.
			|          bool    &#34;tableMapped&#34;       Indica se a tabela está mapeada nos modelos de dados do atual schema.
			|      ];
			| \`\`\`
			
		
		
		:Returns: ‹ ?array ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public executeDropSchema()
	
		.. rst-class:: phpdoc-description
		
			| Remove completamente todo o schema atualmente existente dentro do banco de dados
			| alvo.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public listTableColumns( $tableName)
	
		.. rst-class:: phpdoc-description
		
			| Retorna uma coleção de arrays contendo o nome, tipo e a descrição de cada uma das
			| colunas da tabela indicada.
			
			| \`\`\` php
			|      // O array retornado é uma coleção de entradas conforme o exemplo abaixo:
			|      $arr = [
			|          bool    &#34;columnPrimaryKey&#34;      Indica se a coluna é uma chave primária.
			|          bool    &#34;columnUniqueKey&#34;       Indica se a coluna é do tipo &#34;unique&#34;.
			|          string  &#34;columnName&#34;            Nome da coluna.
			|          string  &#34;columnDescription&#34;     Descrição da coluna.
			|          string  &#34;columnDataType&#34;        Tipo de dados da coluna.
			|          bool    &#34;columnAllowNull&#34;       Indica se a coluna pode assumir NULL como valor.
			|          string  &#34;columnDefaultValue&#34;    Valor padrão para a coluna.
			|      ];
			| \`\`\`
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados alvo.

		
		:Returns: ‹ ?array ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public listSchemaConstraint( $tableName=null)
	
		.. rst-class:: phpdoc-description
		
			| Retorna um array associativo contendo a coleção de ``constraints`` definidas
			| atualmente no banco de dados.
			
			| \`\`\` php
			|      // O array retornado é uma coleção de entradas conforme o exemplo abaixo:
			|      $arr = [
			|          string &#34;tableName&#34;              Nome da tabela de dados na qual a regra está vinculada.
			|          string &#34;columnName&#34;             Nome da coluna de dados alvo da regra.
			|          string &#34;constraintName&#34;         Nome da &#34;constraint&#34;.
			|          string &#34;constraintType&#34;         Tipo de regra. [&#34;PRIMARY KEY&#34;, &#34;FOREIGN KEY&#34;, &#34;UNIQUE&#34;]
			|          int    &#34;constraintCardinality&#34;  Cardinalidade da aplicação da regra.
			|      ];
			| \`\`\`
			
		
		
		:Parameters:
			- ‹ ?string › **$tableName** |br|
			  Se for definido, deverá retornar apenas os registros relacionados
			  com a tabela alvo.

		
		:Returns: ‹ ?array ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public executeCreateSchema( $dropSchema=false)
	
		.. rst-class:: phpdoc-description
		
			| Executa o script de criação do schema gerado por último pela função
			| ``generateCreateSchemaFiles``.
			
		
		
		:Parameters:
			- ‹ bool › **$dropSchema** |br|
			  Quando ``true`` irá excluir totalmente todas as tabelas de dados
			  existentes no banco de dados alvo e então recriar o schema.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public __construct( $factory)
	
		.. rst-class:: phpdoc-description
		
			| Inicia uma instância de um Schema para lidar com os modelos de dados definidos
			| para o objeto ``iDataTableFactory`` passado.
			
		
		
		:Parameters:
			- ‹ AeonDigital\\Interfaces\\ORM\\iDataTableFactory › **$factory** |br|
			  Instância de uma fábrica de objetos ``iTable`` para o projeto que
			  está sendo usado.

		
		:Throws: ‹ \Exception ›|br|
			  Caso não seja possível criar algum dos diretórios do projeto.
		
	
	

