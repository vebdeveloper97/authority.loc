<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_slice_items}}`.
 */
class m191220_113409_add_bichuv_given_roll_id_column_to_bichuv_slice_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_slice_items','bichuv_given_roll_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_slice_items','bichuv_given_roll_id');
    }
}
