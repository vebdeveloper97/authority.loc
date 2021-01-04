<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_responsible}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 * - `{{%users}}`
 */
class m190923_194921_create_model_orders_responsible_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_responsible}}', [
            'id' => $this->primaryKey(),
            'model_orders_id' => $this->integer(),
            'users_id' => $this->bigInteger(),
        ]);

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-model_orders_responsible-model_orders_id}}',
            '{{%model_orders_responsible}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-model_orders_responsible-model_orders_id}}',
            '{{%model_orders_responsible}}',
            'model_orders_id',
            '{{%model_orders}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-model_orders_responsible-users_id}}',
            '{{%model_orders_responsible}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-model_orders_responsible-users_id}}',
            '{{%model_orders_responsible}}',
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
        // drops foreign key for table `{{%model_orders}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_responsible-model_orders_id}}',
            '{{%model_orders_responsible}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-model_orders_responsible-model_orders_id}}',
            '{{%model_orders_responsible}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_responsible-users_id}}',
            '{{%model_orders_responsible}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-model_orders_responsible-users_id}}',
            '{{%model_orders_responsible}}'
        );

        $this->dropTable('{{%model_orders_responsible}}');
    }
}
