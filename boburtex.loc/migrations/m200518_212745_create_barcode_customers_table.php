<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%barcode_customers}}`.
 */
class m200518_212745_create_barcode_customers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%barcode_customers}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'code' => $this->string(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addColumn('goods_barcode', 'bc_id', $this->integer());

        // creates index for column `bc_id`
        $this->createIndex(
            '{{%idx-goods_barcode-bc_id}}',
            '{{%goods_barcode}}',
            'bc_id'
        );

        // add foreign key for table `{{%barcode_customers}}`
        $this->addForeignKey(
            '{{%fk-goods_barcode-bc_id}}',
            '{{%goods_barcode}}',
            'bc_id',
            '{{%barcode_customers}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bc_id}}`
        $this->dropForeignKey(
            '{{%fk-goods_barcode-bc_id}}',
            '{{%goods_barcode}}'
        );

        // drops index for column `bc_id`
        $this->dropIndex(
            '{{%idx-goods_barcode-bc_id}}',
            '{{%goods_barcode}}'
        );
        $this->dropColumn('goods_barcode','bc_id');
        $this->dropTable('{{%barcode_customers}}');
    }
}
