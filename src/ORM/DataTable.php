<?php
declare (strict_types=1);

namespace AeonDigital\ORM;

use AeonDigital\Interfaces\DAL\iDAL as iDAL;
use AeonDigital\Interfaces\ORM\iTable as iTable;
use AeonDigital\DataModel\Abstracts\aModel as aModel;






/**
 * Classe que representa uma tabela de dados.
 *
 * @package     AeonDigital\ORM
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2020, Rianna Cantarelli
 * @license     MIT
 */
class DataTable extends aModel implements iTable
{





    private ?iDAL $DAL = null;
    /**
     * Define o objeto ``iDAL`` a ser usado para executar as instruções ``CRUD`` desta
     * tabela.
     *
     * Deve ser definido apenas 1 vez.
     *
     * @param       iDAL $DAL
     *              Objeto DAL a ser usado.
     *
     * @return      void
     */
    public function setDAL(iDAL $DAL) : void
    {
        if ($this->DAL === null) {
            $this->DAL = $DAL;
        }
    }





    /**
     * Nome abreviado da tabela de dados.
     *
     * @var         ?string
     */
    private ?string $alias = null;
    /**
     * Nome abreviado da tabela de dados.
     * Usado para evitar ambiguidades entre as colunas desta e de outras tabelas de
     * dados.
     *
     * @return      string
     */
    public function getAlias() : string
    {
        return $this->alias;
    }





    /**
     * Propriedade que traz um array contendo as instruções que devem ser executadas após a
     * tabela de dados ser criada.
     *
     * @var         ?array
     */
    private ?array $executeAfterCreateTable = null;
    /**
     * Retorna um array contendo as instruções que devem ser executadas após a tabela de
     * dados ser criada.
     *
     * @return      ?array
     */
    public function getExecuteAfterCreateTable() : ?array
    {
        return $this->executeAfterCreateTable;
    }





    /**
     * Array de arrays contendo em cada qual uma coleção de nomes de colunas de
     * dados desta mesma tabela. Cada conjunto de nomes irá corresponder a uma constraint
     * do tipo unique composta.
     *
     * @var         ?array
     */
    private ?array $uniqueMultipleKeys = null;
    /**
     * Retorna um array de arrays contendo em cada qual uma coleção de nomes de colunas de
     * dados desta mesma tabela. Cada conjunto de nomes irá corresponder a uma constraint
     * do tipo unique composta.
     *
     * @return      ?array
     */
    public function getUniqueMultipleKeys() : ?array
    {
        return $this->uniqueMultipleKeys;
    }











    /**
     * Indica quando a instância iniciou um bloco de transação.
     * Isto significa que apenas ela deve poder encerrar a transação dando um ``rollback`` ou o
     * commit dos dados.
     *
     * @var         bool
     */
    private bool $isTransactionOwner = false;
    /**
     * Identifica se a conexão com o banco de dados está em modo de transação. Se não estiver,
     * tenta iniciar.
     *
     * @codeCoverageIgnore
     *
     * @return      bool
     */
    private function openTransactionIfClosed() : bool
    {
        $r = $this->DAL->inTransaction();
        if ($r === false) {
            $r = $this->DAL->beginTransaction();
            if ($r === true) {
                $this->isTransactionOwner = true;
            }
        }
        return $r;
    }
    /**
     * Executa o ``rollback`` de uma transação em aberto e encerra a mesma.
     * Esta ação será executada apenas se esta própria instância for a dona da
     * transaction.
     *
     * @codeCoverageIgnore
     *
     * @return      bool
     *
     * @throws      \Exception
     *              Caso a transação não esteja aberta.
     *              Caso o rollBack falhe por qualquer motivo.
     */
    private function executeRollBackAndCloseTransaction() : bool
    {
        $r = $this->DAL->inTransaction();
        if ($r === true && $this->isTransactionOwner === true) {
            $r = $this->DAL->rollBack();
            $this->isTransactionOwner = false;
            if ($r === false) {
                $msg = "Could not execute rollback.";
                throw new \Exception($msg);
            }
        }
        return $r;
    }
    /**
     * Executa o ``commit`` das transações realizadas e encerra a mesma.
     * Esta ação será executada apenas se esta própria instância for a dona da
     * transaction.
     *
     * @codeCoverageIgnore
     *
     * @return      bool
     *
     * @throws      \Exception
     *              Caso a transação não esteja aberta.
     *              Caso o commit falhe por qualquer motivo.
     */
    private function executeCommitAndCloseTransaction() : bool
    {
        $r = $this->DAL->inTransaction();
        if ($r === false) {
            $msg = "There is no open transaction to be closed.";
            throw new \Exception($msg);
        } else {
            if ($this->isTransactionOwner === true) {
                $r = $this->DAL->commit();
                $this->isTransactionOwner = false;
                if ($r === false) {
                    $msg = "Data commit can not be executed.";
                    throw new \Exception($msg);
                }
            }
        }
        return $r;
    }










    /**
     * Traz inúmeros dados pré-processados para simplificar o uso desta instância. É melhor
     * definido quando utilizada uma factory que providencie as informações a serem utilizadas.
     *
     * @var         ?array
     */
    private ?array $ormInstructions = null;
    /**
     * Retorna um array associativo contendo o valor atual de cada uma das colunas de dados
     * desta tabela contendo os respectivos valores já em formato de armazenamento para serem
     * usados em uma instrução SQL de ``INSERT`` ou ``UPDATE``.
     *
     * @param       ?string $parentTableName
     *              Se definido, deve ser o nome do modelo de dados ao qual o objeto atual deve
     *              ser associado.
     *
     * @param       ?int $parentId
     *              Id do objeto pai ao qual este registro deve estar associado.
     *
     * @return      array
     */
    private function retrieveRowData(
        ?string $parentTableName = null,
        ?int $parentId = null
    ) : array {
        $arr = [];

        // Resgata os dados comuns.
        foreach ($this->ormInstructions["oColumn"] as $cName => $oColumn) {
            $arr[$cName] = $oColumn->getStorageValue();
        }

        // Adiciona os dados do objeto Parent
        if ($parentTableName !== null && $parentId !== null) {
            $useTableName = $parentTableName . "_Id";
            $arr[$useTableName] = $parentId;
        }

        return $arr;
    }





    /**
     * Retorna a mensagem de erro referente a última instrução SQL executada internamente
     * pela conexão com o banco de dados.
     * Não havendo erro, retorna ``null``.
     *
     * @return      ?string
     */
    public function getLastDALError() : ?string
    {
        return $this->DAL->getLastError();
    }



    /**
     * Retorna o total de registros existentes nesta tabela de dados.
     *
     * @return      int
     */
    public function countRows() : int
    {
        return $this->DAL->countRowsFrom($this->getName(), "Id");
    }



    /**
     * Identifica se existe na tabela de dados um registro com o Id indicado.
     *
     * @param       int $Id
     *              Id do objeto.
     *
     * @return      bool
     */
    public function hasId(int $Id) : bool
    {
        return $this->DAL->hasRowsWith($this->getName(), "Id", $Id);
    }



    /**
     * Insere ou atualiza os dados da instância atual no banco de dados.
     *
     * @param       ?string $parentTableName
     *              Se definido, deve ser o nome do modelo de dados ao qual o objeto atual
     *              deve ser associado.
     *
     * @param       ?int $parentId
     *              Id do objeto pai ao qual este registro deve estar associado.
     *
     * @return      bool
     *              Retornará ``true`` caso esta ação tenha sido bem sucedida.
     */
    public function save(
        ?string $parentTableName = null,
        ?int $parentId = null
    ) : bool {
        $r = $this->isValid();

        // Apenas se a instância atual estiver validada.
        if ($r === true) {
            $rowData = $this->retrieveRowData($parentTableName, $parentId);

            // Inicía um bloco transaction se ele ainda não foi aberto.
            $this->openTransactionIfClosed();


            // ANTES
            // de salvar este próprio objeto, efetua a persistência
            // das relações 1-1 para obter os Ids.
            foreach ($this->ormInstructions["singleFK"] as $cNameFK => $oColumnFK) {
                if ($r === true && $oColumnFK->isInitial() === false) {
                    $r = $oColumnFK->getInstanceValue()->save();
                    if ($r === true) {
                        $rowData[$oColumnFK->getModelName() . "_Id"] = $oColumnFK->getInstanceValue()->Id;
                    }
                }
            }



            // Não havendo falhas até aqui...
            if ($r === true) {

                // Se o objeto não possui um Id definido,
                // então deve ser inserido e um novo Id será resgatado
                if ($this->Id === 0) {
                    $r = $this->DAL->insertInto($this->getName(), $rowData);

                    if ($r === true) {
                        $Id = $this->DAL->getLastPK($this->getName(), "Id");
                        if ($Id === null) {
                            $r = false;
                        } else {
                            $this->Id = $Id;
                        }
                    }
                }
                // Se o Id do objeto já está definido, então atualiza
                // o registro no banco de dados.
                else {
                    $rowData["Id"] = $this->Id;
                    $r = $this->DAL->updateSet($this->getName(), $rowData, "Id");
                }


                // Tendo salvo o objeto principal...
                if ($r === true) {
                    // Salva as coleções de objetos filhos
                    foreach ($this->ormInstructions["collectionFK"] as $cNameFK => $oColumnFK) {
                        if ($r === true) {
                            $collection = $oColumnFK->getInstanceValue();

                            // para cada item na coleção
                            foreach ($collection as $childInstance) {
                                // Enquanto não houver erros e apenas se o objeto não estiver
                                // em seu estado inicial
                                if ($r === true && $childInstance->isInitial() === false) {
                                    // Se for uma coleção "1-N"
                                    if ($oColumnFK->isFKLinkTable() === false) {
                                        $r = $childInstance->save($this->getName(), $this->Id);
                                    }
                                    // Senão, se for uma coleção "N-N"
                                    else {
                                        $isNew = ($childInstance->Id === 0);
                                        $r = $childInstance->save();

                                        // Tendo salvo o objeto com sucesso
                                        // e ele sendo um novo item
                                        if ($r === true && $isNew === true) {
                                            // Resgata o Id do novo objeto e realiza o vínculo
                                            // dos dois na LinkTable alvo.
                                            $fkId = $this->DAL->getLastPK($oColumnFK->getModelName(), "Id");

                                            $ltName     = $this->ormInstructions["selectChild"][$cNameFK]["linkTableName"];
                                            $ltColumns  = $this->ormInstructions["selectChild"][$cNameFK]["linkTableColumns"];
                                            $ltPK       = $ltColumns[0];
                                            $ltFK       = $ltColumns[1];
                                            $ltRowData  = [
                                                "$ltPK" => $this->Id,
                                                "$ltFK" => $fkId,
                                            ];
                                            $r = $this->DAL->insertInto($ltName, $ltRowData);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }



            // Havendo qualquer erro durante o processo efetua o
            // rollback e encerra o bloco transaction
            if ($r === false) {
                $this->executeRollBackAndCloseTransaction();
            } else {
                $this->executeCommitAndCloseTransaction();
            }
        }

        return $r;
    }
    /**
     * Insere os dados desta instância em um novo registro no banco de dados.
     *
     * Se este objeto já possui um Id definido esta ação irá falhar.
     *
     * @param       ?string $parentTableName
     *              Se definido, deve ser o nome do modelo de dados ao qual o objeto atual
     *              deve ser associado.
     *
     * @param       ?int $parentId
     *              Id do objeto pai ao qual este registro deve estar associado.
     *
     * @return      bool
     *              Retornará ``true`` caso esta ação tenha sido bem sucedida.
     */
    public function insert(
        ?string $parentTableName = null,
        ?int $parentId = null
    ) : bool {
        if ($this->Id === 0) {
            return $this->save($parentTableName, $parentId);
        } else {
            return false;
        }
    }
    /**
     * Atualiza os dados desta instância em um novo registro no banco de dados.
     *
     * Se este objeto não possui um Id definido esta ação irá falhar.
     *
     * @param       ?string $parentTableName
     *              Se definido, deve ser o nome do modelo de dados ao qual o objeto atual
     *              deve ser associado.
     *
     * @param       ?int $parentId
     *              Id do objeto pai ao qual este registro deve estar associado.
     *
     * @return      bool
     *              Retornará ``true`` caso esta ação tenha sido bem sucedida.
     */
    public function update(
        ?string $parentTableName = null,
        ?int $parentId = null
    ) : bool {
        if ($this->Id !== 0) {
            return $this->save($parentTableName, $parentId);
        } else {
            return false;
        }
    }





    /**
     * Carrega esta instância com os dados do registro de Id informado.
     *
     * @param       int $Id
     *              Id do registro que será carregado.
     *
     * @param       bool $loadChilds
     *              Quando ``true`` irá carregar todos os objetos que são filhos diretos
     *              deste.
     *
     * @return      bool
     */
    public function select(
        int $Id,
        bool $loadChilds = false) : bool
    {
        $r = false;

        if ($Id > 0) {
            $rowData = $this->DAL->getDataRow($this->ormInstructions["select"], ["Id" => $Id]);

            if ($rowData !== null) {
                $rowData["Id"] = $Id;
                $r = $this->setValues($rowData);

                if ($r === true &&
                    $loadChilds === true &&
                    $this->ormInstructions["selectChild"] !== [])
                {
                    foreach ($this->ormInstructions["selectChild"] as $cName => $sData) {
                        if ($r === true) {
                            $r = $this->loadChild($cName);
                        }
                    }
                }
            }
        }

        return $r;
    }





    /**
     * Retornará o Id do objeto PAI da instância atual na tabela de dados indicada no
     * parametro ``$tableName``.
     *
     * Apenas funcionará para os objetos FILHOS em relações ``1-1`` e ``1-N``.
     *
     * @param       string $tableName
     *              Nome da tabela de dados do objeto pai.
     *
     * @return      ?int
     */
    public function selectParentIdOf(string $tableName) : ?int
    {
        $tgtId = null;

        if ($this->Id !== 0 && isset($this->ormInstructions["selectParentId"][$tableName]) === true) {
            $strSQL = $this->ormInstructions["selectParentId"][$tableName];
            $tgtId = $this->DAL->getDataColumn($strSQL, ["thisId" => $this->Id], "int");
        }

        return $tgtId;
    }





    /**
     * Remove o objeto atual do banco de dados.
     * Irá limpar totalmente os objetos filhos substituindo-os por instâncias vazias, ou
     * por coleções vazias.
     *
     * @return      bool
     */
    public function delete() : bool
    {
        $r = false;

        if ($this->Id !== 0) {
            $r = $this->DAL->deleteFrom($this->getName(), "Id", $this->Id);
            if ($r === true) {

                $this->Id = 0;

                // Limpa desta instância os objetos que
                // fazem uma relação "1-1"
                foreach ($this->ormInstructions["singleFK"] as $cNameFK => $oColumnFK) {
                    if ($oColumnFK->isAllowNull() === true) {
                        $r = $oColumnFK->setValue(null);
                    } else {
                        $oColumnFK->setValue($oColumnFK->getModel());
                    }
                }

                // Limpa desta instância os objetos que representam
                // vínculos em coleções de dados "1-N" e "N-N"
                foreach ($this->ormInstructions["collectionFK"] as $cNameFK => $oColumnFK) {
                    $oColumnFK->setValue([]);
                }
            }
        }

        return $r;
    }





    /**
     * Permite definir o vínculo da instância atualmente carregada a um de seus possíveis
     * relacionamentos indicados nos modelos de dados.
     *
     * @param       string $tableName
     *              Nome da tabela de dados com a qual esta instância passará a ter um
     *              vínculo referencial.
     *
     * @param       int $tgtId
     *              Id do registro da tabela de dados alvo onde este vinculo será firmado.
     *
     * @return      bool
     */
    public function attachWith(string $tableName, int $tgtId) : bool
    {
        $r = false;

        if ($this->Id !== 0 && isset($this->ormInstructions["attachWith"][$tableName]) === true) {
            $execData = [
                "thisId"    => $this->Id,
                "tgtId"     => $tgtId
            ];

            $strSQL = $this->ormInstructions["attachWith"][$tableName];
            $r = $this->DAL->executeInstruction($strSQL, $execData);
        }

        return $r;
    }



    /**
     * Remove o vínculo existente entre este registro e um outro da tabela de dados.
     *
     * O funcionamento deste método depende da *posição* no relacionamento em que a
     * instrução está sendo executada e varia conforme a presença ou não do parametro
     * ``$tgtId``.
     *
     * - Em relações 1-1:
     *   O funcionamento é igual independente da posição em que a instrução está sendo
     *   executada.
     *   Não é preciso definir o parametro ``$tgtId``.
     *   A chave extrangeira será anulada.
     *
     * - Em relações 1-N:
     *   - A partir da instância PAI:
     *     Definindo ``$tgtId``:
     *     Apenas o objeto FILHO de ``$tgtId`` especificado terá seu vínculo desfeito.
     *     Omitindo ``$tgtId``:
     *     TODOS os objetos FILHOS da instância atual perderão seu vínculo.
     *
     *   - A partir da instância FILHA:
     *     Não é preciso definir o parametro ``$tgtId``.
     *     A chave extrangeira será anulada.
     *
     * - Em relações N-N
     *   Independente do lado:
     *   Definindo ``$tgtId``:
     *   Irá remover o vínculo existente entre ambos registros
     *   Omitindo ``$tgtId``:
     *   TODOS os vínculos entre a instância atual e TODOS os demais serão desfeitos.
     *
     * @param       string $tableName
     *              Nome da tabela de dados com a qual esta instância irá romper um vínculo
     *              existente.
     *
     * @param       ?int $tgtId
     *              Id do registro da tabela de dados.
     *
     * @return      bool
     */
    public function detachWith(string $tableName, ?int $tgtId = null) : bool
    {
        $r = false;

        if ($this->Id !== 0 && isset($this->ormInstructions["detachWith"][$tableName]) === true) {
            $useSql = (($tgtId === null) ? "detachWithAll" : "detachWith");
            $strSQL = $this->ormInstructions[$useSql][$tableName];

            $execData = [];
            if (\strpos($strSQL, ":thisId") !== false) {
                $execData["thisId"] = $this->Id;
            }
            if (\strpos($strSQL, ":tgtId") !== false) {
                $execData["tgtId"] = $tgtId;
            }

            $r = $this->DAL->executeInstruction($strSQL, $execData);
        }

        return $r;
    }










    /**
     * Inicia uma nova tabela de dados.
     *
     * @param       array $config
     *              Array associativo com as configurações para esta tabela de dados.
     *
     * ``` php
     *      $arr = [
     *          string          "tableName"
     *          Nome da tabela de dados.
     *
     *          string          "alias"
     *          Nome abreviado da tabela de dados.
     *
     *          string          "description"
     *          Descrição da tabela de dados. (opcional)
     *
     *          array           "executeAfterCreateTable"
     *          Coleção de instruções a serem executadas após a tabela de dados
     *          ser definida.
     *
     *          array           "uniqueMultipleKeys"
     *          Array de arrays contendo em cada qual uma coleção de nomes de colunas de
     *          dados desta mesma tabela.
     *
     *          iColumn[]       "columns"
     *          Array contendo as instâncias das colunas de dados que devem
     *          compor este tabela de dados.
     *
     *          array           "ormInstructions"
     *          Coleção de instruções SQL usadas por esta instância para
     *          carregar seus próprios dados e de seus objetos filhos.
     *      ];
     * ```
     *
     * @throws      \InvalidArgumentException
     *              Caso algum valor passado não seja válido.
     */
    function __construct(array $config)
    {
        // Resgata as propriedades definidas
        $tableName                  = ((isset($config["tableName"]) === true)               ? $config["tableName"]                  : null);
        $alias                      = ((isset($config["alias"]) === true)                   ? $config["alias"]                      : null);
        $executeAfterCreateTable    = ((isset($config["executeAfterCreateTable"]) === true) ? $config["executeAfterCreateTable"]    : null);
        $uniqueMultipleKeys         = ((isset($config["uniqueMultipleKeys"]) === true)      ? $config["uniqueMultipleKeys"]         : null);
        $columns                    = ((isset($config["columns"]) === true)                 ? $config["columns"]                    : false);
        $ormInstructions            = ((isset($config["ormInstructions"]) === true)         ? $config["ormInstructions"]            : null);


        // Verifica se o "alias" é válido
        if (\is_string($alias) === false || $alias === null || $alias === "") {
            $msg = "Every data table must have an unique and valid alias.";
            throw new \InvalidArgumentException($msg);
        }

        // Identifica se o parametro "executeAfterCreateTable" é válido.
        if ($executeAfterCreateTable !== null &&
            (   \is_array($executeAfterCreateTable) === false ||
                \array_is_assoc($executeAfterCreateTable) === true ||
                $executeAfterCreateTable === []))
        {
            $msg = "Invalid value defined for \"executeAfterCreateTable\". Expected non empty array of strings.";
            throw new \InvalidArgumentException($msg);
        }

        // Identifica se o parametro "ormInstructions" é válido.
        if (\array_is_assoc($ormInstructions) === false ||
            \key_exists("select", $ormInstructions) === false ||
            \key_exists("selectChild", $ormInstructions) === false)
        {
            $msg = "Invalid value defined for \"ormInstructions\".";
            throw new \InvalidArgumentException($msg);
        }


        $this->alias                    = $alias;
        $this->executeAfterCreateTable  = $executeAfterCreateTable;
        $this->uniqueMultipleKeys       = $uniqueMultipleKeys;


        // Renomeia as chaves usadas de acordo com as
        // exigências da classe "aModel".
        if ($tableName !== null)    { $config["name"]   = $tableName; }
        if ($columns !== null)      { $config["fields"] = $columns; }

        parent::__construct($config);



        $allColumnNames = $this->getFieldNames();
        foreach ($allColumnNames as $cName) {
            if ($cName !== "Id") {
                $oColumn = $this->getField($cName);

                if ($oColumn->isReference() === false) {
                    $ormInstructions["oColumn"][$cName] = $oColumn;
                } else {
                    $ormInstructions["selectChild"][$cName]["oColumnFK"] = $oColumn;

                    if ($oColumn->isCollection() === false) {
                        $ormInstructions["singleFK"][$cName] = $oColumn;
                    } else {
                        $ormInstructions["collectionFK"][$cName] = $oColumn;
                    }
                }
            }
        }

        $this->ormInstructions = $ormInstructions;
    }










    /**
     * Carrega os dados de uma coluna que seja usada como referência para registros de outras
     * colunas.
     *
     * @param       string $columnName
     *              Nome da coluna de dados que faz o vínculo entre este registro e as
     *              instâncias filhas.
     *
     * @return      bool
     */
    private function loadChild(string $columnName) : bool
    {
        $r = false;

        if ($this->Id !== 0 &&
            isset($this->ormInstructions["selectChild"][$columnName]) === true)
        {
            $strSQL = $this->ormInstructions["selectChild"][$columnName]["select"];
            $field = $this->getField($columnName);

            // Sendo uma relação 1-1
            if ($field->isCollection() === false) {
                $fkId  = $this->DAL->getDataColumn($strSQL, ["Id" => $this->Id], "int");

                // Encontrando o Id, carrega-o
                if ($fkId !== null) {
                    $childInstance = $field->getModel();
                    $r = $childInstance->select($fkId);
                    if ($r === true) {
                        $r = $field->setValue($childInstance);
                    }
                }
            }
            // Tratando-se de uma coleção de objetos
            else {
                $fkIds  = $this->DAL->getDataTable($strSQL, ["Id" => $this->Id]);

                // Encontrando os Ids alvos...
                if ($fkIds !== null) {
                    $r = true;
                    $childInstances = [];

                    foreach ($fkIds as $row) {
                        if ($r === true) {
                            $childInstance = $field->getModel();
                            $r = $childInstance->select((int)$row["fkId"]);
                            $childInstances[] = $childInstance;
                        }
                    }

                    if ($r === true) {
                        $r = $field->setValue($childInstances);
                    }
                }
            }
        }

        return $r;
    }
    /**
     * Exclui definitivamente todas as instâncias vinculadas ao registro atualmente
     * carregado.
     *
     * @param       string $columnName
     *              Nome da coluna de dados que possui as instâncias que serão excluídas
     *
     * @return      bool
     */
    private function deleteChild(string $columnName) : bool
    {
        $r = false;

        if (isset($this->ormInstructions["selectChild"][$columnName]) === true) {
            $field = $this->getField($columnName);

            // Sendo uma relação 1-1
            if ($field->isCollection() === false) {
                $inst = $field->getInstanceValue();

                if (\is_object($inst) === true && $inst->Id !== 0) {
                    $this->openTransactionIfClosed();
                    $r = $this->detachWith($field->getModelName(), $inst->Id);

                    if ($r === false) {
                        $this->executeRollBackAndCloseTransaction();
                    }
                    else {
                        $r = $inst->delete();

                        // Ocorrendo algum erro, executa o rollback
                        if ($r === false) {
                            $this->executeRollBackAndCloseTransaction();
                        }
                        // Excluindo com êxito, efetua o commit e reseta o campo
                        else {
                            $this->executeCommitAndCloseTransaction();
                            $nval = (($field->isAllowNull() === true) ? null : $field->getModel());
                            $field->setValue($nval);
                        }
                    }
                }
            }
            // Sendo uma relação 1-N ou N-N
            else {
                $collection = $field->getInstanceValue();

                if (count($collection) > 0) {
                    $this->openTransactionIfClosed();
                    $r = $this->detachWith($field->getModelName());

                    if ($r === true) {
                        foreach ($collection as $inst) {
                            if ($r === true && $inst->Id !== 0) {

                                if ($r === true) {
                                    $r = $inst->delete();
                                }
                            }
                        }

                        if ($r === false) {
                            $this->executeRollBackAndCloseTransaction();
                        }
                        else {
                            $this->executeCommitAndCloseTransaction();
                            $field->setValue([]);
                        }
                    }
                }
            }
        }

        return $r;
    }





    /**
     * Método que deve ser definido nas classes concretas e que permitem expandir o uso do
     * método ``__call``.
     *
     * @param       string $name
     *              Nome do método.
     *              É preciso ter o prefixo ``new`` e o nome do campo que será automaticamente
     *              definido.
     *
     * @param       array $arguments
     *              Opcionalmente pode ser definido uma coleção de valores a serem definidos
     *              para a nova instância.
     *
     * @return      void|mixed
     */
    protected function extendCall($name, $arguments)
    {
        if (\mb_str_starts_with($name, "load") === true) {
            $this->useMainCall = false;
            $useName = \substr($name, 4);

            $this->throwErrorIfFieldDoesNotExists($useName);
            return $this->loadChild($useName);
        }
        elseif (\mb_str_starts_with($name, "delete") === true) {
            $this->useMainCall = false;
            $useName = \substr($name, 6);

            $this->throwErrorIfFieldDoesNotExists($useName);
            return $this->deleteChild($useName);
        }
        else {
            $this->useMainCall = true;
            return true;
        }
    }
}
