<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%pb}}`
 */
class m190928_103312_add_some_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc}}', 'payment_method', $this->smallInteger());
        $this->addColumn('{{%bichuv_doc}}', 'paid_amount', $this->decimal(20,2));
        $this->addColumn('{{%bichuv_doc}}', 'pb_id', $this->integer());

        // creates index for column `payment_method`
        $this->createIndex(
            '{{%idx-bichuv_doc-payment_method}}',
            '{{%bichuv_doc}}',
            'payment_method'
        );

        // creates index for column `pb_id`
        $this->createIndex(
            '{{%idx-bichuv_doc-pb_id}}',
            '{{%bichuv_doc}}',
            'pb_id'
        );

        // add foreign key for table `{{%pb}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-pb_id}}',
            '{{%bichuv_doc}}',
            'pb_id',
            '{{%pul_birligi}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%pb}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-pb_id}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `pb_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc-pb_id}}',
            '{{%bichuv_doc}}'
        );

        $this->dropIndex(
            '{{%idx-bichuv_doc-payment_method}}',
            '{{%bichuv_doc}}'
        );

        $this->dropColumn('{{%bichuv_doc}}', 'payment_method');
        $this->dropColumn('{{%bichuv_doc}}', 'paid_amount');
        $this->dropColumn('{{%bichuv_doc}}', 'pb_id');
    }
}
