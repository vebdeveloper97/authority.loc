<?php

use yii\db\Migration;

/**
 * Class m200810_102921_tikuv_goods_name_changed
 */
class m200810_102921_tikuv_goods_name_changed extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
        UPDATE goods 
            LEFT JOIN color_pantone ON goods.color = color_pantone.id
            LEFT JOIN size ON size.id = goods.size 
            SET goods.name = concat(goods.model_no,\"*\",color_pantone.code,\"*\",size.name)
            WHERE goods.type = 1
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200810_102921_tikuv_goods_name_changed cannot be reverted.\n";

        return false;
    }
    */
}
