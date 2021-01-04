<?php

use yii\db\Migration;

/**
 * Class m191216_105751_craete_bichuv_given_roll_items_table
 */
class m191216_105752_create_bichuv_given_roll_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_given_roll_items}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer(),
            'bichuv_given_roll_id' => $this->integer(),
            'quantity' => $this->decimal(10,3),
            'type' => $this->smallInteger(2)->defaultValue(1),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger(2)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `bichuv_given_roll_id`
        $this->createIndex(
            '{{%idx-bichuv_given_roll_items-bichuv_given_roll_id}}',
            '{{%bichuv_given_roll_items}}',
            'bichuv_given_roll_id'
        );

        // add foreign key for table `{{%bichuv_given_roll_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_roll_items-bichuv_given_roll_id}}',
            '{{%bichuv_given_roll_items}}',
            'bichuv_given_roll_id',
            '{{%bichuv_given_rolls}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_given_roll_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_given_roll_items-bichuv_given_roll_id}}',
            '{{%bichuv_given_roll_items}}'
        );

        // drops index for column `bichuv_given_roll_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_roll_items-bichuv_given_roll_id}}',
            '{{%bichuv_given_roll_items}}'
        );

        $this->dropTable('bichuv_given_roll_items');
    }
}
