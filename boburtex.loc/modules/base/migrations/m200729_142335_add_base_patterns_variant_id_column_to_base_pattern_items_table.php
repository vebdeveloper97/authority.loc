<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%base_pattern_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_patterns_variations}}`
 */
class m200729_142335_add_base_patterns_variant_id_column_to_base_pattern_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%base_pattern_items}}', 'base_patterns_variant_id', $this->integer());

        // creates index for column `base_patterns_variant_id`
        $this->createIndex(
            '{{%idx-base_pattern_items-base_patterns_variant_id}}',
            '{{%base_pattern_items}}',
            'base_patterns_variant_id'
        );

        // add foreign key for table `{{%base_patterns_variations}}`
        $this->addForeignKey(
            '{{%fk-base_pattern_items-base_patterns_variant_id}}',
            '{{%base_pattern_items}}',
            'base_patterns_variant_id',
            '{{%base_patterns_variations}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_patterns_variations}}`
        $this->dropForeignKey(
            '{{%fk-base_pattern_items-base_patterns_variant_id}}',
            '{{%base_pattern_items}}'
        );

        // drops index for column `base_patterns_variant_id`
        $this->dropIndex(
            '{{%idx-base_pattern_items-base_patterns_variant_id}}',
            '{{%base_pattern_items}}'
        );

        $this->dropColumn('{{%base_pattern_items}}', 'base_patterns_variant_id');
    }
}
