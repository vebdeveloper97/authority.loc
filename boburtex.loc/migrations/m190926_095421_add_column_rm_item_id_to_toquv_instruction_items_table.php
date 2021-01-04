<?php

use yii\db\Migration;

/**
 * Class m190926_095421_add_column_rm_item_id_to_toquv_instruction_items_table
 */
class m190926_095421_add_column_rm_item_id_to_toquv_instruction_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_instruction_items','rm_item_id', $this->integer());

        // creates index for column `rm_item_id`
        $this->createIndex(
            '{{%idx-toquv_instruction_items-rm_item_id}}',
            '{{%toquv_instruction_items}}',
            'rm_item_id'
        );

        // add foreign key for table `{{%toquv_instruction_items}}`
        $this->addForeignKey(
            '{{%fk-toquv_instruction_items-rm_item_id}}',
            '{{%toquv_instruction_items}}',
            'rm_item_id',
            '{{%toquv_rm_order_items}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_instruction_items}}`
        $this->dropForeignKey(
            '{{%fk-toquv_instruction_items-rm_item_id}}',
            '{{%toquv_instruction_items}}'
        );

        // drops index for column `rm_item_id`
        $this->dropIndex(
            '{{%idx-toquv_instruction_items-rm_item_id}}',
            '{{%toquv_instruction_items}}'
        );

        $this->dropColumn('toquv_instruction_items','rm_item_id');
    }

}
