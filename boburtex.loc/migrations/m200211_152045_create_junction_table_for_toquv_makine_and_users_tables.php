<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_makine_users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_makine}}`
 * - `{{%users}}`
 */
class m200211_152045_create_junction_table_for_toquv_makine_and_users_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_makine_users}}', [
            'toquv_makine_id' => $this->integer(),
            'users_id' => $this->bigInteger(),
            'PRIMARY KEY(toquv_makine_id, users_id)',
        ]);

        // creates index for column `toquv_makine_id`
        $this->createIndex(
            '{{%idx-toquv_makine_users-toquv_makine_id}}',
            '{{%toquv_makine_users}}',
            'toquv_makine_id'
        );

        // add foreign key for table `{{%toquv_makine}}`
        $this->addForeignKey(
            '{{%fk-toquv_makine_users-toquv_makine_id}}',
            '{{%toquv_makine_users}}',
            'toquv_makine_id',
            '{{%toquv_makine}}',
            'id',
            'CASCADE'
        );

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-toquv_makine_users-users_id}}',
            '{{%toquv_makine_users}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-toquv_makine_users-users_id}}',
            '{{%toquv_makine_users}}',
            'users_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
        $this->upsert('{{%toquv_rm_defects}}',['id'=>9,'name'=>'Nabor'],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_makine}}`
        $this->dropForeignKey(
            '{{%fk-toquv_makine_users-toquv_makine_id}}',
            '{{%toquv_makine_users}}'
        );

        // drops index for column `toquv_makine_id`
        $this->dropIndex(
            '{{%idx-toquv_makine_users-toquv_makine_id}}',
            '{{%toquv_makine_users}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-toquv_makine_users-users_id}}',
            '{{%toquv_makine_users}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-toquv_makine_users-users_id}}',
            '{{%toquv_makine_users}}'
        );

        $this->dropTable('{{%toquv_makine_users}}');
    }
}
