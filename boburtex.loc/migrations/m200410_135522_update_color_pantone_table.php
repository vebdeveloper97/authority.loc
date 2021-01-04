<?php

use yii\db\Migration;

/**
 * Class m200410_135522_update_color_pantone_table
 */
class m200410_135522_update_color_pantone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('{{%color_pantone%}}', ['status'=>1], 'color_panton_type_id = 3');
        $this->update('{{%color_pantone%}}', ['status'=>2], 'color_panton_type_id IN (1,2)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->update('{{%color_pantone%}}', ['status'=>null], '1=1');
    }

}
