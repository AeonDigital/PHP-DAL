#
# Aeon Digital
# Rianna Cantarelli <rianna@aeondigital.com.br>
#
.SILENT:





#
# Variáveis de controle
CONTAINER_WEB_NAME="dev-php-dal-webserver"
CONTAINER_DB_NAME="dev-php-dal-dbserver"




#
# Inicia o projeto
up:
	docker-compose up -d
	docker exec -it ${CONTAINER_WEB_NAME} composer install

#
# Inicia o projeto e prepara o container alvo para a extração da
# documentação técnica
up-docs: up docs-prepare-extraction

#
# Encerra o projeto
down:
	docker-compose down --remove-orphans

#
# Entra no bash principal do projeto
# Use o parametro 'cont' para indicar em qual container deseja entrar.
#   Valores aceitos são: web|db
#	Se nenhum valor for informado, entrará no 'web'
bash:
	if [ -z "${cont}" ] || [ "${cont}" = "web" ]; then \
	 	docker exec -it ${CONTAINER_WEB_NAME} /bin/bash; \
	fi; \
	if [ "${cont}" = "db" ]; then \
		docker exec -it ${CONTAINER_DB_NAME} /bin/bash; \
	fi;



#
# Instala as dependências do projeto
# usando o 'php composer'
composer-install:
	docker exec -it ${CONTAINER_WEB_NAME} composer install

#
# Atualiza as dependências do projeto
# usando o 'php composer'
composer-update:
	docker exec -it ${CONTAINER_WEB_NAME} composer update

#
# Retorna o IP da rede usado pelo container
get-ip:
	printf "Web-Server : "
	docker inspect ${CONTAINER_WEB_NAME} | grep -oP -m1 '(?<="IPAddress": ")[a-f0-9.:]+'
	printf "DB-Server  : "
	docker inspect ${CONTAINER_DB_NAME} | grep -oP -m1 '(?<="IPAddress": ")[a-f0-9.:]+'





#
# Executa a bateria de testes
#
# Opcionais
# Use o parametro 'file' para indicar que os testes devem percorrer apenas 
# os testes do arquivo especificado.
# Use o parametro 'method' (em adição ao parametro 'file') para indicar que 
# apenas este método do referido arquivo deve ser executado.
#
# > make test
# > make test file="path/to/tgtFile.php"
# > make test file="path/to/tgtFile.php" method="tgtMethodName"
test:
	if [ -z "${file}" ]; then \
		docker exec -it ${CONTAINER_WEB_NAME} vendor/bin/phpunit --configuration "tests/phpunit.xml" --colors=always --verbose --debug; \
	else \
		if [ -z "${method}" ]; then \
			docker exec -it ${CONTAINER_WEB_NAME} vendor/bin/phpunit "tests/src/${file}" --colors=always --verbose --debug; \
		else \
			docker exec -it ${CONTAINER_WEB_NAME} vendor/bin/phpunit --filter "::${method}$$" "tests/src/${file}" --colors=always --verbose --debug; \
		fi; \
	fi





#
# Executa a verificação total de cobertura dos testes unitários
#
# Opcionais
# Use o parametro 'file' para efetuar o teste de cobertura sobre apenas 1
# classe de testes.
#
# Use o parametro 'output' para selecionar o tipo de saida que o teste de 
# cobertura deve ter. As opções são:
#  - 'text' (padrão) : printa o resultado na tela.
#  - 'html' : Monta a saída dos testes em formato HTML.
#
# > make test-cover
# > make test-cover file="path/to/tgtFile.php"
# > make test-cover output="html"
# > make test-cover file="path/to/tgtFile.php" output="html"
test-cover:
	if [ -z "${file}" ] && [ -z "${output}" ]; then \
		docker exec -it ${CONTAINER_WEB_NAME} vendor/bin/phpunit --configuration "tests/phpunit.xml" --colors=always --coverage-text; \
	else \
		if [ -z "${file}" ]; then \
			if [ -z "${output}" ] || [ "${output}" = "text" ]; then \
				docker exec -it ${CONTAINER_WEB_NAME} vendor/bin/phpunit --configuration "tests/phpunit.xml" --colors=always --coverage-text; \
			elif [ "${output}" = "html" ]; then \
				docker exec -it ${CONTAINER_WEB_NAME} vendor/bin/phpunit --configuration "tests/phpunit.xml" --colors=always --coverage-html "tests/cover"; \
			else \
				echo "Parametro 'output' inválido. Use apenas 'text' ou 'html'."; \
			fi; \
		else \
			if [ -z "${output}" ] || [ "${output}" = "text" ]; then \
				docker exec -it ${CONTAINER_WEB_NAME} vendor/bin/phpunit "tests/src/${file}" --whitelist="tests/src/${file}" --colors=always --coverage-text; \
			elif [ "${output}" = "html" ]; then \
				docker exec -it ${CONTAINER_WEB_NAME} vendor/bin/phpunit "tests/src/${file}" --whitelist="tests/src/${file}" --coverage-html "tests/cover-file"; \
			else \
				echo "Parametro 'output' inválido. Use apenas 'text' ou 'html'."; \
			fi; \
		fi; \
	fi





#
# Prepara o container para que seja possível exportar a documentação técnica
# do código fonte para ser compatível com os requisitos do 'ReadTheDocs'.
# Este comando precisa ser rodado apenas 1 vez para cada novo container.
docs-prepare-extraction:
	docker exec -it ${CONTAINER_WEB_NAME} apt-get update
	docker exec -it ${CONTAINER_WEB_NAME} apt-get install -y python3 python3-pip
	docker exec -it ${CONTAINER_WEB_NAME} pip install -U sphinx sphinx_rtd_theme sphinxcontrib-phpdomain recommonmark
	docker exec -it ${CONTAINER_WEB_NAME} mkdir -p docs
	docker exec -it ${CONTAINER_WEB_NAME} ./vendor/bin/phpdoc-to-rst config

#
# Efetua a extração da documentação técnica para o formato 'rst'.
docs-extract:
	docker exec -it ${CONTAINER_WEB_NAME} ./vendor/bin/phpdoc-to-rst generate docs src --public-only





#
# Mostra log resumido do git
# Use o parametro 'len' para indicar a quantidade de itens a serem mostrados.
#
# O workaround abaixo se deve ao fato que o operador <<< não funciona em condições
# normais do 'Makefile' mesmo quando é setado SHELL=/bin/bash.
# O comando abaixo deveria ser apenas 1 linha como a seguinte:
# column -e -t -s "|" <<< $(git log -3 --pretty='format:%ad | %s' --reverse --date=format:'%d %B | %H:%M')
#
LOG_LENGTH=10
git-log:
	if [ -z "${len}" ]; then \
		git log -${LOG_LENGTH} --pretty='format:%ad | %s' --reverse --date=format:'%d %B | %H:%M' > .tmplogdata; \
	else \
		git log -${len} --pretty='format:%ad | %s' --reverse --date=format:'%d %B | %H:%M' > .tmplogdata; \
	fi;
	# Sem esta linha extra o comando 'column' apresenta um erro de 'line too long'
	echo "" >> .tmplogdata
	column .tmplogdata -e -t -s "|"
	rm .tmplogdata
	





#
# Mostra qual a tag atual do projeto.
tag:
	git describe --abbrev=0 --tags

#
# Atualiza o 'patch' da tag atualmente definida 
# para a branch principal 'main'.
tag-update:
	./tag-update.sh "version" "patch"

#
# Atualiza o 'minor version'  da tag atualmente definida 
# para a branch principal 'main'.
tag-update-minor:
	./tag-update.sh "version" "minor"

#
# Atualiza o 'major version'  da tag atualmente definida 
# para a branch principal 'main'.
tag-update-major:
	./tag-update.sh "version" "major"

#
# Atualiza a 'stability' da tag atualmente definida 
# para a branch principal 'main'.
#
# Use o parametro 'stability' para indicar qual será a nova 'stability'.
# use apenas um dos seguintes valores: 'alpha'; 'beta'; 'cr'; 'r'
tag-stability:
	./tag-update.sh "stability" "${stability}"
