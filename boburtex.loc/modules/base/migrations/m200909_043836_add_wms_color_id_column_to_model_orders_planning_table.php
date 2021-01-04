<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_planning}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wms_color}}`
 */
class m200909_043836_add_wms_color_id_column_to_model_orders_planning_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_planning}}', 'wms_color_id', $this->integer());

        // creates index for column `wms_color_id`
        $this->createIndex(
            '{{%idx-model_orders_planning-wms_color_id}}',
            '{{%model_orders_planning}}',
            'wms_color_id'
        );

        // add foreign key for table `{{%wms_color}}`
        $this->addForeignKey(
            '{{%fk-model_orders_planning-wms_color_id}}',
            '{{%model_orders_planning}}',
            'wms_color_id',
            '{{%wms_color}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%wms_color}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_planning-wms_color_id}}',
            '{{%model_orders_planning}}'
        );

        // drops index for column `wms_color_id`
        $this->dropIndex(
            '{{%idx-model_orders_planning-wms_color_id}}',
            '{{%model_orders_planning}}'
        );

        $this->dropColumn('{{%model_orders_planning}}', 'wms_color_id');
    }
}
