<?php

use yii\db\Migration;

/**
 * Class m200826_072149_alter_tikuv_konveyer_bichuv_given_rolls_table
 */
class m200826_072149_alter_tikuv_konveyer_bichuv_given_rolls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropPrimaryKey('PRIMARY', 'tikuv_konveyer_bichuv_given_rolls');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addPrimaryKey('PRIMARY', 'tikuv_konveyer_bichuv_given_rolls', ['tikuv_konveyer_id', 'bichuv_given_rolls_id']);
    }
}
