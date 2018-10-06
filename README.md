 PHP-DAL
=========

> [Aeon Digital](http://aeondigital.com.br)
>
> rianna@aeondigital.com.br  

Provê uma camada de acesso à bancos de dados.


&nbsp;  
&nbsp;  


## AeonDigital\DAL\DAL
Classe principal do projeto. Permite efetuar uma conexão com um banco de dados usando o PDO do PHP e simplifica o uso do mesmo para as operações mais comuns de uso.  

**Métodos referentes às propriedades da conexão**  

  - `getCloneConnection`    : Retorna um clone da conexão atual.
  - `getDBType`             : Retorna o tipo do banco de dados usado nesta conexão.
  - `getDBHost`             : Retorna o Host da conexão.
  - `getDBName`             : Retorna o nome do banco de dados.
  - `replaceConnection`     : Permite substituir a conexão da atual instância por uma outra.

&nbsp; 

**Métodos de execução de instruções SQL**  

  - `executeInstruction`    : Efetua a execução de uma instrução SQL.
  - `getDataTable`          : Efetua uma consulta SQL e retorna seu resultado.
  - `getDataRow`            : Efetua uma consulta SQL e retorna apenas a primeira linha de seu resultado.
  - `getDataColumn`         : Efetua uma consulta SQL e retorna o valor da primeira coluna encontrada na primeira linha de seu resultado.
  - `getCountOf`            : Efetua uma consulta SQL que tenha como objetivo retornar apenas o resultado obtido da função "COUNT" do SQL.
  - `isExecuted`            : Indica se a última instrução SQL foi executada sem erros.
  - `countAffectedRows`     : Indica a quantidade total de linhas afetadas pela última instrução SQL.
  - `getLastError`          : Retorna uma mensagem de erro para a última instrução SQL executada (se ela tiver falhado).

&nbsp; 

**Métodos CRUD e especializados**  

  - `getLastPK`             : Retorna o último Id (PrimaryKey com AutoIncrement) definido na tabela alvo.
  - `countRowsFrom`         : Efetua a contagem de registros existentes na tabela alvo.
  - `insertInto`            : Insere um novo registro na tabela alvo.
  - `updateSet`             : Atualiza um novo registro na tabela alvo.
  - `insertOrUpdate`        : Insere ou atualiza o registro passado na tabela alvo.
  - `selectFrom`            : Seleciona as colunas definidas para o registro de PK indicada.
  - `deleteFrom`            : Exclui um registro da tabela alvo que tenha a PK indicada.

&nbsp; 

**Métodos referentes ao uso de Transaction**  
Quando ativo o desenvolvedor deve definir quando a instrução SQL deve ser firmada no banco de dados ou quando deve ser efetuado o rollback ao estado anterior ao modo transaction iniciar.

  - `inTransaction`         : Indica se a conexão está em modo "transaction".
  - `beginTransaction`      : Inicia o modo "transaction".
  - `commit`                : Efetiva as transações realizadas desde que o modo de transação foi aberto.
  - `rollBack`              : Efetua o rollback das transações feitas desde que o modo de transação foi aberto.


&nbsp;  
&nbsp;  


## Dependências

  - [PHP-Numbers](https://github.com/AeonDigital/PHP-Numbers)


&nbsp;  
&nbsp;  


_______________________________________________________________________________________________________________________

## Testes unitários

[Verifique as instruções](tests/phpunit.md) para instalação e execução dos testes unitários usando o PHPUnit.


&nbsp;  
&nbsp;  


_______________________________________________________________________________________________________________________

## Documentação

[Verifique as instruções](docs/phpdoc.md) para instalação do phpDocumentor e saber como extrair o PHPDoc dos arquivos de código fonte.


&nbsp;  
&nbsp;  


________________________________________________________________________________________________________________________

## Licença

Os projetos públicos da **Aeon Digital** utilizam a [Licença GNUv3](LICENCE.md).
