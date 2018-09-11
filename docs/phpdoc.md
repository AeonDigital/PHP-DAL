 Documentação técnica com PHPDoc
=================================

PHPDoc é um padrão de documentação (utilizando DocBlock) para permitir aos desenvolvedores escreverem a documentação de seu código de forma consistente e que pode vir a ser extraída por outras ferramentas para gerarem formas mais fáceis de serem consultadas e/ou verificadas.
Os projetos PHP da AeonDigital estão documentados utilizando este padrão.  

Importante lembrar que o termo **PHPDoc** refere-se a 2 coisas distintas.  
Uma é o padrão de construção dos blocos de documentação (chamados DocBlock) cuja referências podem ser consultadas no endereço http://docs.phpdoc.org/references/phpdoc/index.html .  
Outra coisa é uma aplicação que lê esta documentação e trata por extraí-la conforme for a configuração definida.

Abaixo, consta como instalar e usar a aplicação **PHPDocumentor** (também chamado de PHPDoc).  

&nbsp;  

**Importante**
Até a presente data (2018-07-14) o **phpDocumentor** apresenta um bug quando confrontado com a nova sintaxe do PHP 7.1+ portanto, infelizmente, ainda não é possível extrair a documentação conforme se deseja.  


&nbsp;  
&nbsp;  


## Instalação
O PHPDocumentor é distribuido no formato **phar** que pode ser convertido em um comando executável conforme o ambiente que você está usando.  


&nbsp;  
&nbsp;  


### Linux

  - Acesse o Terminal e baixe-o:
    > sudo wget http://www.phpdoc.org/phpDocumentor.phar
  - Torne-o executável:
    > chmod +x phpDocumentor.phar
  - Mova-o para a pasta de binários local:
    > sudo mv phpDocumentor.phar /usr/local/bin/phpDocumentor
  - Verifique:
    > phpDocumentor --version

&nbsp;  

### Windows

  - Baixe o arquivo **phar** em http://www.phpdoc.org/phpDocumentor.phar
  - Abra um terminal (como administrador) e vá até o diretório onde o arquivo está salvo.
  - Crie um executável com o comando:
    > echo @php "%~dp0phpDocumentor.phar" %* > phpdoc.cmd
  - Mova o arquivo para um local que faça parte da Variável de Ambiente "PATH".
  - Verifique:
    > phpunit --version

#### Observação
  No PowerShell, o comando para criar o executável não funciona (2018-07-14).


&nbsp;  
&nbsp;  


## Executando
Abra um terminal (bash, cmd, PowerShell) e vá até o diretório onde está o arquivo de configuração **phpdoc.xml**.  
Este arquivo já está preparado com as configurações necessárias para a extração da documentação de cada projeto AeonDigital.  

&nbsp;  

Para extrair a documentação use:
> phpdoc run  

O resultado será criado do diretório **docs**.


&nbsp;  
&nbsp;  

_______________________________________________________________________________________________________________________

# Menu
  - [README](../README.md)
  - [PHPUnit](../tests/phpunit.md)
  - [PHPDoc](../docs/phpdoc.md)
