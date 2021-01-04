<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_process}}`
 */
class m200908_125034_add_mobile_process_id_column_to_tikuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_doc}}', 'mobile_process_id', $this->integer());

        // creates index for column `mobile_process_id`
        $this->createIndex(
            '{{%idx-tikuv_doc-mobile_process_id}}',
            '{{%tikuv_doc}}',
            'mobile_process_id'
        );

        // add foreign key for table `{{%mobile_process}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc-mobile_process_id}}',
            '{{%tikuv_doc}}',
            'mobile_process_id',
            '{{%mobile_process}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_process}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_doc-mobile_process_id}}',
            '{{%tikuv_doc}}'
        );

        // drops index for column `mobile_process_id`
        $this->dropIndex(
            '{{%idx-tikuv_doc-mobile_process_id}}',
            '{{%tikuv_doc}}'
        );

        $this->dropColumn('{{%tikuv_doc}}', 'mobile_process_id');
    }
}
