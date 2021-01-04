<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%parent}}`
 */
class m190925_100910_add_some_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc}}', 'parent_id', $this->integer()->after('to_employee'));

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-bichuv_doc-parent_id}}',
            '{{%bichuv_doc}}',
            'parent_id'
        );

        // add foreign key for table `{{%bichuv_doc}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-parent_id}}',
            '{{%bichuv_doc}}',
            'parent_id',
            '{{%bichuv_doc}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%parent}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-parent_id}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc-parent_id}}',
            '{{%bichuv_doc}}'
        );

        $this->dropColumn('{{%bichuv_doc}}', 'parent_id');
    }
}
