<?php

use yii\db\Migration;

/**
 * Class m190905_155444_insert_color_panton_type_data
 */
class m190905_155444_insert_color_panton_type_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('color_panton_type', ['id'=>1, 'name' => "TSX"],true);
        $this->upsert('color_panton_type', ['id'=>2, 'name' => "TPG"],true);
        $this->upsert('color_panton_type', ['id'=>3, 'name' => "TCX"],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
