<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_acs}}`.
 */
class m190821_074225_create_bichuv_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_acs}}', [
            'id' => $this->primaryKey(),
            'sku' => $this->string(100),
            'name' => $this->string(200),
            'property_id' => $this->integer(11),
            'unit_id' => $this->integer(11),
            'barcode' => $this->string(100),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11)
        ]);

        $this->createIndex(
            'idx-bichuv_acs-property_id',
            'bichuv_acs',
            'property_id'
        );

        $this->createIndex(
            'idx-bichuv_acs-unit_id',
            'bichuv_acs',
            'unit_id'
        );

        $this->addForeignKey(
            'fk-bichuv_acs-unit_id',
            'bichuv_acs',
            'unit_id',
            'unit',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-bichuv_acs-unit_id',
            'bichuv_acs'
        );

        $this->dropIndex(
            'idx-bichuv_acs-unit_id',
            'bichuv_acs'
        );

        $this->dropIndex(
            'idx-bichuv_acs-property_id',
            'bichuv_acs'
        );

        $this->dropTable('{{%bichuv_acs}}');
    }
}
