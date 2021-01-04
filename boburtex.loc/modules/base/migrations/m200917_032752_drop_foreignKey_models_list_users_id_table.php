<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%foreignKey_models_list_users_id}}`.
 */
class m200917_032752_drop_foreignKey_models_list_users_id_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-models_list-users_id}}',
            '{{%models_list}}'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-models_list-users_id}}',
            '{{%models_list}}',
            'users_id',
            '{{%users}}',
            'id'
        );

    }
}
