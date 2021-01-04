<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_patterns_variations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_patterns}}`
 */
class m200729_141854_create_base_patterns_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_patterns_variations}}', [
            'id' => $this->primaryKey(),
            'base_patterns_id' => $this->integer(),
            'variant_no' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `base_patterns_id`
        $this->createIndex(
            '{{%idx-base_patterns_variations-base_patterns_id}}',
            '{{%base_patterns_variations}}',
            'base_patterns_id'
        );

        // add foreign key for table `{{%base_patterns}}`
        $this->addForeignKey(
            '{{%fk-base_patterns_variations-base_patterns_id}}',
            '{{%base_patterns_variations}}',
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
            '{{%fk-base_patterns_variations-base_patterns_id}}',
            '{{%base_patterns_variations}}'
        );

        // drops index for column `base_patterns_id`
        $this->dropIndex(
            '{{%idx-base_patterns_variations-base_patterns_id}}',
            '{{%base_patterns_variations}}'
        );

        $this->dropTable('{{%base_patterns_variations}}');
    }
}
