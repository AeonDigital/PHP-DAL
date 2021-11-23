.. rst-class:: phpdoctorst

.. role:: php(code)
	:language: php


DataColumnFK
============


.. php:namespace:: AeonDigital\ORM

.. php:class:: DataColumnFK


	.. rst-class:: phpdoc-description
	
		| Representação de uma coluna de dados que armazena uma referência para um outro
		| registro de uma outra tabela de dados.
		
	
	:Parent:
		:php:class:`AeonDigital\\DataModel\\Abstracts\\aFieldModel`
	
	:Implements:
		:php:interface:`AeonDigital\\Interfaces\\ORM\\iColumnFK` 
	
	:Used traits:
		:php:trait:`AeonDigital\ORM\Traits\ColumnProperties` :php:trait:`AeonDigital\ORM\Traits\DataColumnCommomMethods` :php:trait:`AeonDigital\ORM\Traits\ColumnFKProperties` 
	

Methods
-------

.. rst-class:: public

	.. php:method:: public __construct( $config, $factory)
	
		.. rst-class:: phpdoc-description
		
			| Inicia um novo campo de dados.
			
		
		
		:Parameters:
			- ‹ array › **$config** |br|
			  Array associativo com as configurações para este campo.
			  
			  \`\`\` php
			       $arr = [
			           string          &#34;name&#34;
			           Nome do campo.
			  
			           string          &#34;description&#34;
			           Descrição do campo. (opcional)
			  
			           bool            &#34;allowNull&#34;
			           Indica se &#34;null&#34; é um valor aceito para este campo. (opcional)
			  
			           bool            &#34;readOnly&#34;
			           Indica se o campo é apenas de leitura.
			           Neste caso ele poderá ser definido apenas 1 vez e após
			           isto seu valor não poderá ser alterado. (opcional)
			  
			           string          &#34;fkTableName&#34;
			           Nome da tabela de dados a qual esta coluna se referencia.
			  
			           string          &#34;fkDescription&#34;
			           Descrição especial desta coluna enquanto FK. (opcional)
			  
			           bool            &#34;fkAllowNull&#34;
			           Indica se os objetos filhos devem ser obrigados a terem uma correlação
			           obrigatória com o objeto pai. (opcional)
			  
			           bool            &#34;fkUnique&#34;
			           Indica que cada objeto pai pode se relacionar com apenas 1 objeto filho e vice-versa.
			  
			           string          &#34;fkOnUpdate&#34;
			           Regra para ser aplicada nesta FK quando o registro pai for alterado. (opcional)
			           São esperados um dos seguintes valores:
			           [ RESTRICT | NO ACTION | CASCADE | SET NULL | SET DEFAULT ]
			  
			           string          &#34;fkOnDelete&#34;
			           Regra para ser aplicada nesta FK quando o registro pai for excluído. (opcional)
			           São esperados um dos seguintes valores:
			           [ RESTRICT | NO ACTION | CASCADE | SET NULL | SET DEFAULT ]
			  
			           mixed           &#34;value&#34;
			           Valor que inicia com o campo.
			       ];
			  \`\`\`
			- ‹ AeonDigital\\Interfaces\\ORM\\iDataTableFactory › **$factory** |br|
			  Instância de uma fábrica de tabelas de dados.

		
		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso algum valor passado não seja válido.
		
	
	

