<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_accepted_mato_from_production}}`.
 */
class m200104_075302_create_bichuv_accepted_mato_from_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_accepted_mato_from_production}}', [
            'id' => $this->primaryKey(),
            'bichuv_given_roll_id' => $this->integer(),
            'quantity' => $this->decimal(20,3),
            'type' => $this->smallInteger(1)->defaultValue(1),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `bichuv_given_roll_id`
        $this->createIndex(
            '{{%idx-bichuv_accepted_mato_from_production-bichuv_given_roll_id}}',
            '{{%bichuv_accepted_mato_from_production}}',
            'bichuv_given_roll_id'
        );

        // add foreign key for table `{{%bichuv_given_roll_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_accepted_mato_from_production-bichuv_given_roll_id}}',
            '{{%bichuv_accepted_mato_from_production}}',
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
            '{{%fk-bichuv_accepted_mato_from_production-bichuv_given_roll_id}}',
            '{{%bichuv_accepted_mato_from_production}}'
        );

        // drops index for column `bichuv_given_roll_id`
        $this->dropIndex(
            '{{%idx-bichuv_accepted_mato_from_production-bichuv_given_roll_id}}',
            '{{%bichuv_accepted_mato_from_production}}'
        );
        $this->dropTable('{{%bichuv_accepted_mato_from_production}}');
    }
}
