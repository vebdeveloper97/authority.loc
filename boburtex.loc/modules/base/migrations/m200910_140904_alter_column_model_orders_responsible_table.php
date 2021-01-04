<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_responsible}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_employee}}`
 */
class m200910_140904_alter_column_model_orders_responsible_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-model_orders_responsible-users_id}}',
            '{{%model_orders_responsible}}'
        );

        $this->dropColumn('{{%model_orders_responsible}}', 'users_id');

        $this->addColumn('{{%model_orders_responsible}}', 'users_id', $this->integer());

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-model_orders_responsible-users_id}}',
            '{{%model_orders_responsible}}',
            'users_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-model_orders_responsible-users_id}}',
            '{{%model_orders_responsible}}',
            'users_id',
            '{{%hr_employee}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_responsible-users_id}}',
            '{{%model_orders_responsible}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-model_orders_responsible-users_id}}',
            '{{%model_orders_responsible}}'
        );

        $this->dropColumn('{{%model_orders_responsible}}', 'users_id');

        $this->addColumn('{{%model_orders_responsible}}', 'users_id', $this->integer());

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-model_orders_responsible-users_id}}',
            '{{%model_orders_responsible}}',
            'users_id'
        );
    }
}
