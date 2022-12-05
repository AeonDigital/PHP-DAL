#
# Aeon Digital
# Rianna Cantarelli <rianna@aeondigital.com.br>
.SILENT:



#
# Módulos e submódulos
include Shell-Make/Makefile

#
# Roda exclusivamente o servidor web com configurações padrões,
# sem o uso do docker-compose
#run-web-server-only:
#	docker run --rm -p 8080:80 --env-file "./container-config/apache-php-8.2/etc/.env" --name "${CONTAINER_WEBSERVER_NAME}" aeondigital/apache-php-8.2:dev

#
# Roda exclusivamente o servidor de banco de dados com as configurações padrões,
# sem o uso do docker-compose
#run-db-server-only:
#	docker run --rm -v "./container-config/mysql-8.0/etc/mysql":"/etc/mysql" --name "${CONTAINER_DBSERVER_NAME}" aeondigital/mysql-8.0:dev