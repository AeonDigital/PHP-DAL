#!/usr/bin/env bash
# myShellEnv v 1.0 [aeondigital.com.br]







#
# Carrega as ferramentas de uso geral
. "${PWD}/make/makeEnvironment.sh"
. "${PWD}/Shell-Make/assets/standalone.sh"
. "${PWD}/Shell-Make/assets/makeTools.sh"





#
# Ação executada imediatamente ANTES cada comando 'make'.
#
# @param string $1
# Recebe o nome do comando que está sendo executado.
#
makeExecuteBefore() {
  if [ "$1" == "up" ] && [ ! -f "./composer.lock" ]; then
    echo "{}" > "./composer.lock"
  fi
}





#
# Ação executada imediatamente ANTES cada comando 'make'.
#
# @param string $1
# Recebe o nome do comando que está sendo executado.
#
makeExecuteAfter() {
  local doNothing=""
}





#
# Permite evocar uma função deste script a partir de um argumento passado ao chamá-lo.
$*
