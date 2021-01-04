<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_pattern_mini_postal_sizes}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_pattern_mini_postal}}`
 */
class m200509_164607_create_base_pattern_mini_postal_sizes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_pattern_mini_postal_sizes}}', [
            'id' => $this->primaryKey(),
            'base_pattern_mini_postal_id' => $this->integer(),
            'size_id' => $this->integer(),
        ]);

        // creates index for column `base_pattern_mini_postal_id`
        $this->createIndex(
            '{{%idx-base_pattern_mini_postal_sizes-base_pattern_mini_postal_id}}',
            '{{%base_pattern_mini_postal_sizes}}',
            'base_pattern_mini_postal_id'
        );

        // add foreign key for table `{{%base_pattern_mini_postal}}`
        $this->addForeignKey(
            '{{%fk-base_pattern_mini_postal_sizes-base_pattern_mini_postal_id}}',
            '{{%base_pattern_mini_postal_sizes}}',
            'base_pattern_mini_postal_id',
            '{{%base_pattern_mini_postal}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_pattern_mini_postal}}`
        $this->dropForeignKey(
            '{{%fk-base_pattern_mini_postal_sizes-base_pattern_mini_postal_id}}',
            '{{%base_pattern_mini_postal_sizes}}'
        );

        // drops index for column `base_pattern_mini_postal_id`
        $this->dropIndex(
            '{{%idx-base_pattern_mini_postal_sizes-base_pattern_mini_postal_id}}',
            '{{%base_pattern_mini_postal_sizes}}'
        );

        $this->dropTable('{{%base_pattern_mini_postal_sizes}}');
    }
}
