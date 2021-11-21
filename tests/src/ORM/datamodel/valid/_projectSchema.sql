/*
 * Main Schema definition
 * Generated in 2021-11-21-22-45-22
*/

/*--INI CREATE TABLE--*/
CREATE TABLE Cidade (
    Id BIGINT NOT NULL AUTO_INCREMENT, 
    Nome VARCHAR(128) NOT NULL COMMENT 'Nome da cidade.', 
    Estado VARCHAR(2) NOT NULL COMMENT 'Sigla do estado.', 
    Capital TINYINT(1) NOT NULL COMMENT 'Indica se a cidade é capital do seu estado.', 
    PRIMARY KEY (Id)
) COMMENT 'Coleção de cidades brasileiras.';
/*--END CREATE TABLE--*/



/*--INI CREATE TABLE--*/
CREATE TABLE EnderecoPostal (
    Id BIGINT NOT NULL AUTO_INCREMENT, 
    CEP VARCHAR(10) NOT NULL COMMENT 'Código de endereçamento postal.', 
    TipoDeEndereco VARCHAR(32) NOT NULL COMMENT 'Indica se o endereço é residencial, comercial, ou de outra natureza qualquer.', 
    TipoDeLogradouro VARCHAR(32) NOT NULL COMMENT 'Tipo de logradouro (rua, avenida, travessa...).', 
    Logradouro VARCHAR(128) NOT NULL COMMENT 'Nome do logradouro.', 
    Numero INTEGER NOT NULL COMMENT 'Número da residência.', 
    Complemento VARCHAR(128) COMMENT 'Complemento do endereço.', 
    Bairro VARCHAR(128) NOT NULL COMMENT 'Nome do bairro.', 
    Referencia VARCHAR(255) COMMENT 'Referência para o endereço.', 
    Cidade_Id BIGINT COMMENT 'Cidade correlacionada com este EnderecoPostal', 
    PRIMARY KEY (Id)
) COMMENT 'Endereço postal.';
/*--END CREATE TABLE--*/



/*--INI CREATE TABLE--*/
CREATE TABLE GrupoDeSeguranca (
    Id BIGINT NOT NULL AUTO_INCREMENT, 
    Ativo TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Indica se este grupo está ativo ou não.', 
    Aplicacao VARCHAR(32) NOT NULL COMMENT 'Nome da aplicação para a qual este grupo é utilizado.', 
    Nome VARCHAR(32) NOT NULL COMMENT 'Nome para este grupo de segurança.', 
    Descricao VARCHAR(255) COMMENT 'Descrição do grupo de segurança.', 
    UseConnection VARCHAR(32) NOT NULL COMMENT 'Identificador da conexão com o banco de dados que será utilizado pelos usuários deste grupo.', 
    PoliticaPadrao VARCHAR(1) NOT NULL DEFAULT 'B' COMMENT 'Indica a politica de segurança comum para as rotas [b (block) | f (free)].', 
    PRIMARY KEY (Id)
) COMMENT 'Define um perfil de segurança para um conjunto de usuários.';
/*--END CREATE TABLE--*/



/*--INI CREATE TABLE--*/
CREATE TABLE SessaoDeAcesso (
    Id BIGINT NOT NULL AUTO_INCREMENT, 
    DataDoLogin DATETIME NOT NULL DEFAULT NOW() COMMENT 'Data e hora do login.', 
    Login VARCHAR(64) NOT NULL COMMENT 'Login com o qual a sessão foi autenticada.', 
    Aplicacao VARCHAR(32) NOT NULL COMMENT 'Aplicação na qual o usuário efetuou o login.', 
    ProfileInUse VARCHAR(32) NOT NULL COMMENT 'Perfil de segurança do usuário sendo usado no momento.', 
    SessionTimeOut DATETIME NOT NULL COMMENT 'Data e hora para o fim da sessão.', 
    Ip VARCHAR(64) NOT NULL COMMENT 'Ip do usuário no momento do login.', 
    Browser VARCHAR(256) NOT NULL COMMENT 'Identificação do nevegador do usuário no momento do login.', 
    Locale VARCHAR(5) NOT NULL DEFAULT 'pt-BR' COMMENT 'Locale do usuário.', 
    SessionRenew TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Indica se a sessão é renovada automaticamente a cada iteração do usuário.', 
    SessionID VARCHAR(160) NOT NULL COMMENT 'ID da sessão do usuário.', 
    PRIMARY KEY (Id)
) COMMENT 'Define uma sessão de acesso para um usuário que efetuou login.';
/*--END CREATE TABLE--*/



/*--INI CREATE TABLE--*/
CREATE TABLE UsuarioDoDominio (
    Id BIGINT NOT NULL AUTO_INCREMENT, 
    Ativo TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Indica se a conta do usuário está ativa para o domínio.', 
    Locale VARCHAR(5) NOT NULL DEFAULT 'pt-BR' COMMENT 'Locale padrão para o usuário.', 
    DataDeRegistro DATETIME NOT NULL DEFAULT NOW() COMMENT 'Data e hora deste registro.', 
    Nome VARCHAR(128) NOT NULL COMMENT 'Nome do usuário.', 
    Genero VARCHAR(32) NOT NULL COMMENT 'Gênero do usuário.', 
    Login VARCHAR(64) NOT NULL COMMENT 'Login do usuário.', 
    ShortLogin VARCHAR(32) NOT NULL COMMENT 'Login curto.', 
    Senha VARCHAR(40) NOT NULL COMMENT 'Senha de acesso.', 
    DataDeDefinicaoDeSenha DATETIME NOT NULL DEFAULT NOW() COMMENT 'Data e hora da definição da senha atual.', 
    Apresentacao LONGTEXT COMMENT 'Texto de apresentação do usuário.', 
    EmailContato VARCHAR(64) NOT NULL COMMENT 'Email para contato.', 
    ValorInteiro INTEGER NOT NULL DEFAULT 500 COMMENT 'Valor inteiro para testes.', 
    ValorFloat FLOAT NOT NULL DEFAULT 8.556 COMMENT 'Valor float para testes.', 
    ValorReal DECIMAL(14,4) NOT NULL DEFAULT 7.778 COMMENT 'Valor real para testes.', 
    SessaoDeAcesso_Id BIGINT NOT NULL COMMENT 'Sessão atualmente aberta.', 
    PRIMARY KEY (Id)
) COMMENT 'Conta de um usuário que pode efetuar login em aplicações do domínio.';
/*--END CREATE TABLE--*/



/*--INI CREATE TABLE--*/
CREATE TABLE udd_to_gds (
    GrupoDeSeguranca_Id BIGINT NOT NULL COMMENT 'GrupoDeSeguranca em UsuarioDoDominio', 
    UsuarioDoDominio_Id BIGINT NOT NULL COMMENT 'UsuarioDoDominio em GrupoDeSeguranca'
) COMMENT 'LinkTable : GrupoDeSeguranca <-> UsuarioDoDominio';
/*--END CREATE TABLE--*/






/*
 * Constraints definition
*/

/*--INI CONSTRAINT INSTRUCTIONS--*/
ALTER TABLE Cidade ADD CONSTRAINT uc_cid_Nome_Estado_Capital UNIQUE (Nome, Estado, Capital);
ALTER TABLE EnderecoPostal ADD CONSTRAINT fk_ep_to_cid_Cidade_Id FOREIGN KEY (Cidade_Id) REFERENCES Cidade(Id) ON DELETE CASCADE;
ALTER TABLE GrupoDeSeguranca ADD CONSTRAINT enum_gds_Aplicacao CHECK (Aplicacao IN ('Application', 'EnGarde', 'GuideLine'));
ALTER TABLE SessaoDeAcesso ADD CONSTRAINT enum_sda_Aplicacao CHECK (Aplicacao IN ('Application', 'EnGarde', 'GuideLine'));
ALTER TABLE SessaoDeAcesso ADD CONSTRAINT enum_sda_Locale CHECK (Locale IN ('pt-BR', 'en-US'));
ALTER TABLE SessaoDeAcesso ADD CONSTRAINT uc_sda_SessionID UNIQUE (SessionID);
ALTER TABLE UsuarioDoDominio ADD CONSTRAINT enum_udd_Locale CHECK (Locale IN ('pt-BR', 'en-US'));
ALTER TABLE UsuarioDoDominio ADD CONSTRAINT enum_udd_Genero CHECK (Genero IN ('Agender', 'Androgyne', 'Androgynous', 'Bigender', 'Cis', 'Cisgender', 'Cis Female', 'Cis Male', 'Cis Man', 'Cis Woman', 'Cisgender Female', 'Cisgender Male', 'Cisgender Man', 'Cisgender Woman', 'Female to Male', 'FTM', 'Gender Fluid', 'Gender Nonconforming', 'Gender Questioning', 'Gender Variant', 'Genderqueer', 'Intersex', 'Male to Female', 'MTF', 'Neither', 'Neutrois', 'Non-binary', 'Other', 'Pangender', 'Trans', 'Trans*', 'Trans Female', 'Trans* Female', 'Trans Male', 'Trans* Male', 'Trans Man', 'Trans* Man', 'Trans Person', 'Trans* Person', 'Trans Woman', 'Trans* Woman', 'Transfeminine', 'Transgender', 'Transgender Female', 'Transgender Male', 'Transgender Man', 'Transgender Person', 'Transgender Woman', 'Transmasculine', 'Transsexual', 'Transsexual Female', 'Transsexual Male', 'Transsexual Man', 'Transsexual Person', 'Transsexual Woman', 'Two-Spirit'));
ALTER TABLE UsuarioDoDominio ADD CONSTRAINT uc_udd_Login UNIQUE (Login);
CREATE INDEX idx_udd_Login ON UsuarioDoDominio (Login);
ALTER TABLE UsuarioDoDominio ADD CONSTRAINT uc_udd_ShortLogin UNIQUE (ShortLogin);
CREATE INDEX idx_udd_ShortLogin ON UsuarioDoDominio (ShortLogin);
ALTER TABLE UsuarioDoDominio ADD CONSTRAINT fk_udd_to_sda_SessaoDeAcesso_Id FOREIGN KEY (SessaoDeAcesso_Id) REFERENCES SessaoDeAcesso(Id);
ALTER TABLE UsuarioDoDominio ADD CONSTRAINT uc_udd_SessaoDeAcesso_Id UNIQUE (SessaoDeAcesso_Id);
ALTER TABLE udd_to_gds ADD CONSTRAINT fk_udd_gds_to_gds_GrupoDeSeguranca_Id FOREIGN KEY (GrupoDeSeguranca_Id) REFERENCES GrupoDeSeguranca(Id) ON DELETE CASCADE;
ALTER TABLE udd_to_gds ADD CONSTRAINT fk_udd_gds_to_udd_UsuarioDoDominio_Id FOREIGN KEY (UsuarioDoDominio_Id) REFERENCES UsuarioDoDominio(Id) ON DELETE CASCADE;
ALTER TABLE udd_to_gds ADD CONSTRAINT uc_udd_gds_UsuarioDoDominio_Id_GrupoDeSeguranca_Id UNIQUE (UsuarioDoDominio_Id, GrupoDeSeguranca_Id);
/*--END CONSTRAINT INSTRUCTIONS--*/




/*
 * End of Main Schema definition
 * Generated in 2021-11-21-22-45-22
*/
