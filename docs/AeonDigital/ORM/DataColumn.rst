.. rst-class:: phpdoctorst

.. role:: php(code)

	:language: php


DataColumn
==========


.. php:namespace:: AeonDigital\ORM

.. php:class:: DataColumn


	.. rst-class:: phpdoc-description

		| Representação de uma coluna de dados comum.


	:Parent:
		:php:class:`AeonDigital\\DataModel\\Abstracts\\aField`

	:Implements:
		:php:interface:`AeonDigital\\Interfaces\\ORM\\iColumn`

	:Used traits:
		:php:trait:`AeonDigital\ORM\Traits\ColumnProperties` :php:trait:`AeonDigital\ORM\Traits\DataColumnCommomMethods`


Methods
-------

.. rst-class:: public

	.. php:method:: public __construct( $config)

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

			           string          &#34;type&#34;
			           Nome completo de uma classe que implemente a interface &#34;iSimpleType&#34;.
			           OU &#34;ref&#34; para identificar que este campo referencia-se a um outro modelo
			           de dados.

			           string          &#34;inputFormat&#34;
			           Nome completo de uma classe que implemente a interface &#34;iFormat&#34;. (opcional)

			           int             &#34;length&#34;
			           Tamanho máximo do campo em caracteres. (opcional)
			           Se não for definido explicitamente poderá herdar das informações
			           indicadas em &#34;inputFormat&#34;.

			           mixed           &#34;min&#34;
			           Valor mínimo aceito para este campo. (opcional)
			           Use apenas para casos de campos numéricos ou data/hora.

			           mixed           &#34;max&#34;
			           Valor máximo aceito para este campo. (opcional)
			           Use apenas para casos de campos numéricos ou data/hora.

			           bool            &#34;allowNull&#34;
			           Indica se &#34;null&#34; é um valor aceito para este campo. (opcional)

			           bool            &#34;allowEmpty&#34;
			           Indica se "" é um valor aceito para este campo. (opcional)

			           bool            &#34;convertEmptyToNull&#34;
			           Indica se, ao receber um valor "", este deve ser convertido para &#34;null&#34;. (opcional)

			           bool            &#34;readOnly&#34;
			           Indica se o campo é apenas de leitura.
			           Neste caso ele poderá ser definido apenas 1 vez e após
			           isto seu valor não poderá ser alterado. (opcional)

			           mixed           &#34;default&#34;
			           Valor padrão para este campo. (opcional)

			           array|string    &#34;enumerator&#34;
			           Coleção de valores válidos para este campo. (opcional)
			           Se for definido uma string, deve ser o caminho completo até um arquivo php
			           que contêm o array a ser utilizado como enumerador.

			           mixed           &#34;value&#34;
			           Valor que inicia com o campo.

			           bool            &#34;unique&#34;
			           Indica quando esta coluna de dados deve ser a única dentro da coleção
			           de registros da tabela de dados a possuir o valor atual.

			           bool            &#34;autoIncrement&#34;
			           Indica quando esta coluna de dados deve ter seu valor definido pelo próprio
			           SGDB usando assim o controle de auto-incremento.

			           bool            &#34;primaryKey&#34;
			           Indica quando esta coluna de dados é a chave primária da tabela de dados.

			           bool            &#34;index&#34;
			           Indica quando esta coluna de dados deve ser indexada.
			       ];
			  \`\`\`


		:Throws: ‹ \InvalidArgumentException ›|br|
			  Caso algum valor passado não seja válido.
