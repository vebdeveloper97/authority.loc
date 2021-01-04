<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%musteri}}`
 */
class m190923_194625_create_model_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders}}', [
            'id' => $this->primaryKey(),
            'doc_number' => $this->string(50),
            'musteri_id' => $this->bigInteger(),
            'reg_date' => $this->dateTime(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-model_orders-musteri_id}}',
            '{{%model_orders}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-model_orders-musteri_id}}',
            '{{%model_orders}}',
            'musteri_id',
            '{{%musteri}}',
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
        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-model_orders-musteri_id}}',
            '{{%model_orders}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-model_orders-musteri_id}}',
            '{{%model_orders}}'
        );

        $this->dropTable('{{%model_orders}}');
    }
}
