<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_kalite}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_instructions}}`
 * - `{{%toquv_rm_order}}`
 * - `{{%sort_name}}`
 */
class m191008_110558_create_toquv_kalite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_kalite}}', [
            'id' => $this->primaryKey(),
            'toquv_instructions_id' => $this->integer(),
            'toquv_rm_order_id' => $this->integer(),
            'toquv_makine_id' => $this->integer(),
            'user_id' => $this->integer(),
            'quantity' => $this->decimal(20,2),
            'sort_name_id' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `toquv_instructions_id`
        $this->createIndex(
            '{{%idx-toquv_kalite-toquv_instructions_id}}',
            '{{%toquv_kalite}}',
            'toquv_instructions_id'
        );

        // add foreign key for table `{{%toquv_instructions}}`
        $this->addForeignKey(
            '{{%fk-toquv_kalite-toquv_instructions_id}}',
            '{{%toquv_kalite}}',
            'toquv_instructions_id',
            '{{%toquv_instructions}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // creates index for column `toquv_rm_order_id`
        $this->createIndex(
            '{{%idx-toquv_kalite-toquv_rm_order_id}}',
            '{{%toquv_kalite}}',
            'toquv_rm_order_id'
        );

        // add foreign key for table `{{%toquv_rm_order}}`
        $this->addForeignKey(
            '{{%fk-toquv_kalite-toquv_rm_order_id}}',
            '{{%toquv_kalite}}',
            'toquv_rm_order_id',
            '{{%toquv_rm_order}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // creates index for column `sort_name_id`
        $this->createIndex(
            '{{%idx-toquv_kalite-sort_name_id}}',
            '{{%toquv_kalite}}',
            'sort_name_id'
        );

        // add foreign key for table `{{%sort_name}}`
        $this->addForeignKey(
            '{{%fk-toquv_kalite-sort_name_id}}',
            '{{%toquv_kalite}}',
            'sort_name_id',
            '{{%sort_name}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_instructions}}`
        $this->dropForeignKey(
            '{{%fk-toquv_kalite-toquv_instructions_id}}',
            '{{%toquv_kalite}}'
        );

        // drops index for column `toquv_instructions_id`
        $this->dropIndex(
            '{{%idx-toquv_kalite-toquv_instructions_id}}',
            '{{%toquv_kalite}}'
        );

        // drops foreign key for table `{{%toquv_rm_order}}`
        $this->dropForeignKey(
            '{{%fk-toquv_kalite-toquv_rm_order_id}}',
            '{{%toquv_kalite}}'
        );

        // drops index for column `toquv_rm_order_id`
        $this->dropIndex(
            '{{%idx-toquv_kalite-toquv_rm_order_id}}',
            '{{%toquv_kalite}}'
        );

        // drops foreign key for table `{{%sort_name}}`
        $this->dropForeignKey(
            '{{%fk-toquv_kalite-sort_name_id}}',
            '{{%toquv_kalite}}'
        );

        // drops index for column `sort_name_id`
        $this->dropIndex(
            '{{%idx-toquv_kalite-sort_name_id}}',
            '{{%toquv_kalite}}'
        );

        $this->dropTable('{{%toquv_kalite}}');
    }
}
