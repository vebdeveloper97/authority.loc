<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_given_rolls}}`.
 */
class m191217_090327_add_nastel_party_column_to_bichuv_given_rolls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_given_rolls','nastel_party', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_given_rolls','nastel_party');
    }
}
