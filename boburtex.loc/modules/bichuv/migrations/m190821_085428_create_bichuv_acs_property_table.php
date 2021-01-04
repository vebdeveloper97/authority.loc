<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_acs_property}}`.
 */
class m190821_085428_create_bichuv_acs_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_acs_property}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(200),
            'type' => $this->smallInteger(2)->defaultValue(1),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11)
        ]);

        $this->addForeignKey(
            'fk-bichuv_acs-property_id',
            'bichuv_acs',
            'property_id',
            'bichuv_acs_property',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-bichuv_acs-property_id',
            'bichuv_acs'
        );

        $this->dropTable('{{%bichuv_acs_property}}');
    }
}
