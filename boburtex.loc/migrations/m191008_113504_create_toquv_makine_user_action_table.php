<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_makine_user_action}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_makine}}`
 * - `{{%users}}`
 */
class m191008_113504_create_toquv_makine_user_action_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_makine_user_action}}', [
            'id' => $this->primaryKey(),
            'toquv_makine_id' => $this->integer(),
            'users_id' => $this->bigInteger(),
            'next_users_id' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `toquv_makine_id`
        $this->createIndex(
            '{{%idx-toquv_makine_user_action-toquv_makine_id}}',
            '{{%toquv_makine_user_action}}',
            'toquv_makine_id'
        );

        // add foreign key for table `{{%toquv_makine}}`
        $this->addForeignKey(
            '{{%fk-toquv_makine_user_action-toquv_makine_id}}',
            '{{%toquv_makine_user_action}}',
            'toquv_makine_id',
            '{{%toquv_makine}}',
            'id',
            'CASCADE'
        );

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-toquv_makine_user_action-users_id}}',
            '{{%toquv_makine_user_action}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-toquv_makine_user_action-users_id}}',
            '{{%toquv_makine_user_action}}',
            'users_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_makine}}`
        $this->dropForeignKey(
            '{{%fk-toquv_makine_user_action-toquv_makine_id}}',
            '{{%toquv_makine_user_action}}'
        );

        // drops index for column `toquv_makine_id`
        $this->dropIndex(
            '{{%idx-toquv_makine_user_action-toquv_makine_id}}',
            '{{%toquv_makine_user_action}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-toquv_makine_user_action-users_id}}',
            '{{%toquv_makine_user_action}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-toquv_makine_user_action-users_id}}',
            '{{%toquv_makine_user_action}}'
        );

        $this->dropTable('{{%toquv_makine_user_action}}');
    }
}
