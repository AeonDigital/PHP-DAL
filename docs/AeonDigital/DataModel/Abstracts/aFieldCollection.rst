.. rst-class:: phpdoctorst

.. role:: php(code)
	:language: php


aFieldCollection
================


.. php:namespace:: AeonDigital\DataModel\Abstracts

.. rst-class::  abstract

.. php:class:: aFieldCollection


	.. rst-class:: phpdoc-description
	
		| Classe abstrata que extende ``aField`` para implementar ``iFieldCollection`` dando a
		| ela capacidade de lidar com coleções de dados.
		
	
	:Parent:
		:php:class:`AeonDigital\\DataModel\\Abstracts\\aField`
	
	:Implements:
		:php:interface:`AeonDigital\\Interfaces\\DataModel\\iFieldCollection` 
	
	:Used traits:
		:php:trait:`AeonDigital\DataModel\Traits\FieldCollectionCommomMethods` 
	

Methods
-------

.. rst-class:: public

	.. php:method:: public __construct( $config)
	
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
			|          // string           Nome completo de uma classe que implemente a interface &#34;iSimpleType&#34;.
			|          //                  OU &#34;ref&#34; para identificar que este campo referencia-se a um outro modelo
			|          //                  de dados.
			|          &#34;type&#34; => ,
			| 
			|          // string           Nome completo de uma classe que implemente a interface &#34;iFormat&#34;. (opcional)
			|          &#34;inputFormat&#34; => ,
			| 
			|          // int              Tamanho máximo do campo em caracteres. (opcional)
			|          //                  Se não for definido explicitamente poderá herdar das informações
			|          //                  indicadas em &#34;inputFormat&#34;.
			|          &#34;length&#34; => ,
			| 
			|          // mixed            Valor mínimo aceito para este campo. (opcional)
			|          //                  Use apenas para casos de campos numéricos ou data/hora.
			|          &#34;min&#34; => ,
			| 
			|          // mixed            Valor máximo aceito para este campo. (opcional)
			|          //                  Use apenas para casos de campos numéricos ou data/hora.
			|          &#34;max&#34; => ,
			| 
			|          // bool             Indica se a coleção permite receber valores repetidos. (opcional)
			|          //                  Usado apenas se o campo é mesmo uma coleção.
			|          &#34;distinct&#34; => ,
			| 
			|          // string           Regras para validação da contagem de valores que devem/podem estar presentes
			|          //                  em uma coleção. (opcional)
			|          //                  Usado apenas se o campo é mesmo uma coleção.
			|          &#34;acceptedCount&#34; => ,
			| 
			|          // mixed            Valor padrão para este campo. (opcional)
			|          &#34;default&#34; => ,
			| 
			|          // array|string     Coleção de valores válidos para este campo. (opcional)
			|          //                  Se for definido uma string, deve ser o caminho completo até um arquivo php
			|          //                  que contêm o array a ser utilizado como enumerador.
			|          &#34;enumerator&#34; => ,
			| 
			|          // mixed            Valor que inicia com o campo.
			|          &#34;value&#34; => ,
			|      ];
			| \`\`\`
			
		
		
		:Parameters:
			- ‹ array › **$config** |br|
			  Array associativo com as configurações para este campo.

		
		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso algum valor passado não seja válido.
		
	
	

