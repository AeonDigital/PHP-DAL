 Desenvolvimento
=================

O presente documento traz informações que podem ser úteis para desenvolvedores
deste projeto. Leia atentamente os tópicos abaixo para saber mais.


&nbsp;
&nbsp;


_______________________________________________________________________________

## Extraindo a documentação técnica.

Para exportar a documentação técnica extraida das anotações PHPDoc contidas no
corpo dos scripts PHP use os comandos abaixo:

- Instale o projeto "*aeondigital/phpdoc-to-rst*"
  ```shell
  > composer require --dev aeondigital/phpdoc-to-rst
  ```

- Use o comando "*phpdoc-to-rst config*" para configurar seu projeto.
  ```shell
  > ./vendor/bin/phpdoc-to-rst config
  ```

- Extraia o **PHPDoc** para **reST**
  ```shell
  > ./vendor/bin/phpdoc-to-rst generate docs src --public-only
  ```

- Se quiser, extraia apenas o conteúdo relacionado com a namespace
  principal:
  ```shell
  > ./vendor/bin/phpdoc-to-rst generate-ns AeonDigital/Namespace docs/rest src --public-only
  ```

- Converta os arquivos **reST** para **HTML** [opcional]
  ```shell
  > sphinx-build -b html docs/rest docs/html
  ```


Para maiores informações sobre o uso do projeto "*aeondigital/phpdoc-to-rst*"
consulte a documentação oficial no respectivo arquivo README ou vá no
[repositório oficial](https://github.com/AeonDigital/phpdoc-to-rst)

&nbsp;

### Para este projeto, use sem pensar:
- Use o comando abaixo para atualizar as informações para a extração.
  ```shell
  > ./vendor/bin/phpdoc-to-rst config
  ```
- Extraia a documentação
  ```shell
  > ./vendor/bin/phpdoc-to-rst generate docs src --public-only
  ```
- Converta a documentação reST para HTML
  ```shell
  > sphinx-build -b html docs docs/html
  ```


&nbsp;
&nbsp;


_______________________________________________________________________________

## Versionamento

Sempre que alterar o alguma parte do código deste script atualize a versão do
mesmo e submeta para o repositório sua nova versão.
Abaixo segue um checklist de itens a serem observados antes de realizar tal
atividade:

- Teste o código completo. Mesmo os itens que não foram modificados.
  De dentro do diretório `tests` execute:
  ```shell
  > phpunit --configuration "phpunit.xml" --verbose --debug
  ```
- Altere no *composer.json* a versão e defina a nova data.
- Exporte e crie a nova documentação (lembre de atualizar a versão usando o
  comando "config").
- Registre todos os arquivos alterados para prepará-los para o commit.
  ```shell
  > git add *
  ```
- Registre o commit com uma mensagem explicativa sobre a alteração feita.
  ```shell
  > git commit -m "Mensagem"
  ```
- Efetue o commit.
  ```shell
  > git push origin master
  ```
- Gere uma nova tag para este novo commit, definindo assim a nova versão do
  código.
  ```shell
  > git tag vx.x.x-alpha
  > git push --tags origin
  ```
