<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_kalite}}`.
 */
class m191206_060054_add_some_column_to_toquv_kalite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_kalite}}','order', $this->integer());
        $this->addColumn('{{%toquv_kalite}}','code', $this->string(60));
        // creates index for column `toquv_kalite`
        $this->createIndex(
            '{{%idx-toquv_kalite-code}}',
            '{{%toquv_kalite}}',
            'code'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `model_orders_planning_id`
        $this->dropIndex(
            '{{%idx-toquv_kalite-code}}',
            '{{%toquv_kalite}}'
        );
        $this->dropColumn('{{%toquv_kalite}}','order');
        $this->dropColumn('{{%toquv_kalite}}','code');
    }
}
