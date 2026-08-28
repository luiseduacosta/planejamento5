<?php
declare(strict_types=1);

use Migrations\BaseMigration;

/**
 * Garante que a tabela docentes esteja alinhada com o que a aplicação
 * (DocentesTable, formulários, fixtures) espera:
 *   - tipocargo (cadastro de docentes)
 *   - created / modified (Timestamp behavior)
 *   - status como VARCHAR (legado usava TINYINT 0/1)
 *   - coluna regiao (legado usava "region" INT)
 *   - tamanhos e tipos corretos para nome, siape, cress, email
 *   - colunas legadas não usadas pela aplicação são preservadas mas
 *     tornadas anuláveis para não quebrar inserções via ORM.
 *
 * A migração é TOTALMENTE defensiva e idempotente:
 *   - Se a tabela docentes não existir, não faz nada (bancos novos criam
 *     tudo via tests/schema.sql ou outro script).
 *   - Se uma coluna já tiver o tipo/tamanho correto, não a altera.
 *   - Dados existentes são preservados: TINYINT status é convertido para
 *     strings canônicas, region INT é copiado para regiao VARCHAR.
 */
class AddTipocargoAndTimestampsToDocentes extends BaseMigration
{
    public function up(): void
    {
        if (!$this->hasTable('docentes')) {
            return;
        }

        $table = $this->table('docentes');
        $changed = false;

        if (!$table->hasColumn('tipocargo')) {
            $table->addColumn('tipocargo', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
                'after' => 'dataingresso',
            ]);
            $changed = true;
        }
        if (!$table->hasColumn('created')) {
            $table->addColumn('created', 'datetime', [
                'default' => null,
                'null' => true,
            ]);
            $changed = true;
        }
        if (!$table->hasColumn('modified')) {
            $table->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => true,
            ]);
            $changed = true;
        }

        if (!$table->hasColumn('regiao') && $table->hasColumn('region')) {
            $table->addColumn('regiao', 'string', [
                'default' => null,
                'limit' => 2,
                'null' => true,
                'after' => 'cress',
            ]);
            $changed = true;
        }

        if ($changed) {
            $table->update();
        }

        $adapter = $this->getAdapter();
        $pdo = method_exists($adapter, 'getConnection')
            ? $adapter->getConnection()
            : (class_exists(\Cake\Datasource\ConnectionManager::class, false)
                ? \Cake\Datasource\ConnectionManager::get('default')->getDriver()->getConnection()
                : null);

        if ($pdo !== null) {
            try {
                $colType = $this->getColumnType('docentes', 'status');
                if ($colType !== null && strpos($colType, 'int') !== false) {
                    $stmt = $pdo->query('SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ' . $pdo->quote('docentes') . ' AND COLUMN_NAME = ' . $pdo->quote('status'));
                    $actual = $stmt ? $stmt->fetchColumn() : null;
                    if ($actual !== false && $actual !== null) {
                        $pdo->exec('ALTER TABLE docentes MODIFY COLUMN status VARCHAR(10) NULL DEFAULT \'ativo\'');
                        $pdo->exec("UPDATE docentes SET status = CASE WHEN status = '0' OR status = 0 THEN 'inativo' WHEN status IS NULL OR status = '' THEN 'ativo' ELSE 'ativo' END");
                        $changed = true;
                    }
                }
            } catch (\Throwable $_e) {
                unset($_e);
            }

            try {
                if ($this->hasTable('docentes')) {
                    $t = $this->table('docentes');
                    if ($t->hasColumn('region') && $t->hasColumn('regiao')) {
                        $pdo->exec('UPDATE docentes SET regiao = CAST(region AS CHAR(2)) WHERE region IS NOT NULL AND (regiao IS NULL OR regiao = \'\')');
                    }
                }
            } catch (\Throwable $_e) {
                unset($_e);
            }

            $this->ensureColumn($pdo, 'docentes', 'nome', 'VARCHAR(200) NOT NULL');
            $this->ensureColumn($pdo, 'docentes', 'siape', 'VARCHAR(20) NULL');
            $this->ensureColumn($pdo, 'docentes', 'cress', 'VARCHAR(10) NULL');
            $this->ensureColumn($pdo, 'docentes', 'email', 'VARCHAR(255) NULL');
            $this->ensureColumnNullable($pdo, 'docentes', 'codigo_telefone');
            $this->ensureColumnNullable($pdo, 'docentes', 'codigo_celular');
            $this->ensureColumnNullable($pdo, 'docentes', 'curriculolattes');
            $this->ensureColumnNullable($pdo, 'docentes', 'atualizacaolattes');
            $this->ensureColumnNullableDefaultZero($pdo, 'docentes', 'user_id');
            $this->ensureColumnNullableDefaultZero($pdo, 'docentes', 'estagiarios_count');
        }

        if ($this->hasTable('docente_disponibilidades')) {
            $ddTable = $this->table('docente_disponibilidades');
            $ddChanged = false;
            if ($ddTable->hasColumn('motivo')) {
                $motivoInfo = $this->describeColumn('docente_disponibilidades', 'motivo');
                if (!empty($motivoInfo) && ($motivoInfo['null'] ?? null) === false) {
                    try {
                        $pdo?->exec('ALTER TABLE docente_disponibilidades MODIFY COLUMN motivo VARCHAR(100) NULL DEFAULT \'\'');
                        $ddChanged = true;
                    } catch (\Throwable $_e) {
                        unset($_e);
                    }
                }
            }
            if ($ddTable->hasColumn('observacoes')) {
                $obsInfo = $this->describeColumn('docente_disponibilidades', 'observacoes');
                if (!empty($obsInfo) && ($obsInfo['null'] ?? null) === false) {
                    try {
                        $pdo?->exec('ALTER TABLE docente_disponibilidades MODIFY COLUMN observacoes TEXT NULL');
                        $ddChanged = true;
                    } catch (\Throwable $_e) {
                        unset($_e);
                    }
                }
            }
            try {
                $pdo?->exec('ALTER TABLE docente_disponibilidades MODIFY COLUMN id INTEGER NOT NULL AUTO_INCREMENT');
                $ddChanged = true;
            } catch (\Throwable $_e) {
                unset($_e);
            }
            if ($ddChanged) {
                try { $ddTable->update(); } catch (\Throwable $_e) { unset($_e); }
            }
        }
    }

    public function down(): void
    {
        if (!$this->hasTable('docentes')) {
            return;
        }

        $table = $this->table('docentes');
        $changed = false;

        foreach (['tipocargo', 'created', 'modified', 'regiao'] as $column) {
            if ($table->hasColumn($column)) {
                $table->removeColumn($column);
                $changed = true;
            }
        }

        if ($changed) {
            $table->update();
        }
    }

    private function getColumnType(string $table, string $column): ?string
    {
        try {
            $columns = $this->table($table)->getColumns();
            foreach ($columns as $col) {
                if (is_array($col) && ($col['name'] ?? null) === $column) {
                    return strtolower((string)($col['type'] ?? ''));
                }
            }
            $desc = $this->describeColumn($table, $column);
            if (!empty($desc)) {
                return strtolower((string)($desc['type'] ?? ''));
            }
        } catch (\Throwable $_e) {
            unset($_e);
        }
        return null;
    }

    private function describeColumn(string $tableName, string $columnName): ?array
    {
        try {
            $adapter = $this->getAdapter();
            if (method_exists($adapter, 'getColumnByName')) {
                $col = $adapter->getColumnByName($tableName, $columnName);
                return is_array($col) ? $col : null;
            }
            $t = $this->table($tableName);
            if (method_exists($t, 'getColumn')) {
                $col = $t->getColumn($columnName);
                return is_array($col) ? $col : null;
            }
        } catch (\Throwable $_e) {
            unset($_e);
        }
        return null;
    }

    /**
     * Garante que uma coluna exista com um dado DDL de tipo/tamanho/nullability.
     * Executa ALTER TABLE só quando necessário, usando INFORMATION_SCHEMA.
     */
    private function ensureColumn($pdo, string $table, string $column, string $targetDdl): void
    {
        if ($pdo === null) {
            return;
        }
        try {
            $stmt = $pdo->prepare(
                'SELECT DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, IS_NULLABLE, COLUMN_TYPE '
                . 'FROM INFORMATION_SCHEMA.COLUMNS '
                . 'WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?'
            );
            $stmt->execute([$table, $column]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$row) {
                return;
            }
            $current = strtoupper((string)$row['COLUMN_TYPE'])
                . ' ' . (strtoupper((string)$row['IS_NULLABLE']) === 'YES' ? 'NULL' : 'NOT NULL');
            $target = preg_replace('/\s+/', ' ', trim(strtoupper($targetDdl)));
            if (strpos($current, $target) === false) {
                $pdo->exec(sprintf(
                    'ALTER TABLE %s MODIFY COLUMN %s %s',
                    $table,
                    $column,
                    $targetDdl
                ));
            }
        } catch (\Throwable $_e) {
            unset($_e);
        }
    }

    private function ensureColumnNullable($pdo, string $table, string $column): void
    {
        if ($pdo === null) {
            return;
        }
        try {
            $t = $this->table($table);
            if (!$t->hasColumn($column)) {
                return;
            }
            $stmt = $pdo->prepare(
                'SELECT IS_NULLABLE, DATA_TYPE, COLUMN_TYPE '
                . 'FROM INFORMATION_SCHEMA.COLUMNS '
                . 'WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?'
            );
            $stmt->execute([$table, $column]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$row || strtoupper((string)$row['IS_NULLABLE']) === 'YES') {
                return;
            }
            $colType = (string)($row['COLUMN_TYPE'] ?: $row['DATA_TYPE']);
            $pdo->exec(sprintf(
                'ALTER TABLE %s MODIFY COLUMN %s %s NULL DEFAULT NULL',
                $table,
                $column,
                strtoupper($colType)
            ));
        } catch (\Throwable $_e) {
            unset($_e);
        }
    }

    private function ensureColumnNullableDefaultZero($pdo, string $table, string $column): void
    {
        if ($pdo === null) {
            return;
        }
        try {
            $t = $this->table($table);
            if (!$t->hasColumn($column)) {
                return;
            }
            $stmt = $pdo->prepare(
                'SELECT IS_NULLABLE, DATA_TYPE, COLUMN_TYPE '
                . 'FROM INFORMATION_SCHEMA.COLUMNS '
                . 'WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?'
            );
            $stmt->execute([$table, $column]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$row) {
                return;
            }
            if (strtoupper((string)$row['IS_NULLABLE']) === 'YES') {
                return;
            }
            $colType = (string)($row['COLUMN_TYPE'] ?: $row['DATA_TYPE']);
            $pdo->exec(sprintf(
                'ALTER TABLE %s MODIFY COLUMN %s %s NULL DEFAULT 0',
                $table,
                $column,
                strtoupper($colType)
            ));
        } catch (\Throwable $_e) {
            unset($_e);
        }
    }
}
