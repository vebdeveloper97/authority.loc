<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%moi_rel_dept}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 */
class m200318_092622_add_model_orders_id_column_to_moi_rel_dept_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%moi_rel_dept}}', 'model_orders_id', $this->integer()->after('name'));

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-moi_rel_dept-model_orders_id}}',
            '{{%moi_rel_dept}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-moi_rel_dept-model_orders_id}}',
            '{{%moi_rel_dept}}',
            'model_orders_id',
            '{{%model_orders}}',
            'id',
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
            '{{%fk-moi_rel_dept-model_orders_id}}',
            '{{%moi_rel_dept}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-moi_rel_dept-model_orders_id}}',
            '{{%moi_rel_dept}}'
        );

        $this->dropColumn('{{%moi_rel_dept}}', 'model_orders_id');
    }
}
