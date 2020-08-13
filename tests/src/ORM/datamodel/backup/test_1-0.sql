-- MySQL dump 10.13  Distrib 8.0.12, for Win64 (x86_64)
--
-- Host: localhost    Database: test
-- ------------------------------------------------------
-- Server version	8.0.12

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8mb4 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cidade`
--

DROP TABLE IF EXISTS `cidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `cidade` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(128) NOT NULL COMMENT 'Nome da cidade.',
  `Estado` varchar(2) NOT NULL COMMENT 'Sigla do estado.',
  `Capital` tinyint(1) NOT NULL COMMENT 'Indica se a cidade é capital do seu estado.',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `uc_cid_Nome_Estado_Capital` (`Nome`,`Estado`,`Capital`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Coleção de cidades brasileiras.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cidade`
--

LOCK TABLES `cidade` WRITE;
/*!40000 ALTER TABLE `cidade` DISABLE KEYS */;
INSERT INTO `cidade` VALUES (1,'Teste','RS',1);
/*!40000 ALTER TABLE `cidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enderecopostal`
--

DROP TABLE IF EXISTS `enderecopostal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `enderecopostal` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `CEP` varchar(10) NOT NULL COMMENT 'Código de endereçamento postal.',
  `TipoDeEndereco` varchar(32) NOT NULL COMMENT 'Indica se o endereço é residencial, comercial, ou de outra natureza qualquer.',
  `TipoDeLogradouro` varchar(32) NOT NULL COMMENT 'Tipo de logradouro (rua, avenida, travessa...).',
  `Logradouro` varchar(128) NOT NULL COMMENT 'Nome do logradouro.',
  `Numero` int(11) NOT NULL COMMENT 'Número da residência.',
  `Complemento` varchar(128) DEFAULT NULL COMMENT 'Complemento do endereço.',
  `Bairro` varchar(128) NOT NULL COMMENT 'Nome do bairro.',
  `Referencia` varchar(255) DEFAULT NULL COMMENT 'Referência para o endereço.',
  `Cidade_Id` bigint(20) DEFAULT NULL COMMENT 'Cidade correlacionada com este EnderecoPostal',
  PRIMARY KEY (`Id`),
  KEY `fk_ep_to_cid_Cidade_Id` (`Cidade_Id`),
  CONSTRAINT `fk_ep_to_cid_Cidade_Id` FOREIGN KEY (`Cidade_Id`) REFERENCES `cidade` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Endereço postal.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enderecopostal`
--

LOCK TABLES `enderecopostal` WRITE;
/*!40000 ALTER TABLE `enderecopostal` DISABLE KEYS */;
/*!40000 ALTER TABLE `enderecopostal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupodeseguranca`
--

DROP TABLE IF EXISTS `grupodeseguranca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `grupodeseguranca` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Ativo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Indica se este grupo está ativo ou não.',
  `Aplicacao` varchar(32) NOT NULL COMMENT 'Nome da aplicação para a qual este grupo é utilizado.',
  `Nome` varchar(32) NOT NULL COMMENT 'Nome para este grupo de segurança.',
  `Descricao` varchar(255) DEFAULT NULL COMMENT 'Descrição do grupo de segurança.',
  `UseConnection` varchar(32) NOT NULL COMMENT 'Identificador da conexão com o banco de dados que será utilizado pelos usuários deste grupo.',
  `PoliticaPadrao` varchar(1) NOT NULL DEFAULT 'B' COMMENT 'Indica a politica de segurança comum para as rotas [b (block) | f (free)].',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Define um perfil de segurança para um conjunto de usuários.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupodeseguranca`
--

LOCK TABLES `grupodeseguranca` WRITE;
/*!40000 ALTER TABLE `grupodeseguranca` DISABLE KEYS */;
/*!40000 ALTER TABLE `grupodeseguranca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessaodeacesso`
--

DROP TABLE IF EXISTS `sessaodeacesso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `sessaodeacesso` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `DataDoLogin` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora do login.',
  `Login` varchar(64) NOT NULL COMMENT 'Login com o qual a sessão foi autenticada.',
  `Aplicacao` varchar(32) NOT NULL COMMENT 'Aplicação na qual o usuário efetuou o login.',
  `ProfileInUse` varchar(32) NOT NULL COMMENT 'Perfil de segurança do usuário sendo usado no momento.',
  `SessionTimeOut` datetime NOT NULL COMMENT 'Data e hora para o fim da sessão.',
  `Ip` varchar(64) NOT NULL COMMENT 'Ip do usuário no momento do login.',
  `Browser` varchar(256) NOT NULL COMMENT 'Identificação do nevegador do usuário no momento do login.',
  `Locale` varchar(5) NOT NULL DEFAULT 'pt-BR' COMMENT 'Locale do usuário.',
  `SessionRenew` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Indica se a sessão é renovada automaticamente a cada iteração do usuário.',
  `SessionID` varchar(160) NOT NULL COMMENT 'ID da sessão do usuário.',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `uc_sda_SessionID` (`SessionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Define uma sessão de acesso para um usuário que efetuou login.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessaodeacesso`
--

LOCK TABLES `sessaodeacesso` WRITE;
/*!40000 ALTER TABLE `sessaodeacesso` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessaodeacesso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `udd_to_gds`
--

DROP TABLE IF EXISTS `udd_to_gds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `udd_to_gds` (
  `GrupoDeSeguranca_Id` bigint(20) NOT NULL COMMENT 'GrupoDeSeguranca em UsuarioDoDominio',
  `UsuarioDoDominio_Id` bigint(20) NOT NULL COMMENT 'UsuarioDoDominio em GrupoDeSeguranca',
  `Padrao` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Informa se este é o Grupo de Segurança padrão para este usuário.',
  `Descricao` varchar(64) NOT NULL DEFAULT 'info' COMMENT 'Descrição sobre esta relação.',
  UNIQUE KEY `uc_udd_gds_UsuarioDoDominio_Id_GrupoDeSeguranca_Id` (`UsuarioDoDominio_Id`,`GrupoDeSeguranca_Id`),
  KEY `fk_udd_gds_to_gds_GrupoDeSeguranca_Id` (`GrupoDeSeguranca_Id`),
  CONSTRAINT `fk_udd_gds_to_gds_GrupoDeSeguranca_Id` FOREIGN KEY (`GrupoDeSeguranca_Id`) REFERENCES `grupodeseguranca` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_udd_gds_to_udd_UsuarioDoDominio_Id` FOREIGN KEY (`UsuarioDoDominio_Id`) REFERENCES `usuariododominio` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='LinkTable : GrupoDeSeguranca <-> UsuarioDoDominio';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `udd_to_gds`
--

LOCK TABLES `udd_to_gds` WRITE;
/*!40000 ALTER TABLE `udd_to_gds` DISABLE KEYS */;
/*!40000 ALTER TABLE `udd_to_gds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuariododominio`
--

DROP TABLE IF EXISTS `usuariododominio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `usuariododominio` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Ativo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Indica se a conta do usuário está ativa para o domínio.',
  `Locale` varchar(5) NOT NULL DEFAULT 'pt-BR' COMMENT 'Locale padrão para o usuário.',
  `DataDeRegistro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora deste registro.',
  `Nome` varchar(128) NOT NULL COMMENT 'Nome do usuário.',
  `Genero` varchar(32) NOT NULL COMMENT 'Gênero do usuário.',
  `Login` varchar(64) NOT NULL COMMENT 'Login do usuário.',
  `ShortLogin` varchar(32) NOT NULL COMMENT 'Login curto.',
  `Senha` varchar(40) NOT NULL COMMENT 'Senha de acesso.',
  `DataDeDefinicaoDeSenha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora da definição da senha atual.',
  `Apresentacao` longtext COMMENT 'Texto de apresentação do usuário.',
  `EmailContato` varchar(64) NOT NULL COMMENT 'Email para contato.',
  `ValorInteiro` int(11) NOT NULL DEFAULT '500' COMMENT 'Valor inteiro para testes.',
  `ValorFloat` float NOT NULL DEFAULT '8.556' COMMENT 'Valor float para testes.',
  `ValorReal` decimal(10,4) NOT NULL DEFAULT '7.7780' COMMENT 'Valor real para testes.',
  `SessaoDeAcesso_Id` bigint(20) DEFAULT NULL COMMENT 'Sessão atualmente aberta.',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `uc_udd_Login` (`Login`),
  UNIQUE KEY `uc_udd_ShortLogin` (`ShortLogin`),
  KEY `idx_udd_Login` (`Login`),
  KEY `idx_udd_ShortLogin` (`ShortLogin`),
  KEY `fk_udd_to_sda_SessaoDeAcesso_Id` (`SessaoDeAcesso_Id`),
  CONSTRAINT `fk_udd_to_sda_SessaoDeAcesso_Id` FOREIGN KEY (`SessaoDeAcesso_Id`) REFERENCES `sessaodeacesso` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Conta de um usuário que pode efetuar login em aplicações do domínio.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuariododominio`
--

LOCK TABLES `usuariododominio` WRITE;
/*!40000 ALTER TABLE `usuariododominio` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuariododominio` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-07-12  0:46:26
