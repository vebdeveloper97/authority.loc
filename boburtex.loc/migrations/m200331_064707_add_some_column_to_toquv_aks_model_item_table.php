<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_aks_model_item}}`.
 */
class m200331_064707_add_some_column_to_toquv_aks_model_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_aks_model_item}}', 'height_sm', $this->double());
        $this->addColumn('{{%toquv_aks_model_item}}', 'percentage', $this->double());
        $this->addColumn('{{%toquv_aks_model_item}}', 'parent_percentage', $this->double());
        $this->addColumn('{{%toquv_aks_model_item}}', 'ip_id', $this->integer());
        $this->addColumn('{{%toquv_aks_model}}', 'add_info', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_aks_model_item}}', 'height_sm');
        $this->dropColumn('{{%toquv_aks_model_item}}', 'percentage');
        $this->dropColumn('{{%toquv_aks_model_item}}', 'parent_percentage');
        $this->dropColumn('{{%toquv_aks_model_item}}', 'ip_id');
        $this->dropColumn('{{%toquv_aks_model}}', 'add_info');
    }
}
