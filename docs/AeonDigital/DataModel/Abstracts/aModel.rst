.. rst-class:: phpdoctorst

.. role:: php(code)

	:language: php


aModel
======


.. php:namespace:: AeonDigital\DataModel\Abstracts

.. rst-class::  abstract

.. php:class:: aModel


	.. rst-class:: phpdoc-description

		| Classe abstrata que implementa ``iModel``.


	:Implements:
		:php:interface:`AeonDigital\\Interfaces\\DataModel\\iModel`


Properties
----------

Methods
-------

.. rst-class:: public

	.. php:method:: public getName()

		.. rst-class:: phpdoc-description

			| Retorna o nome do modelo de dados.



		:Returns: ‹ string ›|br|





.. rst-class:: public

	.. php:method:: public getDescription()

		.. rst-class:: phpdoc-description

			| Retorna a descrição de uso/funcionalidade do modelo de dados.



		:Returns: ‹ string ›|br|





.. rst-class:: public

	.. php:method:: public hasField( $f)

		.. rst-class:: phpdoc-description

			| Identifica se o campo com o nome indicado existe neste modelo de dados.



		:Parameters:
			- ‹ string › **$f** |br|
			  Nome do campo que será verificado.


		:Returns: ‹ bool ›|br|





.. rst-class:: public

	.. php:method:: public countFields()

		.. rst-class:: phpdoc-description

			| Retorna a contagem total dos campos existentes para este modelo de dados.



		:Returns: ‹ int ›|br|





.. rst-class:: public

	.. php:method:: public getFieldNames()

		.. rst-class:: phpdoc-description

			| Retorna um ``array`` contendo o nome de cada um dos campos existentes neste
			| modelo de dados.



		:Returns: ‹ array ›|br|





.. rst-class:: public

	.. php:method:: public getInitialDataModel()

		.. rst-class:: phpdoc-description

			| Retorna um ``array`` associativo contendo todos os campos definidos para o
			| modelo atual e seus respectivos valores iniciais.



		:Returns: ‹ array ›|br|





.. rst-class:: public

	.. php:method:: public isInitial()

		.. rst-class:: phpdoc-description

			| Verifica se algum valor já foi definido para algum campo deste modelo de dados.

			| A partir do acionamento de qualquer método de alteração de campos e obter sucesso
			| ao defini-lo, o resultado deste método será sempre ``false``.



		:Returns: ‹ bool ›|br|





.. rst-class:: public

	.. php:method:: public isValid()

		.. rst-class:: phpdoc-description

			| Informa se o modelo de dados tem no momento valores que satisfazem os critérios de
			| validação para todos os seus campos.



		:Returns: ‹ bool ›|br|





.. rst-class:: public

	.. php:method:: public getState()

		.. rst-class:: phpdoc-description

			| Retorna o código do estado atual deste modelo de dados.

			| Se todos seus campos estão com valores válidos será retornado ``valid``.
			|
			| Caso contrário, será retornado um ``array`` associativo com o estado de cada um dos
			| campos.
			|
			| Campos *collection* trarão um ``array`` associativo conforme o modelo:
			|
			| \`\`\`php
			|      $arr = [
			|          // string   Estado geral da coleção como um todo.
			|          &#34;collection&#34; => "",
			|
			|          // string[] Estado individual de cada um dos itens.
			|          &#34;itens&#34; => []
			|      ];
			| \`\`\`



		:Returns: ‹ string | array ›|br|





.. rst-class:: public

	.. php:method:: public getLastValidateState()

		.. rst-class:: phpdoc-description

			| Referente a última validação executada:
			| Se todos seus campos estão com valores válidos será retornado ``valid``.

			| Caso contrário, será retornado um ``array`` associativo com o estado de cada um dos campos.
			|
			| Quando executado após o uso de ``setFieldValue()`` o resultado será equivalente ao uso de
			| ``iField->getLastValidateState()``.
			|
			| Campos *collection* trarão um ``array`` associativo conforme o modelo:
			|
			| \`\`\`php
			|      $arr = [
			|          // string   Estado geral da coleção como um todo.
			|          &#34;collection&#34; => "",
			|
			|          // string[] Estado individual de cada um dos itens.
			|          &#34;itens&#34; => []
			|      ];
			| \`\`\`



		:Returns: ‹ string | array ›|br|





.. rst-class:: public

	.. php:method:: public getLastValidateCanSet()

		.. rst-class:: phpdoc-description

			| Retornará ``true`` caso a última validação realizada permitir que o valor testado seja
			| definido para o modelo de dados usado.



		:Returns: ‹ bool ›|br|





.. rst-class:: public

	.. php:method:: public validateValues( $objValues, $checkAll=false)

		.. rst-class:: phpdoc-description

			| Verifica se o valor indicado satisfaz os critérios que de validação dos campos em comum
			| que ele tenha com o presente modelo de dados.

			| A validação é feita seguindo os seguintes passos:
			| 1. Verifica se o valor passado é ``iterable``.
			| 2. Verifica se o valor passado possui alguma propriedade/campo que seja inexistênte
			|    para o modelo de dados desta instância.
			| 3. Verifica se nenhuma propriedade foi encontrada no objeto passado.
			| 4. Se ``checkAll`` for definido como ``true`` então irá verificar se restou ser
			|    apresentado algum campo obrigatorio. Campos que tenham configuração de valor default
			|    não invalidarão este tipo de teste.
			|
			|
			| **Método &#34;getLastValidateState()&#34;**
			| Após uma validação é possível usar este método para averiguar com precisão qual foi o
			| motivo da falha.
			| Para os passos **1** e **3** será retornado uma ``string`` única com o código do erro.
			| Para os passos **2** e **4** será retornado um ``array`` associativo contendo uma chave
			| para cada campo testado e seu respectivo código de validação.
			|
			|
			| **Método &#34;getLastValidateCanSet()&#34;**
			| Após uma validação é possível usar este método para averiguar se o valor passado,
			| passando ou não, pode ser efetivamente definido para o modelo de dados.



		:Parameters:
			- ‹ mixed › **$objValues** |br|
			  Objeto que traz os valores a serem testados.

			- ‹ bool › **$checkAll** |br|
			  Quando ``true`` apenas confirmará a validade da coleção de valores se com os
			  mesmos for possível preencher todos os campos obrigatórios deste modelo de
			  dados. Campos não declarados mas que possuem um valor padrão definido **SEMPRE**
			  passarão neste tipo de validação


		:Returns: ‹ bool ›|br|


		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso o objeto passado possua propriedades não correspondentes aos campos
			  definidos.




.. rst-class:: public

	.. php:method:: public setFieldValue( $f, $v)

		.. rst-class:: phpdoc-description

			| Define o valor do campo de nome indicado.

			| Internamente executa o método ``iField->setValue()``.



		:Parameters:
			- ‹ string › **$f** |br|
			  Nome do campo cujo valor será definido.

			- ‹ mixed › **$v** |br|
			  Valor a ser definido para o campo.


		:Returns: ‹ bool ›|br|
			  Retornará ``true`` se o valor tornou o campo válido ou ``false`` caso
			  agora ele esteja inválido.
			  Também retornará ``false`` caso o valor seja totalmente incompatível
			  com o campo.

		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso o nome do campo não seja válido.




.. rst-class:: public

	.. php:method:: public getFieldValue( $f)

		.. rst-class:: phpdoc-description

			| Retorna o valor atual do campo de nome indicado.

			| Internamente executa o método ``iField->getValue()``.



		:Parameters:
			- ‹ string › **$f** |br|
			  Nome do campo alvo.


		:Returns: ‹ mixed ›|br|


		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso o nome do campo não seja válido.




.. rst-class:: public

	.. php:method:: public getFieldStorageValue( $f)

		.. rst-class:: phpdoc-description

			| Retorna o valor atual do campo de nome indicado.

			| Internamente executa o método ``iField->getStorageValue()``.



		:Parameters:
			- ‹ string › **$f** |br|
			  Nome do campo alvo.


		:Returns: ‹ mixed ›|br|


		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso o nome do campo não seja válido.




.. rst-class:: public

	.. php:method:: public getFieldRawValue( $f)

		.. rst-class:: phpdoc-description

			| Retorna o valor atual do campo de nome indicado.

			| Internamente executa o método ``iField->getRawValue()``.



		:Parameters:
			- ‹ string › **$f** |br|
			  Nome do campo alvo.


		:Returns: ‹ mixed ›|br|


		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso o nome do campo não seja válido.




.. rst-class:: public

	.. php:method:: public setValues( $objValues, $checkAll=false)

		.. rst-class:: phpdoc-description

			| Permite definir o valor de inúmeros campos do modelo de dados a partir de um objeto
			| compatível.

			| Se todos os valores passados forem possíveis de serem definidos para seus respectivos
			| campos de dados então isto será feito mesmo que isto  torne o modelo como um todo
			| inválido.



		:Parameters:
			- ‹ mixed › **$objValues** |br|
			  Objeto que traz os valores a serem redefinidos para o atual modelo de
			  dados.

			- ‹ bool › **$checkAll** |br|
			  Quando ``true`` apenas irá definir os dados caso seja possível definir
			  todos os campos do modelo de dados com os valores explicitados.
			  Os campos não definidos devem poder serem definidos com seus valores
			  padrão, caso contrário o *set* não será feito.


		:Returns: ‹ bool ›|br|
			  Retornará ``true`` caso os valores passados tornem o modelo válido.

		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso o objeto passado possua propriedades não correspondentes aos campos
			  definidos.




.. rst-class:: public

	.. php:method:: public getValues()

		.. rst-class:: phpdoc-description

			| Retorna um ``array`` associativo contendo todos os campos do modelo de dados e seus
			| respectivos valores atualmente definidos.

			| Internamente executa o método ``iField->getValue()`` para cada um dos campos de dados
			| existente.



		:Returns: ‹ array ›|br|





.. rst-class:: public

	.. php:method:: public getStorageValues()

		.. rst-class:: phpdoc-description

			| Retorna um ``array`` associativo contendo todos os campos do modelo de dados e seus
			| respectivos valores atualmente definidos.

			| Internamente executa o método ``iField->getStorageValue()`` para cada um dos campos
			| de dados existente.



		:Returns: ‹ array ›|br|





.. rst-class:: public

	.. php:method:: public getRawValues()

		.. rst-class:: phpdoc-description

			| Retorna um ``array`` associativo contendo todos os campos do modelo de dados e seus
			| respectivos valores atualmente definidos.

			| Internamente executa o método ``iField->getRawValue()`` para cada um dos campos de
			| dados existente.



		:Returns: ‹ array ›|br|





.. rst-class:: public

	.. php:method:: public __construct( $config)

		.. rst-class:: phpdoc-description

			| Inicia um novo modelo de dados.

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
			|          // iField[]         Array contendo as instâncias dos campos que devem compor este
			|          //                  modelo de dados.
			|          &#34;fields&#34; => ,
			|      ];
			| \`\`\`



		:Parameters:
			- ‹ array › **$config** |br|
			  Array associativo com as configurações para este modelo de dados.


		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso algum valor passado não seja válido.




.. rst-class:: public

	.. php:method:: public __call( $name, $arguments)

		.. rst-class:: phpdoc-description

			| Permite efetuar o auto-set de um dos campos quando este for do tipo *reference*.



		:Parameters:
			- ‹ string › **$name** |br|
			  Nome do método.
			  É preciso ter o prefixo ``new`` e o nome do campo que será
			  automaticamente definido.

			- ‹ array › **$arguments** |br|
			  Opcionalmente pode ser definido uma coleção de valores a serem
			  definidos para a nova instância.


		:Returns: ‹ mixed ›|br|





.. rst-class:: public

	.. php:method:: public __set( $name, $value)

		.. rst-class:: phpdoc-description

			| Permite efetuar o SET do valor de um campo utilizando uma notação amigável.

			| Internamente executa o método ``setFieldValue()``.
			| Não retorna nenhum valor, e, caso o valor passado não seja válido para este campo,
			| nenhuma alteração será feita sobre o valor pré-existente.



		:Parameters:
			- ‹ string › **$name** |br|
			  Nome do campo.

			- ‹ mixed › **$value** |br|
			  Valor a ser definido.





.. rst-class:: public

	.. php:method:: public __get( $name)

		.. rst-class:: phpdoc-description

			| Permite efetuar o GET do valor de um campo utilizando uma notação amigável.

			| Internamente executa o método ``getFieldValue()``.



		:Parameters:
			- ‹ string › **$name** |br|
			  Nome do campo.


		:Returns: ‹ mixed ›|br|





.. rst-class:: public

	.. php:method:: public getIterator()

		.. rst-class:: phpdoc-description

			| Método que permite a iteração sobre os valores armazenados na coleção de dados da
			| instância usando ``foreach()`` do PHP.

			| \`\`\`php
			|     $oModel = new iModel();
			|     ...
			|     foreach($oModel as $fieldName => $fieldValue) { ... }
			| \`\`\`



		:Returns: ‹ \\Traversable ›|br|
