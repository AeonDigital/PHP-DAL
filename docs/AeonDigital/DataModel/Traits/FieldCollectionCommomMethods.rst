.. rst-class:: phpdoctorst

.. role:: php(code)
	:language: php


FieldCollectionCommomMethods
============================


.. php:namespace:: AeonDigital\DataModel\Traits

.. php:trait:: FieldCollectionCommomMethods


	.. rst-class:: phpdoc-description
	
		| Métodos e propriedades comuns para uso de classes que implementam ``iFieldCollection``.
		
	

Properties
----------

Methods
-------

.. rst-class:: public

	.. php:method:: public collectionGetState()
	
		.. rst-class:: phpdoc-description
		
			| Retorna o código de estado de uma coleção de dados.
			
		
		
		:Returns: ‹ string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public collectionGetLastValidateState()
	
		.. rst-class:: phpdoc-description
		
			| Retornará ``valid`` caso a última validação de uma coleção tenha ocorrido sem falhas.
			
			| Caso a validação tenha falhado, retornará o código que identifica a natureza do erro.
			
		
		
		:Returns: ‹ string ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public collectionIsDistinct()
	
		.. rst-class:: phpdoc-description
		
			| Indica se esta coleção exige que cada um de seus valores seja único.
			
		
		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public collectionGetDistinctKeys()
	
		.. rst-class:: phpdoc-description
		
			| Retorna a coleção de nomes de campos (chaves) que permitem avaliar quando uma coleção
			| de modelos de dados possui objetos iguais.
			
			| Usado apenas para casos de coleções de modelos de dados ``iModel``.
			| 
			| Se nenhuma coleção for definida para ``distinctKeys`` então deverá usar TODOS os
			| campos do modelo de dados para efetuar a comparação.
			
		
		
		:Returns: ‹ ?array ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public collectionAddValue( $v)
	
		.. rst-class:: phpdoc-description
		
			| Adiciona um novo valor para esta coleção.
			
			| Para a aceitação do valor serão seguidas as mesmas regras especificadas para campos
			| simples e *reference*.
			
		
		
		:Parameters:
			- ‹ mixed › **$v** |br|
			  Valor a ser adicionado na coleção.

		
		:Returns: ‹ bool ›|br|
			  Retornará ``true`` se o valor tornou o campo válido ou ``false`` caso
			  agora ele esteja inválido. Também retornará ``false`` caso o valor seja
			  totalmente incompatível com o campo.
		
	
	

.. rst-class:: public

	.. php:method:: public collectionGetIndexOfValue( $v)
	
		.. rst-class:: phpdoc-description
		
			| Procura pelo valor indicado na coleção atualmente armazenada e retorna o índice do mesmo.
			
			| Valores que não estão aptos a serem armazenados neste campo irão sempre retornar ``null``.
			| 
			| Havendo mais de 1 valor igual na coleção, retornará o índice da primeira ocorrência
			| encontrada.
			
		
		
		:Parameters:
			- ‹ mixed › **$v** |br|
			  Valor que será verificado.

		
		:Returns: ‹ ?int ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public collectionCountOccurrenciesOfValue( $v)
	
		.. rst-class:: phpdoc-description
		
			| Retorna a contagem de ocorrências do valor passado na coleção atualmente armazenada.
			
		
		
		:Parameters:
			- ‹ mixed › **$v** |br|
			  Valor que será verificado.

		
		:Returns: ‹ int ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public collectionHasValue( $v)
	
		.. rst-class:: phpdoc-description
		
			| Verifica se o valor informado existe na coleção de valores atuais deste campo.
			
		
		
		:Parameters:
			- ‹ mixed › **$v** |br|
			  Valor que será verificado.

		
		:Returns: ‹ bool ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public collectionCount()
	
		.. rst-class:: phpdoc-description
		
			| Retorna a quantidade de valores que estão atualmente definidos na coleção do campo.
			
		
		
		:Returns: ‹ int ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public collectionUnsetValue( $v, $all=false)
	
		.. rst-class:: phpdoc-description
		
			| Removerá da coleção de valores a primeira ocorrência do valor informado.
			
		
		
		:Parameters:
			- ‹ mixed › **$v** |br|
			  Valor que será removido.
			- ‹ bool › **$all** |br|
			  Quando ``true`` irá remover TODAS as ocorrências do valor indicado.

		
		:Returns: ‹ void ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public collectionUnsetIndex( $i)
	
		.. rst-class:: phpdoc-description
		
			| Removerá da coleção de valores o item na posição indicada.
			
		
		
		:Parameters:
			- ‹ int › **$i** |br|
			  Índice que será removido.

		
		:Returns: ‹ void ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public collectionGetAcceptedCount()
	
		.. rst-class:: phpdoc-description
		
			| Resgata as regras de aceitação para a contagem de itens em uma coleção de dados.
			
			| O retorno deve ser um ``array`` associativo seguindo as seguintes orientações:
			| 
			| \`\`\` php
			|      $arr = [
			|          // int      Coleção de valores exatos que podem ser encontrados na contagem dos itens em uma coleção.
			|          &#34;exactValues&#34; => 0,
			| 
			|          // int[]    Coleção que indica os múltiplos que a coleção pode possuir.
			|          &#34;multiples&#34; => [],
			| 
			|          // int      Número mínimo de itens que a coleção deve ter.
			|          &#34;min&#34; => 0,
			| 
			|          // int      Número máximo de itens que a coleção deve ter.
			|          &#34;max&#34; => 0
			|      ];
			| \`\`\`
			
		
		
		:Returns: ‹ ?array ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public collectionGetMin()
	
		.. rst-class:: phpdoc-description
		
			| Retornará o número mínimo de itens que esta coleção pode possuir para ser considerada
			| válida.
			
		
		
		:Returns: ‹ ?int ›|br|
			  
		
	
	

.. rst-class:: public

	.. php:method:: public collectionGetMax()
	
		.. rst-class:: phpdoc-description
		
			| Retornará o número máximo de itens que esta coleção pode possuir para ser considerada
			| válida.
			
		
		
		:Returns: ‹ ?int ›|br|
			  
		
	
	

