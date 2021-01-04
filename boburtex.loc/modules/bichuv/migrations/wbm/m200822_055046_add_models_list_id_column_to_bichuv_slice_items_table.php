<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_slice_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 */
class m200822_055046_add_models_list_id_column_to_bichuv_slice_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_slice_items}}', 'models_list_id', $this->integer());

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-bichuv_slice_items-models_list_id}}',
            '{{%bichuv_slice_items}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_items-models_list_id}}',
            '{{%bichuv_slice_items}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_slice_items-models_list_id}}',
            '{{%bichuv_slice_items}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-bichuv_slice_items-models_list_id}}',
            '{{%bichuv_slice_items}}'
        );

        $this->dropColumn('{{%bichuv_slice_items}}', 'models_list_id');
    }
}
