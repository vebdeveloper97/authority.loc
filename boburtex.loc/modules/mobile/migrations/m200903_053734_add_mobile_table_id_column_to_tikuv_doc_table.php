<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_tables}}`
 */
class m200903_053734_add_mobile_table_id_column_to_tikuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_doc}}', 'mobile_table_id', $this->integer());

        // creates index for column `mobile_table_id`
        $this->createIndex(
            '{{%idx-tikuv_doc-mobile_table_id}}',
            '{{%tikuv_doc}}',
            'mobile_table_id'
        );

        // add foreign key for table `{{%mobile_tables}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc-mobile_table_id}}',
            '{{%tikuv_doc}}',
            'mobile_table_id',
            '{{%mobile_tables}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_tables}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_doc-mobile_table_id}}',
            '{{%tikuv_doc}}'
        );

        // drops index for column `mobile_table_id`
        $this->dropIndex(
            '{{%idx-tikuv_doc-mobile_table_id}}',
            '{{%tikuv_doc}}'
        );

        $this->dropColumn('{{%tikuv_doc}}', 'mobile_table_id');
    }
}
