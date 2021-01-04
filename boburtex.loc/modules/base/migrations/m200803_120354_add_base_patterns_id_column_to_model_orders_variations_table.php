<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_variations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_patterns}}`
 */
class m200803_120354_add_base_patterns_id_column_to_model_orders_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_variations}}', 'base_patterns_id', $this->integer());

        // creates index for column `base_patterns_id`
        $this->createIndex(
            '{{%idx-model_orders_variations-base_patterns_id}}',
            '{{%model_orders_variations}}',
            'base_patterns_id'
        );

        // add foreign key for table `{{%base_patterns}}`
        $this->addForeignKey(
            '{{%fk-model_orders_variations-base_patterns_id}}',
            '{{%model_orders_variations}}',
            'base_patterns_id',
            '{{%base_patterns}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_patterns}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_variations-base_patterns_id}}',
            '{{%model_orders_variations}}'
        );

        // drops index for column `base_patterns_id`
        $this->dropIndex(
            '{{%idx-model_orders_variations-base_patterns_id}}',
            '{{%model_orders_variations}}'
        );

        $this->dropColumn('{{%model_orders_variations}}', 'base_patterns_id');
    }
}
