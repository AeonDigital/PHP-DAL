.. rst-class:: phpdoctorst

.. role:: php(code)
	:language: php


ColumnProperties
================


.. php:namespace:: AeonDigital\ORM\Traits

.. php:trait:: ColumnProperties


	.. rst-class:: phpdoc-description
	
		| Métodos e propriedades comuns para uso de colunas de dados que implementem a
		| interface ``iColumn``.
		
	

Properties
----------

Methods
-------

.. rst-class:: public

	.. php:method:: public isUnique()
	
		.. rst-class:: phpdoc-description
		
			| Indica se o valor para esta coluna pode ser repetido entre os demais registros
			| que compões a coleção da tabela de dados.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public isAutoIncrement()
	
		.. rst-class:: phpdoc-description
		
			| Indica quando o o valor desta coluna é do tipo *auto-incremento*.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public isPrimaryKey()
	
		.. rst-class:: phpdoc-description
		
			| Indica se esta coluna é a chave primária da tabela de dados.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public isForeignKey()
	
		.. rst-class:: phpdoc-description
		
			| Indica se esta coluna é uma chave extrangeira.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public isIndex()
	
		.. rst-class:: phpdoc-description
		
			| Indica se esta coluna está ou não indexada.
			
			| Por padrão, toda ``primaryKey`` e ``foreignKey`` é automaticamente indexada.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

