<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 */
class m200505_175251_add_some_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc}}', 'models_list_id', $this->integer());
        $this->addColumn('{{%bichuv_doc}}', 'model_var_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bichuv_doc}}', 'models_list_id');
        $this->dropColumn('{{%bichuv_doc}}', 'model_var_id');
    }
}
