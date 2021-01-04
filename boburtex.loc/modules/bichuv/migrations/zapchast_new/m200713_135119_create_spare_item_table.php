<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spare_item}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%unit}}`
 */
class m200713_135119_create_spare_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spare_item}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'sku' => $this->char(50),
            'unit_id' => $this->integer(),
            'barcode' => $this->char(100),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-spare_item-unit_id}}',
            '{{%spare_item}}',
            'unit_id'
        );

        // add foreign key for table `{{%unit}}`
        $this->addForeignKey(
            '{{%fk-spare_item-unit_id}}',
            '{{%spare_item}}',
            'unit_id',
            '{{%unit}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-spare_item-unit_id}}',
            '{{%spare_item}}'
        );

        // drops index for column `unit_id`
        $this->dropIndex(
            '{{%idx-spare_item-unit_id}}',
            '{{%spare_item}}'
        );

        $this->dropTable('{{%spare_item}}');
    }
}
