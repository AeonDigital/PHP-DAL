# Para LinkTable
  Adicionar a capacidade de atualizar o projeto, redefinir o schema e manter uma cópia de
    cada versão redefinida.

  Adicionar a capacidade de, definir colunas especiais dentro de uma linktable.
    Nestes casos, em ambos lados das correlações, as colunas extras assim adicionadas deverão
    ser carregadas em conjunto tanto no objeto principal quanto no filho.

  Verificar casos em que uma FK não pode ser null.
    Isto exigirá que o objeto filho (ex: UsuarioDoDominio->SessaoDeAcesso) seja persistido ANTES
    do objeto pai.

  Verificar por que uma coluna do tipo REAL não aceitou o valor 'default' definido.
    Este teste pode ser feito usando a tabela "UsuarioDoDominio".
