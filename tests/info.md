 Informativo
==============

O presente documento traz informações que podem ser úteis para desenvolvedores deste projeto. Leia atentamente os tópicos abaixo para saber mais.


&nbsp;  
&nbsp;  


## Documentação técnica

Para exportar a documentação técnica extraida das anotações PHPDoc contidas no corpo dos scripts PHP use os comandos abaixo:

1. Instale o projeto *aeondigital/phpdoc-to-rst*
   > composer require --dev aeondigital/phpdoc-to-rst

2. Use o comando *phpdoc-to-rst config*
   > ./vendor/bin/phpdoc-to-rst config

3. Extraia o PHPDoc para reST
   > ./vendor/bin/phpdoc-to-rst generate docs/rest src

   - Se quiser, extraia apenas o conteúdo relacionado com a namespace principal:
   > ./vendor/bin/phpdoc-to-rst generate-ns AeonDigital/Namespace docs/rest src 

4. Converta os arquivos reST para HTML
   > sphinx-build -b html docs/rest docs/html  


Para maiores informações sobre o uso do projeto *aeondigital/phpdoc-to-rst* consulte a documentação oficial no respectivo README.md ou vá no [repositório](https://github.com/AeonDigital/phpdoc-to-rst)

&nbsp;  

### Para este projeto, use sem pensar: 
0. Use o comando abaixo para atualizar as informações para a extração.
   > ./vendor/bin/phpdoc-to-rst config
1. Extraia a documentação
   > ./vendor/bin/phpdoc-to-rst generate-ns AeonDigital/DAL docs/rest src
2. Converta a documentação reST para HTML
   > sphinx-build -b html docs/rest docs/html  


&nbsp;  
&nbsp;  


## Versionamento
Sempre que alterar o alguma parte do código deste script atualize a versão do mesmo e submeta para o repositório sua nova versão.  
Abaixo segue um checklist de itens a serem observados antes de realizar tal atividade:  

1. Teste o código completo. Mesmo os itens que não foram modificados.  
   De dentro do diretório `tests` execute:
   > phpunit --configuration "phpunit.xml" --verbose --debug
2. Altere no *composer.json* a versão e a defina a nova data.
3. Exporte e crie a nova documentação (lembre de atualizar a versão usando o comando "config").
4. Registre todos os arquivos alterados para prepara-los para o commit.
   > git add *
5. Registre o commit com uma mensagem explicativa sobre a alteração feita.
   > git commit -m "Mensagem"
6. Efetue o commit.
   > git push origin master
7. Gere uma nova tag para este novo commit, definindo assim a nova versão do código.
   > git tag vx.x.x-alpha  
   > git push --tags origin
