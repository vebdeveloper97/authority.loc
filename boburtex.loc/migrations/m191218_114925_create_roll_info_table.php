<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%roll_info}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_instruction_rm}}`
 * - `{{%model_orders_items}}`
 * - `{{%toquv_kalite}}`
 * - `{{%toquv_departments}}`
 * - `{{%sort_name}}`
 */
class m191218_114925_create_roll_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%roll_info}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(30),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger(),
            'quantity' => $this->float(),
            'unit_id' => $this->integer()->defaultValue(2),
            'tir_id' => $this->integer(),
            'moi_id' => $this->integer(),
            'toquv_kalite_id' => $this->integer(),
            'toquv_departments_id' => $this->integer(),
            'old_departments_id' => $this->integer(),
            'sort_name_id' => $this->integer(),
            'accept_date' => $this->timeStamp(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `tir_id`
        $this->createIndex(
            '{{%idx-roll_info-tir_id}}',
            '{{%roll_info}}',
            'tir_id'
        );

        // add foreign key for table `{{%toquv_instruction_rm}}`
        $this->addForeignKey(
            '{{%fk-roll_info-tir_id}}',
            '{{%roll_info}}',
            'tir_id',
            '{{%toquv_instruction_rm}}',
            'id',
            'CASCADE'
        );

        // creates index for column `moi_id`
        $this->createIndex(
            '{{%idx-roll_info-moi_id}}',
            '{{%roll_info}}',
            'moi_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-roll_info-moi_id}}',
            '{{%roll_info}}',
            'moi_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `toquv_kalite_id`
        $this->createIndex(
            '{{%idx-roll_info-toquv_kalite_id}}',
            '{{%roll_info}}',
            'toquv_kalite_id'
        );

        // add foreign key for table `{{%toquv_kalite}}`
        $this->addForeignKey(
            '{{%fk-roll_info-toquv_kalite_id}}',
            '{{%roll_info}}',
            'toquv_kalite_id',
            '{{%toquv_kalite}}',
            'id',
            'CASCADE'
        );

        // creates index for column `toquv_departments_id`
        $this->createIndex(
            '{{%idx-roll_info-toquv_departments_id}}',
            '{{%roll_info}}',
            'toquv_departments_id'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-roll_info-toquv_departments_id}}',
            '{{%roll_info}}',
            'toquv_departments_id',
            '{{%toquv_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `sort_name_id`
        $this->createIndex(
            '{{%idx-roll_info-sort_name_id}}',
            '{{%roll_info}}',
            'sort_name_id'
        );

        // add foreign key for table `{{%sort_name}}`
        $this->addForeignKey(
            '{{%fk-roll_info-sort_name_id}}',
            '{{%roll_info}}',
            'sort_name_id',
            '{{%sort_name}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_instruction_rm}}`
        $this->dropForeignKey(
            '{{%fk-roll_info-tir_id}}',
            '{{%roll_info}}'
        );

        // drops index for column `tir_id`
        $this->dropIndex(
            '{{%idx-roll_info-tir_id}}',
            '{{%roll_info}}'
        );

        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-roll_info-moi_id}}',
            '{{%roll_info}}'
        );

        // drops index for column `moi_id`
        $this->dropIndex(
            '{{%idx-roll_info-moi_id}}',
            '{{%roll_info}}'
        );

        // drops foreign key for table `{{%toquv_kalite}}`
        $this->dropForeignKey(
            '{{%fk-roll_info-toquv_kalite_id}}',
            '{{%roll_info}}'
        );

        // drops index for column `toquv_kalite_id`
        $this->dropIndex(
            '{{%idx-roll_info-toquv_kalite_id}}',
            '{{%roll_info}}'
        );

        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-roll_info-toquv_departments_id}}',
            '{{%roll_info}}'
        );

        // drops index for column `toquv_departments_id`
        $this->dropIndex(
            '{{%idx-roll_info-toquv_departments_id}}',
            '{{%roll_info}}'
        );

        // drops foreign key for table `{{%sort_name}}`
        $this->dropForeignKey(
            '{{%fk-roll_info-sort_name_id}}',
            '{{%roll_info}}'
        );

        // drops index for column `sort_name_id`
        $this->dropIndex(
            '{{%idx-roll_info-sort_name_id}}',
            '{{%roll_info}}'
        );

        $this->dropTable('{{%roll_info}}');
    }
}
