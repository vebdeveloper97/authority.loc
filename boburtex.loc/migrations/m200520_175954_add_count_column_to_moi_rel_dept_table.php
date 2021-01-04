<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%moi_rel_dept}}`.
 */
class m200520_175954_add_count_column_to_moi_rel_dept_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%moi_rel_dept}}', 'count', $this->integer());
        $this->addColumn('{{%model_orders_planning}}', 'count', $this->integer());
        $this->addColumn('{{%moi_rel_dept}}', 'size_id', $this->integer());
        $this->addColumn('{{%model_orders_planning}}', 'type', $this->smallInteger()->defaultValue(1));
        $this->addColumn('{{%model_orders_planning}}', 'size_id', $this->integer());
        $this->addColumn('{{%toquv_rm_order_moi}}', 'count', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%moi_rel_dept}}', 'count');
        $this->dropColumn('{{%model_orders_planning}}', 'count');
        $this->dropColumn('{{%moi_rel_dept}}', 'size_id');
        $this->dropColumn('{{%model_orders_planning}}', 'type');
        $this->dropColumn('{{%model_orders_planning}}', 'size_id');
        $this->dropColumn('{{%toquv_rm_order_moi}}', 'count');
    }
}
