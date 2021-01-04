<?php

use yii\db\Migration;

/**
 * Class m200912_023439_alter_tikuv_konveyer_id_from_tikuv_konveyer_bichuv_given_rolls_table
 */
class m200912_023439_alter_tikuv_konveyer_id_from_tikuv_konveyer_bichuv_given_rolls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%tikuv_konveyer_bichuv_given_rolls}}', 'tikuv_konveyer_id', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%tikuv_konveyer_bichuv_given_rolls}}', 'tikuv_konveyer_id', $this->integer()->notNull()->defaultValue(0));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200912_023439_alter_tikuv_konveyer_id_from_tikuv_konveyer_bichuv_given_rolls_table cannot be reverted.\n";

        return false;
    }
    */
}
