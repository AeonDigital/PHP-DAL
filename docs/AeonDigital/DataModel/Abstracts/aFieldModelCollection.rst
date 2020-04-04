.. rst-class:: phpdoctorst

.. role:: php(code)
	:language: php


aFieldModelCollection
=====================


.. php:namespace:: AeonDigital\DataModel\Abstracts

.. rst-class::  abstract

.. php:class:: aFieldModelCollection


	.. rst-class:: phpdoc-description
	
		| Classe abstrata que extende ``aFieldModel`` para implementar ``iFieldCollection`` dando
		| a ela capacidade de lidar com coleções de modelos de dados.
		
	
	:Parent:
		:php:class:`AeonDigital\\DataModel\\Abstracts\\aFieldModel`
	
	:Implements:
		:php:interface:`AeonDigital\\Interfaces\\DataModel\\iFieldCollection` 
	
	:Used traits:
		:php:trait:`AeonDigital\DataModel\Traits\FieldCollectionCommomMethods` 
	

Methods
-------

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
			|          // array            Usado quando o campo é uma coleção de instâncias de modelos de dados.
			|          //                  Deve indicar quais chaves/campos devem ser utilizados para comparar
			|          //                  a coleção de objetos e determinar quais deles são iguais.
			|          //                  Por padrão, TODOS os campos serão utilizados para efetuar a comparação.
			|          &#34;distinctKeys&#34; => ,
			| 
			|          // string           Regras para validação da contagem de valores que devem/podem estar presentes
			|          //                  em uma coleção. (opcional)
			|          //                  Usado apenas se o campo é mesmo uma coleção.
			|          &#34;acceptedCount&#34; => ,
			| 
			|          // mixed            Valor que inicia com o campo.
			|          &#34;value&#34; => ,
			|      ];
			| \`\`\`
			
		
		
		:Parameters:
			- ‹ array › **$config** |br|
			  Array associativo com as configurações para este campo.
			- ‹ AeonDigital\\Interfaces\\DataModel\\iModelFactory › **$factory** |br|
			  Instância de uma fábrica de modelos para ser usada internamente caso a
			  nova instância represente um campo que utiliza modelos de dados.

		
		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso algum valor passado não seja válido.
		
	
	

