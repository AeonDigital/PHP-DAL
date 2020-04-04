.. rst-class:: phpdoctorst

.. role:: php(code)
	:language: php


DataTable
=========


.. php:namespace:: AeonDigital\ORM

.. php:class:: DataTable


	.. rst-class:: phpdoc-description
	
		| Classe que representa uma tabela de dados.
		
	
	:Parent:
		:php:class:`AeonDigital\\DataModel\\Abstracts\\aModel`
	
	:Implements:
		:php:interface:`AeonDigital\\Interfaces\\ORM\\iTable` 
	

Properties
----------

Methods
-------

.. rst-class:: public

	.. php:method:: public setDAL( $DAL)
	
		.. rst-class:: phpdoc-description
		
			| Define o objeto ``iDAL`` a ser usado para executar as instruções ``CRUD`` desta
			| tabela.
			
			| Deve ser definido apenas 1 vez.
			
		
		
		:Parameters:
			- ‹ AeonDigital\\Interfaces\\DAL\\iDAL › **$DAL** |br|
			  Objeto DAL a ser usado.

		
		:Returns: ‹ void ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getAlias()
	
		.. rst-class:: phpdoc-description
		
			| Nome abreviado da tabela de dados.
			
			| Usado para evitar ambiguidades entre as colunas desta e de outras tabelas de
			| dados.
			
		
		
		:Returns: ‹ string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getExecuteAfterCreateTable()
	
		.. rst-class:: phpdoc-description
		
			| Retorna um array contendo as instruções que devem ser executadas após a tabela de
			| dados ser criada.
			
		
		
		:Returns: ‹ ?array ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getLastDALError()
	
		.. rst-class:: phpdoc-description
		
			| Retorna a mensagem de erro referente a última instrução SQL executada internamente
			| pela conexão com o banco de dados.
			
			| Não havendo erro, retorna ``null``.
			
		
		
		:Returns: ‹ ?string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public countRows()
	
		.. rst-class:: phpdoc-description
		
			| Retorna o total de registros existentes nesta tabela de dados.
			
		
		
		:Returns: ‹ int ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public hasId( $Id)
	
		.. rst-class:: phpdoc-description
		
			| Identifica se existe na tabela de dados um registro com o Id indicado.
			
		
		
		:Parameters:
			- ‹ int › **$Id** |br|
			  Id do objeto.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public save( $parentTableName=null, $parentId=null)
	
		.. rst-class:: phpdoc-description
		
			| Insere ou atualiza os dados da instância atual no banco de dados.
			
		
		
		:Parameters:
			- ‹ ?string › **$parentTableName** |br|
			  Se definido, deve ser o nome do modelo de dados ao qual o objeto atual
			  deve ser associado.
			- ‹ ?int › **$parentId** |br|
			  Id do objeto pai ao qual este registro deve estar associado.

		
		:Returns: ‹ bool ›|br|
			  Retornará ``true`` caso esta ação tenha sido bem sucedida.
		
	
	

.. rst-class:: public

	.. php:method:: public insert( $parentTableName=null, $parentId=null)
	
		.. rst-class:: phpdoc-description
		
			| Insere os dados desta instância em um novo registro no banco de dados.
			
			| Se este objeto já possui um Id definido esta ação irá falhar.
			
		
		
		:Parameters:
			- ‹ ?string › **$parentTableName** |br|
			  Se definido, deve ser o nome do modelo de dados ao qual o objeto atual
			  deve ser associado.
			- ‹ ?int › **$parentId** |br|
			  Id do objeto pai ao qual este registro deve estar associado.

		
		:Returns: ‹ bool ›|br|
			  Retornará ``true`` caso esta ação tenha sido bem sucedida.
		
	
	

.. rst-class:: public

	.. php:method:: public update( $parentTableName=null, $parentId=null)
	
		.. rst-class:: phpdoc-description
		
			| Atualiza os dados desta instância em um novo registro no banco de dados.
			
			| Se este objeto não possui um Id definido esta ação irá falhar.
			
		
		
		:Parameters:
			- ‹ ?string › **$parentTableName** |br|
			  Se definido, deve ser o nome do modelo de dados ao qual o objeto atual
			  deve ser associado.
			- ‹ ?int › **$parentId** |br|
			  Id do objeto pai ao qual este registro deve estar associado.

		
		:Returns: ‹ bool ›|br|
			  Retornará ``true`` caso esta ação tenha sido bem sucedida.
		
	
	

.. rst-class:: public

	.. php:method:: public select( $Id, $loadChilds=false)
	
		.. rst-class:: phpdoc-description
		
			| Carrega esta instância com os dados do registro de Id informado.
			
		
		
		:Parameters:
			- ‹ int › **$Id** |br|
			  Id do registro que será carregado.
			- ‹ bool › **$loadChilds** |br|
			  Quando ``true`` irá carregar todos os objetos que são filhos diretos
			  deste.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public selectParentIdOf( $tableName)
	
		.. rst-class:: phpdoc-description
		
			| Retornará o Id do objeto PAI da instância atual na tabela de dados indicada no
			| parametro ``$tableName``.
			
			| Apenas funcionará para os objetos FILHOS em relações ``1-1`` e ``1-N``.
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados do objeto pai.

		
		:Returns: ‹ ?int ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public delete()
	
		.. rst-class:: phpdoc-description
		
			| Remove o objeto atual do banco de dados.
			
			| Irá limpar totalmente os objetos filhos substituindo-os por instâncias vazias, ou
			| por coleções vazias.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public attatchWith( $tableName, $tgtId)
	
		.. rst-class:: phpdoc-description
		
			| Permite definir o vínculo da instância atualmente carregada a um de seus possíveis
			| relacionamentos indicados nos modelos de dados.
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados com a qual esta instância passará a ter um
			  vínculo referencial.
			- ‹ int › **$tgtId** |br|
			  Id do registro da tabela de dados alvo onde este vinculo será firmado.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public detachWith( $tableName, $tgtId=null)
	
		.. rst-class:: phpdoc-description
		
			| Remove o vínculo existente entre este registro e um outro da tabela de dados.
			
			| O funcionamento deste método depende da *posição* no relacionamento em que a
			| instrução está sendo executada e varia conforme a presença ou não do parametro
			| ``$tgtId``.
			| 
			| - Em relações 1-1:
			|   O funcionamento é igual independente da posição em que a instrução está sendo
			|   executada.
			|   Não é preciso definir o parametro ``$tgtId``.
			|   A chave extrangeira será anulada.
			| 
			| - Em relações 1-N:
			|   - A partir da instância PAI:
			|     Definindo ``$tgtId``:
			|     Apenas o objeto FILHO de ``$tgtId`` especificado terá seu vínculo desfeito.
			|     Omitindo ``$tgtId``:
			|     TODOS os objetos FILHOS da instância atual perderão seu vínculo.
			| 
			|   - A partir da instância FILHA:
			|     Não é preciso definir o parametro ``$tgtId``.
			|     A chave extrangeira será anulada.
			| 
			| - Em relações N-N
			|   Independente do lado:
			|   Definindo ``$tgtId``:
			|   Irá remover o vínculo existente entre ambos registros
			|   Omitindo ``$tgtId``:
			|   TODOS os vínculos entre a instância atual e TODOS os demais serão desfeitos.
			
		
		
		:Parameters:
			- ‹ string › **$tableName** |br|
			  Nome da tabela de dados com a qual esta instância irá romper um vínculo
			  existente.
			- ‹ ?int › **$tgtId** |br|
			  Id do registro da tabela de dados.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public __construct( $config)
	
		.. rst-class:: phpdoc-description
		
			| Inicia uma nova tabela de dados.
			
		
		
		:Parameters:
			- ‹ array › **$config** |br|
			  Array associativo com as configurações para esta tabela de dados.
			  
			  \`\`\` php
			       $arr = [
			           string          &#34;tableName&#34;
			           Nome da tabela de dados.
			  
			           string          &#34;alias&#34;
			           Nome abreviado da tabela de dados.
			  
			           string          &#34;description&#34;
			           Descrição da tabela de dados. (opcional)
			  
			           array           &#34;executeAfterCreateTable&#34;
			           Coleção de instruções a serem executadas após a tabela de dados
			           ser definida.
			  
			           iColumn[]       &#34;columns&#34;
			           Array contendo as instâncias das colunas de dados que devem
			           compor este tabela de dados.
			  
			           array           &#34;ormInstructions&#34;
			           Coleção de instruções SQL usadas por esta instância para
			           carregar seus próprios dados e de seus objetos filhos.
			       ];
			  \`\`\`

		
		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso algum valor passado não seja válido.
		
	
	

