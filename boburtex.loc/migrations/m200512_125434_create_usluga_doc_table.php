<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%usluga_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_departments}}`
 * - `{{%users}}`
 * - `{{%toquv_departments}}`
 * - `{{%users}}`
 * - `{{%musteri}}`
 * - `{{%musteri}}`
 * - `{{%models_list}}`
 * - `{{%models_variations}}`
 * - `{{%pul_birligi}}`
 * - `{{%bichuv_given_rolls}}`
 */
class m200512_125434_create_usluga_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%usluga_doc}}', [
            'id' => $this->primaryKey(),
            'document_type' => $this->smallInteger(6),
            'action' => $this->smallInteger(6),
            'doc_number' => $this->string(25),
            'reg_date' => $this->dateTime(),
            'from_department' => $this->integer(),
            'from_employee' => $this->bigInteger(),
            'to_department' => $this->integer(),
            'to_employee' => $this->bigInteger(),
            'from_musteri' => $this->bigInteger(),
            'to_musteri' => $this->bigInteger(),
            'musteri_responsible' => $this->string(),
            'deadline' => $this->dateTime(),
            'accepted_date' => $this->dateTime(),
            'size_collection_id' => $this->integer(),
            'models_list_id' => $this->integer(),
            'model_var_id' => $this->integer(),
            'type' => $this->smallInteger()->defaultValue(1),
            'is_returned' => $this->tinyInteger()->defaultValue(0),
            'parent_id' => $this->integer(),
            'price' => $this->decimal(20,2),
            'pb_id' => $this->integer(),
            'nastel_no' => $this->string(30),
            'bichuv_given_rolls_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `from_department`
        $this->createIndex(
            '{{%idx-usluga_doc-from_department}}',
            '{{%usluga_doc}}',
            'from_department'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc-from_department}}',
            '{{%usluga_doc}}',
            'from_department',
            '{{%toquv_departments}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `from_employee`
        $this->createIndex(
            '{{%idx-usluga_doc-from_employee}}',
            '{{%usluga_doc}}',
            'from_employee'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc-from_employee}}',
            '{{%usluga_doc}}',
            'from_employee',
            '{{%users}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `to_department`
        $this->createIndex(
            '{{%idx-usluga_doc-to_department}}',
            '{{%usluga_doc}}',
            'to_department'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc-to_department}}',
            '{{%usluga_doc}}',
            'to_department',
            '{{%toquv_departments}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `to_employee`
        $this->createIndex(
            '{{%idx-usluga_doc-to_employee}}',
            '{{%usluga_doc}}',
            'to_employee'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc-to_employee}}',
            '{{%usluga_doc}}',
            'to_employee',
            '{{%users}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `from_musteri`
        $this->createIndex(
            '{{%idx-usluga_doc-from_musteri}}',
            '{{%usluga_doc}}',
            'from_musteri'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc-from_musteri}}',
            '{{%usluga_doc}}',
            'from_musteri',
            '{{%musteri}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `to_musteri`
        $this->createIndex(
            '{{%idx-usluga_doc-to_musteri}}',
            '{{%usluga_doc}}',
            'to_musteri'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc-to_musteri}}',
            '{{%usluga_doc}}',
            'to_musteri',
            '{{%musteri}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-usluga_doc-models_list_id}}',
            '{{%usluga_doc}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc-models_list_id}}',
            '{{%usluga_doc}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `model_var_id`
        $this->createIndex(
            '{{%idx-usluga_doc-model_var_id}}',
            '{{%usluga_doc}}',
            'model_var_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc-model_var_id}}',
            '{{%usluga_doc}}',
            'model_var_id',
            '{{%models_variations}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `pb_id`
        $this->createIndex(
            '{{%idx-usluga_doc-pb_id}}',
            '{{%usluga_doc}}',
            'pb_id'
        );

        // add foreign key for table `{{%pul_birligi}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc-pb_id}}',
            '{{%usluga_doc}}',
            'pb_id',
            '{{%pul_birligi}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `bichuv_given_rolls_id`
        $this->createIndex(
            '{{%idx-usluga_doc-bichuv_given_rolls_id}}',
            '{{%usluga_doc}}',
            'bichuv_given_rolls_id'
        );

        // add foreign key for table `{{%bichuv_given_rolls}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc-bichuv_given_rolls_id}}',
            '{{%usluga_doc}}',
            'bichuv_given_rolls_id',
            '{{%bichuv_given_rolls}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc-from_department}}',
            '{{%usluga_doc}}'
        );

        // drops index for column `from_department`
        $this->dropIndex(
            '{{%idx-usluga_doc-from_department}}',
            '{{%usluga_doc}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc-from_employee}}',
            '{{%usluga_doc}}'
        );

        // drops index for column `from_employee`
        $this->dropIndex(
            '{{%idx-usluga_doc-from_employee}}',
            '{{%usluga_doc}}'
        );

        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc-to_department}}',
            '{{%usluga_doc}}'
        );

        // drops index for column `to_department`
        $this->dropIndex(
            '{{%idx-usluga_doc-to_department}}',
            '{{%usluga_doc}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc-to_employee}}',
            '{{%usluga_doc}}'
        );

        // drops index for column `to_employee`
        $this->dropIndex(
            '{{%idx-usluga_doc-to_employee}}',
            '{{%usluga_doc}}'
        );

        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc-from_musteri}}',
            '{{%usluga_doc}}'
        );

        // drops index for column `from_musteri`
        $this->dropIndex(
            '{{%idx-usluga_doc-from_musteri}}',
            '{{%usluga_doc}}'
        );

        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc-to_musteri}}',
            '{{%usluga_doc}}'
        );

        // drops index for column `to_musteri`
        $this->dropIndex(
            '{{%idx-usluga_doc-to_musteri}}',
            '{{%usluga_doc}}'
        );

        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc-models_list_id}}',
            '{{%usluga_doc}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-usluga_doc-models_list_id}}',
            '{{%usluga_doc}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc-model_var_id}}',
            '{{%usluga_doc}}'
        );

        // drops index for column `model_var_id`
        $this->dropIndex(
            '{{%idx-usluga_doc-model_var_id}}',
            '{{%usluga_doc}}'
        );

        // drops foreign key for table `{{%pul_birligi}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc-pb_id}}',
            '{{%usluga_doc}}'
        );

        // drops index for column `pb_id`
        $this->dropIndex(
            '{{%idx-usluga_doc-pb_id}}',
            '{{%usluga_doc}}'
        );

        // drops foreign key for table `{{%bichuv_given_rolls}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc-bichuv_given_rolls_id}}',
            '{{%usluga_doc}}'
        );

        // drops index for column `bichuv_given_rolls_id`
        $this->dropIndex(
            '{{%idx-usluga_doc-bichuv_given_rolls_id}}',
            '{{%usluga_doc}}'
        );

        $this->dropTable('{{%usluga_doc}}');
    }
}
