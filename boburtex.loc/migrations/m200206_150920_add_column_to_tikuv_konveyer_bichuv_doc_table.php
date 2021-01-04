<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_konveyer_bichuv_doc}}`.
 */
class m200206_150920_add_column_to_tikuv_konveyer_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_konveyer_bichuv_doc}}', 'status', $this->smallInteger()->defaultValue(1));
        $this->addColumn('{{%tikuv_konveyer_bichuv_doc}}', 'created_at', $this->integer());
        $this->addColumn('{{%tikuv_konveyer_bichuv_doc}}', 'updated_at', $this->integer());
        $this->addColumn('{{%tikuv_konveyer_bichuv_doc}}', 'created_by', $this->integer());
        $this->execute("ALTER TABLE `tikuv_konveyer_bichuv_doc` CHANGE `indeks` `indeks` DOUBLE(8,7) NULL DEFAULT NULL;");
        $this->addColumn('{{%toquv_makine_processes}}', 'status', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tikuv_konveyer_bichuv_doc}}', 'status');
        $this->dropColumn('{{%tikuv_konveyer_bichuv_doc}}', 'created_at');
        $this->dropColumn('{{%tikuv_konveyer_bichuv_doc}}', 'updated_at');
        $this->dropColumn('{{%tikuv_konveyer_bichuv_doc}}', 'created_by');
        $this->alterColumn('{{%tikuv_konveyer_bichuv_doc}}','indeks', $this->integer());
//        $this->dropColumn('{{%toquv_makine_processes}}', 'status');
    }
}
