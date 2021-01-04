<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_planning}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%color}}`
 */
class m200317_125015_add_color_id_column_to_model_orders_planning_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_planning}}', 'color_id', $this->integer()->after('color_pantone_id'));

        // creates index for column `color_id`
        $this->createIndex(
            '{{%idx-model_orders_planning-color_id}}',
            '{{%model_orders_planning}}',
            'color_id'
        );

        // add foreign key for table `{{%color}}`
        $this->addForeignKey(
            '{{%fk-model_orders_planning-color_id}}',
            '{{%model_orders_planning}}',
            'color_id',
            '{{%color}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%color}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_planning-color_id}}',
            '{{%model_orders_planning}}'
        );

        // drops index for column `color_id`
        $this->dropIndex(
            '{{%idx-model_orders_planning-color_id}}',
            '{{%model_orders_planning}}'
        );
        $this->dropColumn('{{%model_orders_planning}}', 'color_id');
    }
}
