-- Test database schema.
--
-- Tabelas adicionais usadas pelos testes, criadas de forma idempotente
-- (CREATE TABLE IF NOT EXISTS) depois que as migrations rodam. As migrations
-- já cobrem users, dias, horarios, salas, disciplinas e docente_disponibilidades;
-- as tabelas abaixo (turmaotps, configuraplanejamentos, docentes, planejamentos)
-- não possuem migration de criação própria e são definidas aqui para que os
-- fixtures consigam refletir o schema no banco de teste.

CREATE TABLE IF NOT EXISTS turmaotps (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  configuraplanejamento_id INTEGER NOT NULL,
  turno VARCHAR(10),
  periodo INTEGER,
  turmaotp VARCHAR(20) NOT NULL,
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

-- Colunas alinhadas ao DocentesTable/Docente (validação, formulários e
-- behavior Timestamp): nome, cpf, siape, cress, regiao, telefone, celular,
-- email, dataingresso, tipocargo, departamento, dataegresso, motivoegresso,
-- status, observacoes, created, modified.
CREATE TABLE IF NOT EXISTS docentes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nome VARCHAR(200) NOT NULL,
  cpf VARCHAR(14),
  siape VARCHAR(20),
  cress VARCHAR(10),
  regiao VARCHAR(2),
  telefone VARCHAR(20),
  celular VARCHAR(20),
  email VARCHAR(255),
  dataingresso DATE,
  tipocargo VARCHAR(20),
  departamento VARCHAR(30),
  dataegresso DATE,
  motivoegresso VARCHAR(100),
  status VARCHAR(10) DEFAULT 'ativo',
  observacoes TEXT,
  created DATETIME,
  modified DATETIME
);

-- Usada pelo contain "Planejamentos" de DocentesController::view().
CREATE TABLE IF NOT EXISTS planejamentos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  configuraplanejamento_id INTEGER NOT NULL,
  turno VARCHAR(10),
  periodo INTEGER,
  dia_id INTEGER,
  horario_id INTEGER,
  sala_id INTEGER,
  disciplina_id INTEGER,
  docente_id INTEGER,
  ementa_id INTEGER,
  optativa_id INTEGER,
  observacoes VARCHAR(255),
  created DATETIME,
  modified DATETIME
);
