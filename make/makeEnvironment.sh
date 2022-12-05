#!/usr/bin/env bash
# myShellEnv v 1.0 [aeondigital.com.br]


#
# Configuração do MSE
MSE_GLOBAL_THEME_NAME="mse_inter_theme_default"


#
# Variáveis para comandos Makefile
MK_ROOT_PATH="${PWD}"
MK_WEB_SERVER_DATABASE_BOOTSTRAP_FILE="/etc/database/bootstrap.sql"

MK_WEB_SERVER_ENV_FILE="${MK_ROOT_PATH}/container-config/apache-php-8.2/etc/.env"
MK_LOCAL_BOOTSTRAP_FILE="${MK_ROOT_PATH}/container-config/apache-php-8.2${MK_WEB_SERVER_DATABASE_BOOTSTRAP_FILE}"
MK_LOCAL_CONTAINER_ROOT_DIR="${MK_ROOT_PATH}/container-config/apache-php-8.2"


CONTAINER_WEBSERVER_NAME="dev-php-webserver"
CONTAINER_DBSERVER_NAME="dev-php-dbserver"

MK_GIT_DEFAULT_BRANCH="main"
MK_GIT_LOG_LENGTH="10"
MK_GIT_TASK_FINISH_MERGE="1"