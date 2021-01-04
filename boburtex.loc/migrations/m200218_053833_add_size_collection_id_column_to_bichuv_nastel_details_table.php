<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_nastel_details}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%size_collection}}`
 */
class m200218_053833_add_size_collection_id_column_to_bichuv_nastel_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_nastel_details}}', 'size_collection_id', $this->integer());

        // creates index for column `size_collection_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_details-size_collection_id}}',
            '{{%bichuv_nastel_details}}',
            'size_collection_id'
        );

        // add foreign key for table `{{%size_collections}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_details-size_collection_id}}',
            '{{%bichuv_nastel_details}}',
            'size_collection_id',
            '{{%size_collections}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%size_collections}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_details-size_collection_id}}',
            '{{%bichuv_nastel_details}}'
        );

        // drops index for column `size_collection_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_details-size_collection_id}}',
            '{{%bichuv_nastel_details}}'
        );

        $this->dropColumn('{{%bichuv_nastel_details}}', 'size_collection_id');
    }
}
