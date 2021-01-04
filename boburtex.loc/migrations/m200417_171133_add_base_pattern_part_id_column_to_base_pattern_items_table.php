<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%base_pattern_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_pattern_part}}`
 */
class m200417_171133_add_base_pattern_part_id_column_to_base_pattern_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%base_pattern_items}}', 'base_pattern_part_id', $this->integer());

        // creates index for column `base_pattern_part_id`
        $this->createIndex(
            '{{%idx-base_pattern_items-base_pattern_part_id}}',
            '{{%base_pattern_items}}',
            'base_pattern_part_id'
        );

        // add foreign key for table `{{%base_pattern_part}}`
        $this->addForeignKey(
            '{{%fk-base_pattern_items-base_pattern_part_id}}',
            '{{%base_pattern_items}}',
            'base_pattern_part_id',
            '{{%base_pattern_part}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_pattern_part}}`
        $this->dropForeignKey(
            '{{%fk-base_pattern_items-base_pattern_part_id}}',
            '{{%base_pattern_items}}'
        );

        // drops index for column `base_pattern_part_id`
        $this->dropIndex(
            '{{%idx-base_pattern_items-base_pattern_part_id}}',
            '{{%base_pattern_items}}'
        );

        $this->dropColumn('{{%base_pattern_items}}', 'base_pattern_part_id');
    }
}
