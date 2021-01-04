<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%foreign_keys_for_models_list}}`.
 */
class m200401_052922_drop_foreign_keys_for_models_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //type_child_id
        $this->dropForeignKey(
            'fk-models_list-type_child_id',
            'models_list'
        );

        $this->dropIndex(
            'idx-models_list-type_child_id',
            'models_list'
        );

        //type_2x_id
        $this->dropForeignKey(
            'fk-models_list-type_2x_id',
            'models_list'
        );

        $this->dropIndex(
            'idx-models_list-type_2x_id',
            'models_list'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //type_child_id
        $this->createIndex(
            'idx-models_list-type_child_id',
            'models_list',
            'type_child_id'
        );

        $this->addForeignKey(
            'fk-models_list-type_child_id',
            'models_list',
            'type_child_id',
            'model_types',
            'id'
        );

        //type_2x_id
        $this->createIndex(
            'idx-models_list-type_2x_id',
            'models_list',
            'type_2x_id'
        );

        $this->addForeignKey(
            'fk-models_list-type_2x_id',
            'models_list',
            'type_2x_id',
            'model_types',
            'id'
        );
    }
}
