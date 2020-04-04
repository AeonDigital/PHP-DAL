.. rst-class:: phpdoctorst

.. role:: php(code)
	:language: php


aFieldModel
===========


.. php:namespace:: AeonDigital\DataModel\Abstracts

.. rst-class::  abstract

.. php:class:: aFieldModel


	.. rst-class:: phpdoc-description
	
		| Classe abstrata que extende ``aField`` para implementar ``iFieldModel`` dando a ela
		| capacidade de possuir como valor instâncias de modelos de dados (``iModel``).
		
	
	:Parent:
		:php:class:`AeonDigital\\DataModel\\Abstracts\\aField`
	
	:Implements:
		:php:interface:`AeonDigital\\Interfaces\\DataModel\\iFieldModel` 
	

Methods
-------

.. rst-class:: public

	.. php:method:: public isInitial()
	
		.. rst-class:: phpdoc-description
		
			| Verifica se algum valor já foi definido para algum campo deste modelo de dados.
			
			| Internamente executa o método ``iModel->isInitial()``.
			| 
			| A partir do acionamento de qualquer método de alteração de campos e obter sucesso
			| ao defini-lo, o resultado deste método será sempre ``false``.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getModel()
	
		.. rst-class:: phpdoc-description
		
			| Retorna uma instância do modelo de dados usada por este campo.
			
		
		
		:Returns: ‹ \\AeonDigital\\Interfaces\\DataModel\\iModel ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getModelName()
	
		.. rst-class:: phpdoc-description
		
			| Retorna o nome do modelo de dados usado.
			
		
		
		:Returns: ‹ string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public getInstanceValue()
	
		.. rst-class:: phpdoc-description
		
			| Retornará a instância do valor que está definida para o campo.
			
			| Em campos *collection* será retornado o ``array`` contendo as instâncias que
			| compõe a coleção atual.
			
		
		
		:Returns: ‹ \\AeonDigital\\Interfaces\\DataModel\\iModel | \\AeonDigital\\Interfaces\\DataModel\\iModel[] ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public __construct( $config, $factory)
	
		.. rst-class:: phpdoc-description
		
			| Inicia um novo campo de dados.
			
			| O ``array`` de configuração deve ter a seguinte definição:
			| 
			| \`\`\` php
			|      $arr = [
			|          // string           Nome do campo.
			|          &#34;name&#34; => ,
			| 
			|          // string           Descrição do campo. (opcional)
			|          &#34;description&#34; => ,
			| 
			|          // string           Nome do modelo de dados a ser usado por este campo. Uma vez definido,
			|          //                  irá anular qualquer definição de propriedades incompatíveis com esta e, a
			|          //                  propriedade &#34;type&#34; será definida como &#34;reference&#34;. (opcional)
			|          &#34;modelName&#34; => ,
			| 
			|          // bool             Indica se &#34;null&#34; é um valor aceito para este campo. (opcional)
			|          &#34;allowNull&#34; => ,
			| 
			|          // bool             Indica se o campo é apenas de leitura.
			|          //                  Neste caso ele poderá ser definido apenas 1 vez e após
			|          //                  isto seu valor não poderá ser alterado. (opcional)
			|          &#34;readOnly&#34; => ,
			| 
			|          // mixed            Valor que inicia com o campo.
			|          &#34;value&#34; => ,
			|      ];
			| \`\`\`
			
		
		
		:Parameters:
			- ‹ array › **$config** |br|
			  ``array`` associativo com as configurações para este campo.
			- ‹ AeonDigital\\Interfaces\\DataModel\\iModelFactory › **$factory** |br|
			  Instância de uma fábrica de modelos para ser usada internamente caso a
			  nova instância represente um campo que utiliza modelos de dados.

		
		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso algum valor passado não seja válido.
		
	
	

