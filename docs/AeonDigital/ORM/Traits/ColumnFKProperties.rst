.. rst-class:: phpdoctorst

.. role:: php(code)
	:language: php


ColumnFKProperties
==================


.. php:namespace:: AeonDigital\ORM\Traits

.. php:trait:: ColumnFKProperties


	.. rst-class:: phpdoc-description
	
		| Métodos e propriedades comuns para uso de colunas de dados que representam chaves
		| extrangeiras.
		
	

Properties
----------

Methods
-------

.. rst-class:: public

	.. php:method:: public getFKDescription()
	
		.. rst-class:: phpdoc-description
		
			| Retorna a descrição para ser usada na documentação SQL de uma chave extrangeira.
			
		
		
		:Returns: ‹ ?string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public isFKAllowNull()
	
		.. rst-class:: phpdoc-description
		
			| Indica se os objetos filhos (que recebem a FK) aceita serem orfãos, ou seja, se
			| podem existir sem vínculo com com o objeto pai.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public isFKLinkTable()
	
		.. rst-class:: phpdoc-description
		
			| Indica se o vínculo entre as 2 tabelas de dados se dá por meio de uma ``linkTable``.
			
			| Quando ``true``, designa que a relação é do tipo ``N-N``.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getFKOnUpdate()
	
		.. rst-class:: phpdoc-description
		
			| Retorna a regra definida para o uso da definição ``ON UPDATE``.
			
		
		
		:Returns: ‹ ?string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getFKOnDelete()
	
		.. rst-class:: phpdoc-description
		
			| Retorna a regra definida para o uso da definição ``ON DELETE``.
			
		
		
		:Returns: ‹ ?string ›|br|
			  
		
	
	

