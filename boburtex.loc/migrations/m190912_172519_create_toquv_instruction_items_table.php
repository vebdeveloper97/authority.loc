<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_instruction_items}}`.
 */
class m190912_172519_create_toquv_instruction_items_table extends Migration
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
        $this->createTable('{{%toquv_instruction_items}}', [
            'id' => $this->primaryKey(),
            'toquv_instruction_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger()->defaultValue(1),
            'quantity' => $this->decimal(20,3),
            'fact' => $this->decimal(20,3),
            'add_info' => $this->text(),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //toquv_instruction_id
        $this->createIndex(
            'idx-toquv_instruction_items-toquv_instruction_id',
            '{{%toquv_instruction_items}}',
            'toquv_instruction_id'
        );

        $this->addForeignKey(
            'fk-toquv_instruction_items-toquv_instruction_id',
            '{{%toquv_instruction_items}}',
            'toquv_instruction_id',
            '{{%toquv_instructions}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //toquv_instruction_id
        $this->dropForeignKey(
            'fk-toquv_instruction_items-toquv_instruction_id',
            '{{%toquv_instruction_items}}'
        );

        $this->dropIndex(
            'idx-toquv_instruction_items-toquv_instruction_id',
            '{{%toquv_instruction_items}}'
        );
        $this->dropTable('{{%toquv_instruction_items}}');
    }
}
