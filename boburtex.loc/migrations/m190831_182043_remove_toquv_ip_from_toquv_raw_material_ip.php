<?php

use yii\db\Migration;

/**
 * Class m190831_182043_remove_fields_from_toquv_raw_material_ip
 */
class m190831_182043_remove_toquv_ip_from_toquv_raw_material_ip extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //toquv_ip_id
        $this->dropForeignKey(
            'fk-toquv_raw_material_ip-toquv_ip_id',
            'toquv_raw_material_ip'
        );

        $this->dropIndex(
            'idx-toquv_raw_material_ip-toquv_ip_id',
            'toquv_raw_material_ip'
        );

        $this->dropColumn('toquv_raw_material_ip', 'toquv_ip_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190831_182043_remove_fields_from_toquv_raw_material_ip cannot be reverted.\n";

        return false;
    }
}
