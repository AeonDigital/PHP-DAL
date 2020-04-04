.. rst-class:: phpdoctorst

.. role:: php(code)

	:language: php


aField
======


.. php:namespace:: AeonDigital\DataModel\Abstracts

.. rst-class::  abstract

.. php:class:: aField


	.. rst-class:: phpdoc-description

		| Classe abstrata que implementa ``iField``.


	:Implements:
		:php:interface:`AeonDigital\\Interfaces\\DataModel\\iField`


Properties
----------

Methods
-------

.. rst-class:: public

	.. php:method:: public getName()

		.. rst-class:: phpdoc-description

			| Retorna o nome do campo.



		:Returns: ‹ string ›|br|





.. rst-class:: public

	.. php:method:: public getDescription()

		.. rst-class:: phpdoc-description

			| Retorna a descrição de uso/funcionalidade do campo.



		:Returns: ‹ string ›|br|





.. rst-class:: public

	.. php:method:: public getType()

		.. rst-class:: phpdoc-description

			| Retorna o nome completo da classe que determina o tipo deste campo.



		:Returns: ‹ string ›|br|





.. rst-class:: public

	.. php:method:: public getInputFormat()

		.. rst-class:: phpdoc-description

			| Retorna o nome da classe que determina o formato de entrada que o valor a ser
			| armazenado pode assumir
			| **OU**
			| retorna o nome de uma instrução especial de transformação de caracteres para
			| campos do tipo ``string``.



		:Returns: ‹ ?string ›|br|





.. rst-class:: public

	.. php:method:: public getLength()

		.. rst-class:: phpdoc-description

			| Retorna o tamanho máximo (em caracteres) aceitos por este campo.

			| Deve retornar ``null`` quando não há um limite definido.



		:Returns: ‹ ?int ›|br|





.. rst-class:: public

	.. php:method:: public getMin()

		.. rst-class:: phpdoc-description

			| Retorna o menor valor possível para um tipo numérico ou ``DateTime``.

			| Por padrão, herdará este valor da definição de seu ``type`` quando isto for aplicável.



		:Returns: ‹ ?int | ?\\AeonDigital\\Numbers\\RealNumber | ?\\DateTime ›|br|





.. rst-class:: public

	.. php:method:: public getMax()

		.. rst-class:: phpdoc-description

			| Retorna o maior valor possível para um tipo numérico ou ``DateTime``.

			| Por padrão, herdará este valor da definição de seu ``type`` quando isto for aplicável.



		:Returns: ‹ ?int | ?\\AeonDigital\\Numbers\\RealNumber | ?\\DateTime ›|br|





.. rst-class:: public

	.. php:method:: public isAllowNull()

		.. rst-class:: phpdoc-description

			| Indica se é ou não permitido atribuir ``null`` como um valor válido para este campo.



		:Returns: ‹ bool ›|br|





.. rst-class:: public

	.. php:method:: public isAllowEmpty()

		.. rst-class:: phpdoc-description

			| Indica se é ou não permitido atribuir ``''`` como um valor válido para este campo.



		:Returns: ‹ ?bool ›|br|





.. rst-class:: public

	.. php:method:: public isConvertEmptyToNull()

		.. rst-class:: phpdoc-description

			| Define se, ao receber um valor ``''``, este deverá ser convertido para ``null``.



		:Returns: ‹ bool ›|br|





.. rst-class:: public

	.. php:method:: public isReadOnly()

		.. rst-class:: phpdoc-description

			| Indica se este campo é ou não ``readonly``.



		:Returns: ‹ bool ›|br|





.. rst-class:: public

	.. php:method:: public isReference()

		.. rst-class:: phpdoc-description

			| Indica quando este campo é do tipo *reference*, ou seja, seu valor é um
			| modelo de dados.



		:Returns: ‹ bool ›|br|





.. rst-class:: public

	.. php:method:: public isCollection()

		.. rst-class:: phpdoc-description

			| Indica quando trata-se de um campo capaz de conter uma coleção de valores.



		:Returns: ‹ bool ›|br|





.. rst-class:: public

	.. php:method:: public isValid()

		.. rst-class:: phpdoc-description

			| Informa se o campo tem no momento um valor que satisfaz os critérios de validação
			| para o mesmo.



		:Returns: ‹ bool ›|br|





.. rst-class:: public

	.. php:method:: public getState()

		.. rst-class:: phpdoc-description

			| Retorna o código do estado atual deste campo.

			| **Campos Simples**
			| Retornará ``valid`` caso o valor definido seja válido, ou o código da validação
			| que caracteríza a invalidez deste valor.
			|
			| **Campos &#34;reference&#34;**
			| Retornará ``valid`` se **TODOS** os campos estiverem com valores válidos. Caso
			| contrário retornará um ``array`` associativo contendo o estado de cada um dos campos
			| existêntes.
			|
			| **Campos &#34;collection&#34;**
			| Retornará ``valid`` caso **TODOS** os valores estejam de acordo com os critérios de
			| validação ou um ``array`` contendo a validação individual de cada ítem membro da
			| coleção.



		:Returns: ‹ string | array ›|br|





.. rst-class:: public

	.. php:method:: public getLastValidateState()

		.. rst-class:: phpdoc-description

			| Retornará o resultado da validação conforme o tipo de campo testado.

			| **Campos Simples**
			| Retornará ``valid`` caso o valor definido seja válido, ou o código da validação
			| que caracteríza a invalidez deste valor.
			|
			| **Campos &#34;reference&#34;**
			| Retornará ``valid`` se **TODOS** os campos estiverem com valores válidos. Caso
			| contrário retornará um ``array`` associativo contendo o estado de cada um dos campos
			| existêntes.
			|
			| **Campos &#34;collection&#34;**
			| Retornará ``valid`` caso **TODOS** os valores estejam de acordo com os critérios de
			| validação ou um ``array`` contendo a validação individual de cada ítem membro da
			| coleção.



		:Returns: ‹ string | array ›|br|





.. rst-class:: public

	.. php:method:: public getLastValidateCanSet()

		.. rst-class:: phpdoc-description

			| Retornará ``true`` caso a última validação realizada permitir que o valor testado
			| seja definido para este campo.

			| **Campos Simples**
			| Valores inválidos podem ser definidos quando eles forem do mesmo ``type`` deste campo.
			|
			| **Campos &#34;reference&#34;**
			| Se **TODOS** os valores passados para um modelo de dados puderem ser assumidos por seus
			| respectivos campos, então tais dados poderão ser utilizados para preencher a instância.
			|
			| **Campos &#34;collection&#34;**
			| Se **TODOS** os valores membros para uma coleção de dados puderem ser setados,
			| independente de serem válidos, então, a coleção poderá assumir aquele grupo de dados.



		:Returns: ‹ bool ›|br|





.. rst-class:: public

	.. php:method:: public validateValue( $v)

		.. rst-class:: phpdoc-description

			| Verifica se o valor indicado satisfaz os critérios que permitem dizer que o valor
			| passado é válido.

			| **Valores especiais e seus efeitos**
			|  ``undefined``
			|  Sempre falhará na validação.
			|
			|  ``null``
			|  Falhará se o campo não permitir este valor [ veja propriedade ``allowNull`` ].
			|
			|  ``''``
			|  Falhará se o campo não permitir este valor e estiver com a conversão de ``''`` em
			|  ``null`` desabilitada [ veja as propriedades ``allowEmpty`` e ``convertEmptyToNull`` ].
			|
			|  ``[]``
			|  Falhará SEMPRE para campos que não forem ``collection``.
			|
			|
			| **Validação dos Campos Simples**
			|  A validação é feita seguindo os seguintes passos:
			|
			|  1. Verifica se o campo está apto a receber um valor ou se ele é do tipo ``readOnly``.
			|  2. Verifica se o valor cai em algum dos valores especiais citados no tópico anterior.
			|  3. Verifica se o valor não é um objeto de um tipo não aceito.
			|    Os tipos aceitos para campos simples são:
			|    ``bool``, ``int``, ``float``, ``RealNumber``, ``DateTime``, ``string``
			|  4. Validação de tipo:
			|  4.1. Havendo um ``inputFormat`` definido, identifica se o valor passa em sua
			|    respectiva validação.
			|  4.2. Verifica se o valor passado é um representante válido do tipo base do campo.
			|  5. Verificação de adequação:
			|  5.1. Enumerador, se houver, verifica se o valor está entre os itens válidos.
			|  5.2. Sendo um campo ``string`` e existindo uma definição de tamanho máximo
			|   [ propriedade ``length`` ] verifica se o valor não excede seu limite.
			|  5.3. Sendo um campo numérico ou de data e existindo limites definidos para seus
			|   valores mínimos e máximos, identifica se o valor passado não excede algum destes
			|   limites.
			|
			| **Valores aceitáveis**
			| ``null``, ``bool``, ``int``, ``float``, ``RealNumber``, ``DateTime``, ``string``
			|
			|
			| **Regras de aceitação**
			|  No passo 4.1, caso falhe na validação de ``inputFormat`` mas tanto o valor passado
			|  quanto o próprio campo são do tipo ``string`` ocorrerá que a validação não impedirá
			|  que tal valor seja definido para este campo, mas ele ficará com o estado inválido.
			|
			|  Com excessão da regra especificada acima, falhas ocorridas até o passo 5 invalida
			|  totalmente o valor para poder ser definido como o valor do campo atual.
			|
			|  Falhas ocorridas no passo 5, apesar de falhar na validação, indica que o valor poderá
			|  passar a representar o valor atual do campo mas seu estado passará a ser &#34;inválido&#34;.
			|
			|
			| **Validação de Campos &#34;reference&#34;**
			|  A validação é feita tentando usar o conjunto de valores passado para que ele preencha
			|  os campos de um modelo de dados do mesmo tipo que este campo está apto a representar.
			|  É preciso que **TODAS** as respectivas chaves de dados compatíveis com o modelo de
			|  dados representado pelo campo possam ser aceitos (independente de serem válidos) para
			|  que o objeto seja validado.
			|
			| **Valores aceitáveis**
			|  ``null``, ``iterable``, ``array``, ``iModel``
			|
			|
			| **Validação de Campos &#34;collection&#34;**
			|  A validação é feita submetendo cada um dos membros da coleção indicada a seu
			|  respectivo tipo de validação. Os dados serão utilizados pelo campo se todos os membros
			|  apresentados puderem ser definidos.
			|
			| **Valores aceitáveis**
			| ``null``, ``array``



		:Parameters:
			- ‹ mixed › **$v** |br|
			  Valor que será testado.


		:Returns: ‹ bool ›|br|





.. rst-class:: public

	.. php:method:: public getDefault( $getInstruction=false)

		.. rst-class:: phpdoc-description

			| Retorna o valor padrão que este campo deve ter caso nenhum outro seja definido.

			| Se ``default`` não for definido, ``undefined`` será retornado.



		:Parameters:
			- ‹ bool › **$getInstruction** |br|
			  Quando ``true``, retorna o nome da instrução especial que define o
			  valor padrão.


		:Returns: ‹ mixed ›|br|





.. rst-class:: public

	.. php:method:: public getEnumerator( $getOnlyValues=false)

		.. rst-class:: phpdoc-description

			| Retorna um ``array`` com a coleção de valores que este campo está apto a assumir.

			| Os valores aqui pré-definidos devem seguir as mesmas regras de validade especificadas
			| nas demais propriedades.



		:Parameters:
			- ‹ bool › **$getOnlyValues** |br|
			  Quando ``true``, retorna um array unidimensional contendo apenas os
			  valores válidos de serem selecionados sem seus respectivos ``labels``.


		:Returns: ‹ ?array ›|br|





.. rst-class:: public

	.. php:method:: public setValue( $v)

		.. rst-class:: phpdoc-description

			| Define um novo valor para este campo.

			| O valor passado será validado e será definido caso seu valor seja condizente com as
			| regras de aplicação especificadas na descrição do método ``validateValue()``.
			|
			|
			| Define um novo valor para este campo.
			|
			| **undefined**
			| Este valor **NUNCA** será aceito por nenhum tipo de campo e em qualquer circunstância.
			|
			|
			| **Campos Simples**
			| Para que o campo assuma o novo valor ele precisa ser compatível com o ``type`` definido.
			| Caso contrário o campo ficará com o valor ``null``.
			|
			| **Valores aceitáveis**
			| ``null``, ``bool``, ``int``, ``float``, ``RealNumber``, ``DateTime``, ``string``
			|
			|
			| **Campos &#34;reference&#34;**
			| Campos deste tipo apenas aceitarão valores capazes de preencher os campos do modelo
			| de dados ao qual eles se referenciam. Independente de tornar o modelo de dados válido
			| ou não, os valores serão definidos exceto se o valor passado for incompatível com o
			| modelo de dados configurado.
			|
			| **Valores aceitáveis**
			| ``null``, ``iterable``, ``array``, ``iModel``
			|
			|
			| **Campos &#34;collection&#34;**
			| Uma coleção de dados sempre será definida como o valor de um campo que aceite este
			| tipo de valor.
			| Os membros da coleção serão convertidos para o tipo ``type`` definido. Membros que
			| não possam ser convertidos serão substituidos por ``null`` e a coleção será inválida
			| até que estes membros sejam removidos ou substituídos.
			|
			| Coleções do tipo *reference* apenas serão redefinidos se **TODOS** seus itens forem
			| capazes de tornarem-se objetos ``iModel`` do tipo definido para este campo.
			|
			| **Valores aceitáveis**
			| ``null``, ``array``
			|
			|
			| **Estado e validação**
			| Independente de o valor vir a ser efetivamente definido para o campo o estado da
			| validação pode ser verificado usando ``getLastValidateState()``.
			|
			| Uma vez que o valor seja definido, o campo passa a assumir o estado herdado da
			| validação e poderá ser verificado em ``getState()``.



		:Parameters:
			- ‹ mixed › **$v** |br|
			  Valor a ser definido para o campo.


		:Returns: ‹ bool ›|br|
			  Retornará ``true`` se o valor tornou o campo válido ou ``false`` caso
			  agora ele esteja inválido. Também retornará ``false`` caso o valor seja
			  totalmente incompatível com o campo.




.. rst-class:: public

	.. php:method:: public getValue()

		.. rst-class:: phpdoc-description

			| Retorna o valor atual deste campo.

			| **undefined**
			| Este valor será retornado **ENQUANTO** o campo **AINDA** não foi redefinido com qualquer
			| outro valor. Esta regra se aplica para campos simples e *reference*.
			|
			|
			| **Campos Simples**
			| O valor retornado estará sempre no mesmo ``type`` que aquele que o campo está
			| configurado para assumir. Havendo alguma formatação indicada em ``inputFormat``, esta
			| será usada sobrepondo-se ao ``type``.
			|
			|
			| **Campos &#34;reference&#34;**
			| Estes campos apenas são capazes de retornar valores ``undefined``, ``null`` ou um ``array``
			| associativo representando o respectivo modelo de dados que ele está configurado para
			| receber.
			|
			|
			| **Campos &#34;collection&#34;**
			| O valor retornado será **SEMPRE** um ``array`` contendo os itens atualmente definidos.
			| Estes itens serão retornados conforme as regras definidas acima para *campos simples*.
			|
			| Coleções do tipo *reference* apenas retornarão um ``array`` de arrays associativos
			| representando a coleção de modelos de dados que o campo está apto a utilizar.
			|
			| Um *collection* em seu estado inicial retornará sempre um ``array`` vazio.



		:Returns: ‹ mixed ›|br|





.. rst-class:: public

	.. php:method:: public getStorageValue()

		.. rst-class:: phpdoc-description

			| Retorna o valor atual deste campo em seu formato de armazenamento.

			| **undefined**
			| O valor ``null`` será retornado no lugar de ``undefined`` para campos simples e
			| *reference*.
			|
			|
			| **Campos Simples**
			| O valor retornado estará sempre no mesmo ``type`` que aquele que o campo está
			| configurado para assumir. Qualquer regra para **REMOÇÃO** de formatação será aplicada
			| caso exista.
			|
			|
			| **Campos &#34;reference&#34;**
			| Estes campos apenas são capazes de retornar valores ``null`` ou arrays associativos
			| representando o respectivo modelo de dados que ele está configurado para receber.
			|
			|
			| **Campos &#34;collection&#34;**
			| O valor retornado será **SEMPRE** um ``array`` contendo os itens atualmente definidos.
			| Estes itens serão retornados conforme as regras definidas acima para *campos simples*.
			|
			| Coleções do tipo *reference* apenas retornarão um ``array`` de arrays associativos
			| representando a coleção de modelos de dados que o campo está apto a utilizar.
			|
			| Campos do tipo *collection* em seu estado inicial retornarsão sempre um ``array`` vazio.
			| Coleções que possuam valores inválidos entre seus membros também retornarão um ``array``
			| vazio.



		:Returns: ‹ mixed ›|br|





.. rst-class:: public

	.. php:method:: public getRawValue()

		.. rst-class:: phpdoc-description

			| Retorna o valor que está definido para este campo assim como ele foi passado em
			| ``setValue()``.



		:Returns: ‹ mixed ›|br|





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
			|          // bool             Indica se &#34;null&#34; é um valor aceito para este campo. (opcional)
			|          &#34;allowNull&#34; => ,
			|
			|          // bool             Indica se "' é um valor aceito para este campo. (opcional)
			|          &#34;allowEmpty&#34; => ,
			|
			|          // bool             Indica se, ao receber um valor "", este deve ser convertido para &#34;null&#34;. (opcional)
			|          &#34;convertEmptyToNull&#34; => ,
			|
			|          // bool             Indica se o campo é apenas de leitura.
			|          //                  Neste caso ele poderá ser definido apenas 1 vez e após
			|          //                  isto seu valor não poderá ser alterado. (opcional)
			|          &#34;readOnly&#34; => ,
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
			  ``array`` associativo com as configurações para este campo.


		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso algum valor passado não seja válido.
