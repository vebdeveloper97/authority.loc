<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_instructions}}`.
 */
class m190912_172504_create_toquv_instructions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%toquv_instructions}}', [
            'id' => $this->primaryKey(),
            'toquv_order_id' => $this->integer(),
            'to_department' => $this->integer(),
            'from_department' => $this->integer(),
            'type' => $this->smallInteger()->defaultValue(1),
            // 1- low 2-normal 3 - high 4 - urgent
            'priority' => $this->smallInteger()->defaultValue(1),
            'responsible_persons' => $this->text(),
            'reg_date' => 'datetime DEFAULT NOW()',
            'add_info' => $this->text(),
            'notify' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //toquv_order_id
        $this->createIndex(
            'idx-toquv_instructions-toquv_order_id',
            '{{%toquv_instructions}}',
            'toquv_order_id'
        );

        $this->addForeignKey(
            'fk-toquv_instructions-toquv_order_id',
            '{{%toquv_instructions}}',
            'toquv_order_id',
            '{{%toquv_orders}}',
            'id'
        );

        //to_department
        $this->createIndex(
            'idx-toquv_instructions-to_department',
            '{{%toquv_instructions}}',
            'to_department'
        );

        $this->addForeignKey(
            'fk-toquv_instructions-to_department',
            '{{%toquv_instructions}}',
            'to_department',
            '{{%toquv_departments}}',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //toquv_order_id
        $this->dropForeignKey(
            'fk-toquv_instructions-toquv_order_id',
            '{{%toquv_instructions}}'
        );

        $this->dropIndex(
            'idx-toquv_instructions-toquv_order_id',
            '{{%toquv_instructions}}'
        );

        //to_department
        $this->dropForeignKey(
            'fk-toquv_instructions-to_department',
            '{{%toquv_instructions}}'
        );

        $this->dropIndex(
            'idx-toquv_instructions-to_department',
            '{{%toquv_instructions}}'
        );
        $this->dropTable('{{%toquv_instructions}}');
    }
}
