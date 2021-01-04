<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_pattern_rel_attachment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_patterns}}`
 * - `{{%attachments}}`
 */
class m200303_062122_create_base_pattern_rel_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_pattern_rel_attachment}}', [
            'id' => $this->primaryKey(),
            'base_pattern_id' => $this->integer(),
            'attachment_id' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `base_pattern_id`
        $this->createIndex(
            '{{%idx-base_pattern_rel_attachment-base_pattern_id}}',
            '{{%base_pattern_rel_attachment}}',
            'base_pattern_id'
        );

        // add foreign key for table `{{%base_patterns}}`
        $this->addForeignKey(
            '{{%fk-base_pattern_rel_attachment-base_pattern_id}}',
            '{{%base_pattern_rel_attachment}}',
            'base_pattern_id',
            '{{%base_patterns}}',
            'id',
            'CASCADE'
        );

        // creates index for column `attachment_id`
        $this->createIndex(
            '{{%idx-base_pattern_rel_attachment-attachment_id}}',
            '{{%base_pattern_rel_attachment}}',
            'attachment_id'
        );

        // add foreign key for table `{{%attachment}}`
        $this->addForeignKey(
            '{{%fk-base_pattern_rel_attachment-attachment_id}}',
            '{{%base_pattern_rel_attachment}}',
            'attachment_id',
            '{{%attachments}}',
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
            '{{%fk-base_pattern_rel_attachment-base_pattern_id}}',
            '{{%base_pattern_rel_attachment}}'
        );

        // drops index for column `base_pattern_id`
        $this->dropIndex(
            '{{%idx-base_pattern_rel_attachment-base_pattern_id}}',
            '{{%base_pattern_rel_attachment}}'
        );

        // drops foreign key for table `{{%attachments}}`
        $this->dropForeignKey(
            '{{%fk-base_pattern_rel_attachment-attachment_id}}',
            '{{%base_pattern_rel_attachment}}'
        );

        // drops index for column `attachment_id`
        $this->dropIndex(
            '{{%idx-base_pattern_rel_attachment-attachment_id}}',
            '{{%base_pattern_rel_attachment}}'
        );

        $this->dropTable('{{%base_pattern_rel_attachment}}');
    }
}
