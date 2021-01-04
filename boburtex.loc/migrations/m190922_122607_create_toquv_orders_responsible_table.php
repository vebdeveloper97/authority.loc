<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_orders_responsible}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_orders}}`
 * - `{{%users}}`
 */
class m190922_122607_create_toquv_orders_responsible_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_orders_responsible}}', [
            'id' => $this->primaryKey(),
            'toquv_orders_id' => $this->integer(),
            'users_id' => $this->bigInteger(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `toquv_orders_id`
        $this->createIndex(
            '{{%idx-toquv_orders_responsible-toquv_orders_id}}',
            '{{%toquv_orders_responsible}}',
            'toquv_orders_id'
        );

        // add foreign key for table `{{%toquv_orders}}`
        $this->addForeignKey(
            '{{%fk-toquv_orders_responsible-toquv_orders_id}}',
            '{{%toquv_orders_responsible}}',
            'toquv_orders_id',
            '{{%toquv_orders}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-toquv_orders_responsible-users_id}}',
            '{{%toquv_orders_responsible}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-toquv_orders_responsible-users_id}}',
            '{{%toquv_orders_responsible}}',
            'users_id',
            '{{%users}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_orders}}`
        $this->dropForeignKey(
            '{{%fk-toquv_orders_responsible-toquv_orders_id}}',
            '{{%toquv_orders_responsible}}'
        );

        // drops index for column `toquv_orders_id`
        $this->dropIndex(
            '{{%idx-toquv_orders_responsible-toquv_orders_id}}',
            '{{%toquv_orders_responsible}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-toquv_orders_responsible-users_id}}',
            '{{%toquv_orders_responsible}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-toquv_orders_responsible-users_id}}',
            '{{%toquv_orders_responsible}}'
        );

        $this->dropTable('{{%toquv_orders_responsible}}');
    }
}
