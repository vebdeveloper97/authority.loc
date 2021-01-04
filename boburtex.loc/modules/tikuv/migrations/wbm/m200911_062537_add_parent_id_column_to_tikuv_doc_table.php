<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tikuv_doc}}`
 */
class m200911_062537_add_parent_id_column_to_tikuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_doc}}', 'parent_id', $this->integer());

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-tikuv_doc-parent_id}}',
            '{{%tikuv_doc}}',
            'parent_id'
        );

        // add foreign key for table `{{%tikuv_doc}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc-parent_id}}',
            '{{%tikuv_doc}}',
            'parent_id',
            '{{%tikuv_doc}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tikuv_doc}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_doc-parent_id}}',
            '{{%tikuv_doc}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-tikuv_doc-parent_id}}',
            '{{%tikuv_doc}}'
        );

        $this->dropColumn('{{%tikuv_doc}}', 'parent_id');
    }
}
