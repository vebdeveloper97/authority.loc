<?php

use yii\db\Migration;

/**
 * Class m190817_062252_add_percentage_column_to_toquv_raw_material_consist
 */
class m190817_062252_add_percentage_column_to_toquv_raw_material_consist extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('toquv_raw_material_consist', 'percentage', $this->integer(11));
    }

    public function down()
    {
        $this->dropColumn('toquv_raw_material_consist', 'percentage');
    }

}
