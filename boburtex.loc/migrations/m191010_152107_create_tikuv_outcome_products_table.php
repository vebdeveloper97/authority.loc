<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_outcome_products}}`.
 */
class m191010_152107_create_tikuv_outcome_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_outcome_products}}', [
            'id' => $this->primaryKey(),
            'order_no' => $this->string(100),
            'model_no' => $this->string(50),
            'color_code' => $this->string(50),
            'size_type_id' => $this->integer(),
            'size_id' => $this->integer(),
            'pechat' => $this->string(100),
            'barcode' => $this->string(50),
            'quantity' => $this->integer(),
            'accepted_quantity' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->integer()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `size_type_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products-size_type_id}}',
            '{{%tikuv_outcome_products}}',
            'size_type_id'
        );

        // add foreign key for table `{{%size_type}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products-size_type_id}}',
            '{{%tikuv_outcome_products}}',
            'size_type_id',
            '{{%size_type}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products-size_id}}',
            '{{%tikuv_outcome_products}}',
            'size_id'
        );

        // add foreign key for table `{{%size}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products-size_id}}',
            '{{%tikuv_outcome_products}}',
            'size_id',
            '{{%size}}',
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

        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products-size_id}}',
            '{{%tikuv_outcome_products}}'
        );

        $this->dropIndex(
            '{{%idx-tikuv_outcome_products-size_id}}',
            '{{%tikuv_outcome_products}}'
        );

        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products-size_type_id}}',
            '{{%tikuv_outcome_products}}'
        );

        $this->dropIndex(
            '{{%idx-tikuv_outcome_products-size_type_id}}',
            '{{%tikuv_outcome_products}}'
        );

        $this->dropTable('{{%tikuv_outcome_products}}');
    }
}
