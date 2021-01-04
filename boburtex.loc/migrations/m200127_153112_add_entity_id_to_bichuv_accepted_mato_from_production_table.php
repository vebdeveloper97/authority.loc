<?php

use yii\db\Migration;

/**
 * Class m200127_153112_add_entity_id_to_bichuv_accepted_mato_from_production_table
 */
class m200127_153112_add_entity_id_to_bichuv_accepted_mato_from_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_accepted_mato_from_production', 'entity_id', $this->integer());

        // creates index for column `entity_id`
        $this->createIndex(
            '{{%idx-bichuv_accepted_mato_from_production-entity_id}}',
            '{{%bichuv_accepted_mato_from_production}}',
            'entity_id'
        );
        $this->addColumn('bichuv_beka','bichuv_given_roll_id', $this->integer());

        // creates index for column `bichuv_given_roll_id`
        $this->createIndex(
            '{{%idx-bichuv_beka-bichuv_given_roll_id}}',
            '{{%bichuv_beka}}',
            'bichuv_given_roll_id'
        );

        // add foreign key for table `{{%product}}`
        $this->addForeignKey(
            '{{%fk-bichuv_beka-bichuv_given_roll_id}}',
            '{{%bichuv_beka}}',
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
        // drops index for column `bichuv_accepted_mato_from_production`
        $this->dropIndex(
            '{{%idx-bichuv_accepted_mato_from_production-entity_id}}',
            '{{%bichuv_accepted_mato_from_production}}'
        );

        //drops foreign key for table `{{%bichuv_given_roll_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_beka-bichuv_given_roll_id}}',
            '{{%bichuv_beka}}'
        );

        // drops index for column `bichuv_given_roll_id`
        $this->dropIndex(
            '{{%idx-bichuv_beka-bichuv_given_roll_id}}',
            '{{%bichuv_beka}}'
        );

        $this->dropColumn('bichuv_accepted_mato_from_production', 'entity_id');
        $this->dropColumn('bichuv_beka','bichuv_given_roll_id');
    }
}
