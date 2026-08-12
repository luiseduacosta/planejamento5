-- Test database schema.
--
-- Tabelas adicionais usadas pelos testes, criadas de forma idempotente
-- (CREATE TABLE IF NOT EXISTS) depois que as migrations rodam. As migrations
-- já cobrem users, dias, horarios, salas e docente_disponibilidades; as
-- tabelas abaixo (turmaotps, configuraplanejamentos, docentes) não possuem
-- migration própria e são definidas aqui para que os fixtures consigam
-- refletir o schema no banco de teste.

CREATE TABLE IF NOT EXISTS turmaotps (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  configuraplanejamento_id INTEGER NOT NULL,
  turno VARCHAR(10),
  periodo INTEGER,
  turmaotp VARCHAR(5) NOT NULL,
  docente_id INTEGER,
  dia_id INTEGER,
  horario_id INTEGER,
  sala_id INTEGER,
  observacoes VARCHAR(255),
  created DATETIME,
  modified DATETIME
);

CREATE TABLE IF NOT EXISTS configuraplanejamentos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  usuarioplanejamento_id INTEGER,
  nome VARCHAR(100) NOT NULL,
  semestre VARCHAR(20) NOT NULL,
  versao INTEGER,
  ativo BOOLEAN DEFAULT 0,
  created DATETIME,
  modified DATETIME
);

CREATE TABLE IF NOT EXISTS docentes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nome VARCHAR(50) NOT NULL,
  cpf VARCHAR(12),
  sexo VARCHAR(1) DEFAULT '2',
  ddd_telefone VARCHAR(2) DEFAULT '21',
  ddd_celular VARCHAR(2) DEFAULT '21',
  departamento VARCHAR(30) NOT NULL,
  status VARCHAR(10) DEFAULT 'ativo'
);
