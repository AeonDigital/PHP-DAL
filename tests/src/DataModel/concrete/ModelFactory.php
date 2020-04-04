<?php
declare (strict_types=1);

namespace AeonDigital\DataModel\Tests\Concrete;

use AeonDigital\Interfaces\DataModel\iModelFactory as iModelFactory;
use AeonDigital\Interfaces\DataModel\iModel as iModel;
use AeonDigital\DataModel\Tests\Concrete\DataField as DataField;
use AeonDigital\DataModel\Tests\Concrete\DataFieldCollection as DataFieldCollection;
use AeonDigital\DataModel\Tests\Concrete\DataFieldModelCollection as DataFieldModelCollection;
use AeonDigital\DataModel\Tests\Concrete\DataModel as DataModel;



/**
 * Fábrica de modelos de dados para os testes unitários
 *
 * @package     AeonDigital\DataModel
 * @version     0.9.0 [alpha]
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   GNUv3
 */
class ModelFactory implements iModelFactory
{





    /**
     * Identifica se esta fábrica pode fornecer um objeto
     * compatível com o nome do Identificador passado.
     *
     * @param       string $idName
     *              Identificador único do modelo de
     *              dados dentro do escopo definido.
     *
     * @return      bool
     */
    public function hasDataModel(string $idName) : bool
    {
        return (key_exists($idName, $this->modelsConfig) === true);
    }



    /**
     * Retorna um objeto "iModel" com as configurações
     * equivalentes ao identificador único do mesmo.
     *
     * @param       string $idName
     *              Identificador único do modelo de
     *              dados dentro do escopo definido.
     *
     * @param       mixed $initialValues
     *              Coleção de valores a serem setados para
     *              a nova instância que será retornada.
     *
     * @return      iModel
     */
    public function createDataModel(string $idName, $initialValues = null) : iModel
    {
        if ($this->hasDataModel($idName) === true) {
            $useConfig = $this->modelsConfig[$idName];

            $useFields = [];
            foreach ($useConfig["fields"] as $fieldConfig) {
                $isReference    = (key_exists("modelName", $fieldConfig) === true);


                // Sendo um modelo de dados simples...
                if ($isReference === false) {
                    $type           = $fieldConfig["type"];
                    $isCollection   = (strpos($type, "[]") !== false);

                    if ($isCollection === false) {
                        $useFields[] = new DataField($fieldConfig);
                    } else {
                        $fieldConfig["type"] = str_replace("[]", "", $type);
                        $useFields[] = new DataFieldCollection($fieldConfig);
                    }
                } else {
                    $modelName      = $fieldConfig["modelName"];
                    $isCollection   = (strpos($modelName, "[]") !== false);

                    if ($isCollection === false) {
                        $useFields[] = new DataFieldModel($fieldConfig, $this);
                    } else {
                        $fieldConfig["modelName"] = str_replace("[]", "", $modelName);
                        $useFields[] = new DataFieldModelCollection($fieldConfig, $this);
                    }
                }
            }

            $useConfig["fields"] = $useFields;
            $nInst = new DataModel($useConfig);

            if ($initialValues !== null) {
                $nInst->setValues($initialValues);
            }
            return $nInst;
        }
    }




    private $modelsConfig = [
        "Aplicacao" => [
            "name"  => "Aplicacao",
            "description" => "Definição da aplicação",
            "fields" => [
                [
                    "name" => "Nome",
                    "type" => "String",
                    "length" => 16,
                    "allowNull" => false,
                    "allowEmpty" => false
                ],
                [
                    "name" => "Descricao",
                    "type" => "String",
                    "length" => 128,
                    "allowNull" => false,
                    "allowEmpty" => false
                ],
                [
                    "name" => "CNPJ",
                    "type" => "String",
                    "inputFormat" => "Brasil.CNPJ"
                ]
            ]
        ],
        "FormularioDeContato" => [
            "name"  => "FormularioDeContato",
            "description" => "Formulário de contato",
            "fields" => [
                [
                    "name" => "Nome",
                    "type" => "String",
                    "length" => 128,
                    "allowNull" => false,
                    "allowEmpty" => false
                ],
                [
                    "name" => "Email",
                    "type" => "String",
                    "length" => 64,
                    "allowNull" => false,
                    "allowEmpty" => false
                ],
                [
                    "name" => "Destinatarios",
                    "type" => "String[]",
                    "length" => 64
                ]
            ]
        ],
        "Usuario" => [
            "name"  => "Usuario",
            "description" => "Usuário do sistema",
            "fields" => [
                [
                    "name" => "Nome",
                    "type" => "AeonDigital\\SimpleType\\stString",
                    "length" => 128,
                    "allowNull" => false,
                    "allowEmpty" => false
                ],
                [
                    "name" => "Login",
                    "type" => "AeonDigital\\SimpleType\\stString",
                    "length" => 128,
                    "allowNull" => false,
                    "allowEmpty" => false
                ],
                [
                    "name" => "GrupoDeSeguranca",
                    "description" => "Grupo de perfil de segurança que o usuário está apto a utilizar.",
                    "modelName" => "GrupoDeSeguranca",
                ]
            ]
        ],
        "GrupoDeSeguranca" => [
            "name" => "GrupoDeSeguranca",
            "description" => "Grupos de perfis de segurança para controle de acesso dos usuários.",
            "fields" => [
                [
                    "name" => "ApplicationName",
                    "type" => "AeonDigital\\SimpleType\\stString",
                    "length" => 32,
                    "allowNull" => false,
                    "allowEmpty" => false
                ],
                [
                    "name" => "NomeDoGrupo",
                    "type" => "AeonDigital\\SimpleType\\stString",
                    "length" => 32,
                    "allowNull" => false,
                    "allowEmpty" => false
                ],
                [
                    "name" => "Usuarios",
                    "description" => "Usuários que pertencem a este grupo de segurança.",
                    "modelName" => "Usuario[]",
                ]
            ]
        ]
    ];





    /**
     * Construtor de uma nova fábrica de
     * modelos de dados
     */
    function __construct() { }
}
