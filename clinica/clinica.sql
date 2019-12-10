-- DROP DATABASE IF EXISTS CLINICA;
CREATE DATABASE IF NOT EXISTS CLINICA;
USE CLINICA;


--
-- Estrutura da tabela CLIENTE
--

CREATE TABLE IF NOT EXISTS CLIENTE
(
  ID_CLIENTE int(11) NOT NULL AUTO_INCREMENT,
  NOME_CLIENTE varchar(30) NOT NULL,
  EMAIL varchar(30) NOT NULL,
  CIDADE varchar(30) NOT NULL,
  ESTADO varchar(30) NOT NULL,
  TELEFONE varchar(30) NOT NULL,
  BAIRRO varchar(30) NOT NULL,
  PRIMARY KEY (ID_CLIENTE)
);

--
-- Extraindo dados da tabela CLIENTE
--

INSERT INTO CLIENTE (ID_CLIENTE, NOME_CLIENTE, EMAIL, CIDADE, ESTADO, TELEFONE, BAIRRO) 
VALUES
(1, 'Arnaldo', 'arnaldo@gmail.com', 'Araraquara', 'São Paulo', '(16)5555-6666', 'São José'),
(2, 'Maria', 'maria@gmail.com', 'Araraquara', 'São Paulo', '(16)3333-6666', 'Maria Luiza');

-- --------------------------------------------------------

--
-- Estrutura da tabela FUNCIONARIO
--

CREATE TABLE IF NOT EXISTS FUNCIONARIO (
  ID_FUNCIONARIO int(11) NOT NULL AUTO_INCREMENT,
  NOME_FUNCIONARIO varchar(30) NOT NULL,
  EMAIL varchar(30) NOT NULL,
  TELEFONE varchar(30) NOT NULL,
  CIDADE varchar(30) NOT NULL,
  CPF date NOT NULL,
  SALARIO int(11) NOT NULL,
  PRIMARY KEY (ID_FUNCIONARIO)
);

--
-- Extraindo dados da tabela FUNCIONARIO
--

INSERT INTO FUNCIONARIO (ID_FUNCIONARIO, NOME_FUNCIONARIO, EMAIL, TELEFONE, CIDADE, CPF, SALARIO)
VALUES
(1, 'Marcos', 'marcos@gmail.com', '(16)5555-4444', 'Araraquara', 123456, '2000.00'),
(2, 'Marcelo', 'marcelo@gmail.com', '(16)5656-2323', 'Araraquara', 12345, '2500.00');

-- ---------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela MEDICO
--

CREATE TABLE IF NOT EXISTS MEDICO (
  ID_MEDICO int(11) NOT NULL AUTO_INCREMENT,
  NOME_MEDICO varchar(30) NOT NULL,
  TELEFONE varchar(30) NOT NULL,
  CIDADE varchar(30) NOT NULL,
  PRIMARY KEY (ID_MEDICO)
);

--
-- Extraindo dados da tabela MEDICO
--

INSERT INTO MEDICO (ID_MEDICO, NOME_MEDICO, TELEFONE, CIDADE) 
VALUES
(1, 'Jorge', '(16)99999-9999', 'Araraquara'),
(2, 'Claudia', '(16)3333-6666', 'Araraquara');


-- ---------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela CANCELAMENTO DE CONSULTA
--

CREATE TABLE IF NOT EXISTS CANCELAMENTO
(
	ID_CANCELAMENTO INT NOT NULL AUTO_INCREMENT,
    ID_CLIENTE INT NOT NULL,
    MOTIVO VARCHAR(50) NOT NULL,
    PRIMARY KEY (ID_CANCELAMENTO),

    FOREIGN KEY (ID_CLIENTE)
      REFERENCES CLIENTE(ID_CLIENTE)
);

--
-- Extraindo dados da tabela CANCELAMENTO
--

INSERT INTO CANCELAMENTO (ID_CANCELAMENTO, ID_CLIENTE, MOTIVO)
VALUES
(1, 2, 'Chegar muito atrasado'),
(2, 3, 'Ocorreu um emprevisto');

-- ---------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela FORMAS DE PAGAMENTO
--

CREATE TABLE IF NOT EXISTS FORMA_PAGAMENTO
(
	ID_FORMA_PAGAMENTO INT NOT NULL AUTO_INCREMENT,
    TIPO_PAGAMENTO VARCHAR(30) NOT NULL,
    PRIMARY KEY (ID_FORMA)
);

--
-- Extraindo dados da tabela FORMA DE PAGAMENTO
--

INSERT INTO FORMA_PAGAMENTO (ID_FORMA_PAGAMENTO, TIPO_PAGAMENTO)
VALUES
(1, 'Cartao de Debito'),
(2, 'Cartao de Credito'),
(3, 'Dinheto');


-- ---------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela GRUPO
--

CREATE TABLE IF NOT EXISTS GRUPO
(
	ID_GRUPO INT NOT NULL AUTO_INCREMENT,
    DESCRICAO VARCHAR(50) NOT NULL,
    PRIMARY KEY (ID_GRUPO)
);

--
-- Extraindo dados da tabela GRUPO
--

INSERT INTO GRUPO (ID_GRUPO, NOME_GRUPO)
VALUES
(1, 'Limpeza - R$ 30.00'),
(2, 'Tratamento - R$ 45.00'),
(3, 'Clareamento - R$ 40.00'),
(4, 'Obturação - R$ 100.00'),
(5, 'Canal - R$ 150.00');

-- ---------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela AGENDAMENTO DE CONSULTA
--


-- DROP TABLE AGENDAMENTO_CONSULTA;
CREATE TABLE IF NOT EXISTS AGENDAMENTO_CONSULTA
(
	ID_CONSULTA INT NOT NULL AUTO_INCREMENT,
    ID_CLIENTE INT NOT NULL,
    ID_MEDICO INT NOT NULL,
    ID_GRUPO INT NOT NULL,
	DIA VARCHAR(30) NOT NULL,
    DATA_HORA VARCHAR(50) NOT NULL,
    PRIMARY KEY (ID_CONSULTA),
    
    FOREIGN KEY (ID_CLIENTE)
		REFERENCES CLIENTE(ID_CLIENTE),
	FOREIGN KEY (ID_MEDICO)
		REFERENCES MEDICO(ID_MEDICO),
	FOREIGN KEY (ID_GRUPO)
		REFERENCES GRUPO(ID_GRUPO)
);

--
-- Extraindo dados da tabela AGENDAMENTO DE CONSULTA
--

INSERT INTO AGENDAMENTO_CONSULTA (ID_CONSULTA, ID_CLIENTE, ID_MEDICO, ID_GRUPO, DIA, DATA_HORA)
VALUES
(1, 2, 1, '1', 'Quarta-Feira', '00:00:00 a 2019-02-10'),
(2, 1, 2, '4', 'Sexta-Feira', '00:00:00 a 2019-01-25');

-- ---------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela DIAGNOSTICO
-- 

CREATE TABLE IF NOT EXISTS DIAGNOSTICO
(
	ID_CODIGO INT NOT NULL AUTO_INCREMENT,
    ID_MEDICO INT NOT NULL,
    ID_CLIENTE INT NOT NULL,
    DESCRICAO VARCHAR(50) NOT NULL,
    PRIMARY KEY (ID_CODIGO),
    
    FOREIGN KEY (ID_MEDICO)
		REFERENCES MEDICO(CRM),
	FOREIGN KEY (ID_CLIENTE)
		REFERENCES CLIENTE(ID_CLIENTE)
);


--
-- Extraindo dados da tabela DIAGNOSTICO
-- 

INSERT INTO DIAGNOSTICO (ID_CODIGO, ID_MEDICO, ID_CLIENTE, DESCRICAO)
VALUES
(1, 2, 1, 'Dente esta com Carie'),
(2, 1, 2, 'Falta de cuidado');

-- ---------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela PAGAMENTO
-- 

CREATE TABLE IF NOT EXISTS PAGAMENTO
(
	ID_PAGAMENTO INT NOT NULL AUTO_INCREMENT,
    ID_FORMA_PAGAMENTO INT NOT NULL,
    ID_GRUPO INT NOT NULL,
    ID_CLIENTE INT NOT NULL,
    PRIMARY KEY (ID_PAGAMENTO),
    
    FOREIGN KEY (ID_FORMA_PAGAMENTO)
		REFERENCES FORMA_PAGAMENTO(ID_FORMA_PAGAMENTO),
	FOREIGN KEY (ID_GRUPO)
		REFERENCES GRUPO(ID_GRUPO),
	FOREIGN KEY (ID_CLIENTE)
		REFERENCES CLIENTE(ID_CLIENTE)
);

--
-- Extraindo dados da tabela DIAGNOSTICO
-- 

INSERT INTO PAGAMENTO (ID_PAGAMENTO, ID_FORMA_PAGAMENTO, ID_GRUPO, ID_CLIENTE)
VALUES
(1, 2, 1, 2),
(2, 3, 4, 1);

-- ---------------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------
-- -------------------------------------------------------VIEWS--------------------------------------------------------


CREATE VIEW CONSULTAS AS
SELECT C.NOME_CLIENTE AS 'Nome do Cliente', M.NOME_MEDICO AS 'Nome do Medico', G.NOME_GRUPO AS 'Ofício e Valores', AC.DIA AS 'Dia da Consulta', AC.DATETIME AS 'Hora e Data da Consulta'
FROM AGENDAMENTO_CONSULTA AC
		INNER JOIN
	CLIENTE C ON AC.COD_CLIENTE = C.ID_CLIENTE
		INNER JOIN
	MEDICO M ON AC.COD_MEDICO = M.CRM
		INNER JOIN
	GRUPO G ON AC.COD_GRUPO = G.ID_GRUPO;
	

-- DROP VIEW IF EXISTS CONSULTAS;
SELECT * FROM CONSULTAS;

-- ---------------------------------------------------------------------------------------------------------------------

CREATE VIEW PAGAMENTOS AS
SELECT C.NOME_CLIENTE AS 'Nome do Cliente', G.NOME_GRUPO AS 'Ofício e Valores', FM.TIPO_PAGAMENTO AS 'Forma de Pagamento'
FROM PAGAMENTO P
		INNER JOIN
	CLIENTE C ON P.COD_CLIENTE = C.ID_CLIENTE
		INNER JOIN
	GRUPO G ON P.COD_GRUPO = G.ID_GRUPO
		INNER JOIN
	FORMA_PAGAMENTO FM ON P.COD_FORMA_PAGAMENTO = FM.ID_FORMA;

-- DROP VIEW IF EXISTS PAGAMENTOS;
SELECT * FROM PAGAMENTOS;

-- ---------------------------------------------------------------------------------------------------------------------
-- ---------------------------------------------------------------------------------------------------------------------
-- -------------------------------------------------------TRIGGERS------------------------------------------------------

