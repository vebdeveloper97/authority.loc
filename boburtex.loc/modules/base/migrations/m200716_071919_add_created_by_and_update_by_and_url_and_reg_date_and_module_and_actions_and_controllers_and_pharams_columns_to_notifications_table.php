<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%notifications}}`.
 */
class m200716_071919_add_created_by_and_update_by_and_url_and_reg_date_and_module_and_actions_and_controllers_and_pharams_columns_to_notifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%notifications}}', 'created_by', $this->integer());
        $this->addColumn('{{%notifications}}', 'updated_by', $this->integer());
        $this->addColumn('{{%notifications}}', 'url', $this->char(255));
        $this->addColumn('{{%notifications}}', 'reg_date', $this->datetime());
        $this->addColumn('{{%notifications}}', 'module', $this->char(255));
        $this->addColumn('{{%notifications}}', 'actions', $this->char(255));
        $this->addColumn('{{%notifications}}', 'controllers', $this->char(255));
        $this->addColumn('{{%notifications}}', 'pharams', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%notifications}}', 'created_by');
        $this->dropColumn('{{%notifications}}', 'updated_by');
        $this->dropColumn('{{%notifications}}', 'url');
        $this->dropColumn('{{%notifications}}', 'reg_date');
        $this->dropColumn('{{%notifications}}', 'module');
        $this->dropColumn('{{%notifications}}', 'actions');
        $this->dropColumn('{{%notifications}}', 'controllers');
        $this->dropColumn('{{%notifications}}', 'pharams');
    }
}
