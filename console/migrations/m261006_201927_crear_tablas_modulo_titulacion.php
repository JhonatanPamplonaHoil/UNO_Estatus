<?php

use yii\db\Migration;

/**
 * Migración para el Módulo de Seguimiento de Titulación y Citas - UNO
 */
class m261006_201927_crear_tablas_modulo_titulacion extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // 1. Catálogo de Carreras
        $this->createTable('{{%carrera}}', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string(150)->notNull(),
            'codigo' => $this->string(20)->notNull()->unique(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        // 2. Catálogo de Modalidades de Titulación
        $this->createTable('{{%modalidad_titulacion}}', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string(100)->notNull(),
            'descripcion' => $this->text()->null(),
            'activo' => $this->boolean()->defaultValue(true),
        ], $tableOptions);

        // 3. Catálogo de Etapas del Trámite
        $this->createTable('{{%etapa_tramite}}', [
            'id' => $this->primaryKey(),
            'secuencia' => $this->integer()->notNull()->unique(),
            'nombre' => $this->string(100)->notNull(),
            'descripcion' => $this->string(255)->null(),
            'es_etapa_final' => $this->boolean()->defaultValue(false),
            'permite_cita' => $this->boolean()->defaultValue(false),
        ], $tableOptions);

        // 4. Tabla Principal: Trámite de Titulación
        $this->createTable('{{%tramite_titulacion}}', [
            'id' => $this->primaryKey(),
            'folio' => $this->string(50)->notNull()->unique(),
            'user_id' => $this->integer()->notNull(),
            'carrera_id' => $this->integer()->notNull(),
            'modalidad_id' => $this->integer()->notNull(),
            'etapa_actual_id' => $this->integer()->notNull(),
            'matricula' => $this->string(20)->notNull(),
            'promedio_general' => $this->decimal(4, 2)->null(),
            'observaciones' => $this->text()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        // 5. Tabla de Citas para Recoger Título
        $this->createTable('{{%cita_entrega}}', [
            'id' => $this->primaryKey(),
            'tramite_id' => $this->integer()->notNull()->unique(),
            'fecha' => $this->date()->notNull(),
            'hora' => $this->time()->notNull(),
            'estado' => $this->string(20)->defaultValue('Agendada'),
            'comentarios' => $this->string(255)->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        // 6. Bitácora de Auditoría Inmutable
        $this->createTable('{{%auditoria_tramite}}', [
            'id' => $this->primaryKey(),
            'tramite_id' => $this->integer()->notNull(),
            'usuario_responsable_id' => $this->integer()->notNull(),
            'etapa_anterior_id' => $this->integer()->null(),
            'etapa_nueva_id' => $this->integer()->notNull(),
            'motivo_cambio' => $this->text()->null(),
            'ip_origen' => $this->string(45)->null(),
            'created_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        // Índices para optimizar búsquedas frecuentes
        $this->createIndex('idx-tramite-user_id', '{{%tramite_titulacion}}', 'user_id');
        $this->createIndex('idx-tramite-carrera_id', '{{%tramite_titulacion}}', 'carrera_id');
        $this->createIndex('idx-tramite-modalidad_id', '{{%tramite_titulacion}}', 'modalidad_id');
        $this->createIndex('idx-tramite-etapa_id', '{{%tramite_titulacion}}', 'etapa_actual_id');

        // Llaves Foráneas entre las tablas nuevas
        $this->addForeignKey('fk_tramite_carrera', '{{%tramite_titulacion}}', 'carrera_id', '{{%carrera}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_tramite_modalidad', '{{%tramite_titulacion}}', 'modalidad_id', '{{%modalidad_titulacion}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_tramite_etapa', '{{%tramite_titulacion}}', 'etapa_actual_id', '{{%etapa_tramite}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_cita_tramite', '{{%cita_entrega}}', 'tramite_id', '{{%tramite_titulacion}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_auditoria_tramite', '{{%auditoria_tramite}}', 'tramite_id', '{{%tramite_titulacion}}', 'id', 'CASCADE', 'CASCADE');

        // Intentar agregar FK hacia user de forma segura
        try {
            $this->addForeignKey('fk_tramite_user', '{{%tramite_titulacion}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
            $this->addForeignKey('fk_auditoria_user', '{{%auditoria_tramite}}', 'usuario_responsable_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
        } catch (\Exception $e) {
            // Si la tabla user tiene una collation o tipo integer distinto, se mantiene el índice sin romper la migración
        }

        // Datos Semilla
        $this->batchInsert('{{%etapa_tramite}}', ['secuencia', 'nombre', 'descripcion', 'es_etapa_final', 'permite_cita'], [
            [1, 'Recepción y Revisión de Documentos', 'Expediente entregado y en revisión por Servicios Escolares', false, false],
            [2, 'Validación de Pago y Derechos', 'Comprobante de pago arancelario verificado', false, false],
            [3, 'En Proceso de Expedición Estatal/Federal', 'Título en trámite ante autoridades correspondientes', false, false],
            [4, 'Título Disponible en Ventanilla', 'Título físico recibido en el campus. Ya puede agendar cita para recoger', false, true],
            [5, 'Título Entregado al Egresado', 'Trámite finalizado con la firma de recepción de documentos', true, false],
        ]);

        $this->batchInsert('{{%modalidad_titulacion}}', ['nombre', 'descripcion', 'activo'], [
            ['Informe Técnico de Residencia Profesional', 'Titulación por informe de residencia individual o en brigada', true],
            ['Tesis Profesional', 'Investigación individual o colectiva con réplica oral', true],
            ['Examen General de Egreso (EGEL-CENEVAL)', 'Acreditación por testimonio de desempeño sobresaliente o satisfactorio', true],
            ['Promedio / Excelencia Académica', 'Titulación directa por promedio general sobresaliente', true],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auditoria_tramite}}');
        $this->dropTable('{{%cita_entrega}}');
        $this->dropTable('{{%tramite_titulacion}}');
        $this->dropTable('{{%etapa_tramite}}');
        $this->dropTable('{{%modalidad_titulacion}}');
        $this->dropTable('{{%carrera}}');
    }
}