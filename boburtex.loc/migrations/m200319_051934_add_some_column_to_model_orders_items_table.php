<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%brend}}`
 */
class m200319_051934_add_some_column_to_model_orders_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items}}', 'finish_en', $this->string(30));
        $this->addColumn('{{%model_orders_items}}', 'finish_gramaj', $this->string(30));
        $this->addColumn('{{%model_orders_items}}', 'brend_id', $this->integer());

        // creates index for column `brend_id`
        $this->createIndex(
            '{{%idx-model_orders_items-brend_id}}',
            '{{%model_orders_items}}',
            'brend_id'
        );

        // add foreign key for table `{{%brend}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items-brend_id}}',
            '{{%model_orders_items}}',
            'brend_id',
            '{{%brend}}',
            'id',
            'CASCADE'
        );
        $this->execute("ALTER TABLE `users_info` CHANGE `rfid_key` `rfid_key` VARCHAR(11) NULL DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%brend}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items-brend_id}}',
            '{{%model_orders_items}}'
        );

        // drops index for column `brend_id`
        $this->dropIndex(
            '{{%idx-model_orders_items-brend_id}}',
            '{{%model_orders_items}}'
        );

        $this->dropColumn('{{%model_orders_items}}', 'finish_en');
        $this->dropColumn('{{%model_orders_items}}', 'finish_gramaj');
        $this->dropColumn('{{%model_orders_items}}', 'brend_id');
    }
}
