<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_list}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_patterns}}`
 */
class m200304_113152_add_base_pattern_id_column_to_models_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_list}}', 'base_pattern_id', $this->integer());

        // creates index for column `base_pattern_id`
        $this->createIndex(
            '{{%idx-models_list-base_pattern_id}}',
            '{{%models_list}}',
            'base_pattern_id'
        );

        // add foreign key for table `{{%base_pattern}}`
        $this->addForeignKey(
            '{{%fk-models_list-base_pattern_id}}',
            '{{%models_list}}',
            'base_pattern_id',
            '{{%base_patterns}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_patterns}}`
        $this->dropForeignKey(
            '{{%fk-models_list-base_pattern_id}}',
            '{{%models_list}}'
        );

        // drops index for column `base_pattern_id`
        $this->dropIndex(
            '{{%idx-models_list-base_pattern_id}}',
            '{{%models_list}}'
        );

        $this->dropColumn('{{%models_list}}', 'base_pattern_id');
    }
}
