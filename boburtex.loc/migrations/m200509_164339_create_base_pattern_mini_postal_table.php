<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_pattern_mini_postal}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_patterns}}`
 */
class m200509_164339_create_base_pattern_mini_postal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_pattern_mini_postal}}', [
            'id' => $this->primaryKey(),
            'base_patterns_id' => $this->integer(),
            'loss' => $this->double(),
            'name' => $this->string(),
            'size' => $this->integer(),
            'extension' => $this->string(10),
            'type' => $this->string(120),
            'path' => $this->string(),
        ]);

        // creates index for column `base_patterns_id`
        $this->createIndex(
            '{{%idx-base_pattern_mini_postal-base_patterns_id}}',
            '{{%base_pattern_mini_postal}}',
            'base_patterns_id'
        );

        // add foreign key for table `{{%base_patterns}}`
        $this->addForeignKey(
            '{{%fk-base_pattern_mini_postal-base_patterns_id}}',
            '{{%base_pattern_mini_postal}}',
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
            '{{%fk-base_pattern_mini_postal-base_patterns_id}}',
            '{{%base_pattern_mini_postal}}'
        );

        // drops index for column `base_patterns_id`
        $this->dropIndex(
            '{{%idx-base_pattern_mini_postal-base_patterns_id}}',
            '{{%base_pattern_mini_postal}}'
        );

        $this->dropTable('{{%base_pattern_mini_postal}}');
    }
}
