<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_makine}}`.
 */
class m190806_054250_add_status_column_to_toquv_makine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /*    musteri table   */
        $this->addColumn("musteri", "director", $this->string(200));
        $this->addColumn("musteri", "status", $this->smallInteger()->defaultValue(1));
        $this->addColumn("musteri", "created_at", $this->integer(11));
        $this->addColumn("musteri", "updated_at", $this->integer(11));
        $this->addColumn("musteri", "created_by", $this->integer(11));

        /*    toquv_makine table   */
        $this->addColumn("toquv_makine", "status", $this->smallInteger()->defaultValue(1));
        $this->addColumn("toquv_makine", "created_at", $this->integer(11));
        $this->addColumn("toquv_makine", "updated_at", $this->integer(11));
        $this->addColumn("toquv_makine", "created_by", $this->integer(11));

        /*  musteri_type  table */
        $this->addColumn("musteri_type", "status", $this->smallInteger()->defaultValue(1));
        $this->addColumn("musteri_type", "created_at", $this->integer(11));
        $this->addColumn("musteri_type", "updated_at", $this->integer(11));
        $this->addColumn("musteri_type", "created_by", $this->integer(11));

        /*  toquv_ne  table */
        $this->addColumn("toquv_ne", "status", $this->smallInteger()->defaultValue(1));
        $this->addColumn("toquv_ne", "created_at", $this->integer(11));
        $this->addColumn("toquv_ne", "updated_at", $this->integer(11));
        $this->addColumn("toquv_ne", "created_by", $this->integer(11));

        /*  toquv_pus_fine  table */
        $this->addColumn("toquv_pus_fine", "status", $this->smallInteger()->defaultValue(1));
        $this->addColumn("toquv_pus_fine", "created_at", $this->integer(11));
        $this->addColumn("toquv_pus_fine", "updated_at", $this->integer(11));
        $this->addColumn("toquv_pus_fine", "created_by", $this->integer(11));

        /*  toquv_thread table */
        $this->addColumn("toquv_thread", "status", $this->smallInteger()->defaultValue(1));
        $this->addColumn("toquv_thread", "created_at", $this->integer(11));
        $this->addColumn("toquv_thread", "updated_at", $this->integer(11));
        $this->addColumn("toquv_thread", "created_by", $this->integer(11));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        /*    musteri table   */
        $this->dropColumn("musteri", "director");
        $this->dropColumn("musteri", "status");
        $this->dropColumn("musteri", "created_at");
        $this->dropColumn("musteri", "updated_at");
        $this->dropColumn("musteri", "created_by");

        /*    toquv_makine table   */
        $this->dropColumn("toquv_makine", "status");
        $this->dropColumn("toquv_makine", "created_at");
        $this->dropColumn("toquv_makine", "updated_at");
        $this->dropColumn("toquv_makine", "created_by");

        /*    musteri_type table   */
        $this->dropColumn("musteri_type", "status");
        $this->dropColumn("musteri_type", "created_at");
        $this->dropColumn("musteri_type", "updated_at");
        $this->dropColumn("musteri_type", "created_by");

        /*    toquv_ne table   */
        $this->dropColumn("toquv_ne", "status");
        $this->dropColumn("toquv_ne", "created_at");
        $this->dropColumn("toquv_ne", "updated_at");
        $this->dropColumn("toquv_ne", "created_by");

        /*    toquv_pus_fine table   */
        $this->dropColumn("toquv_pus_fine", "status");
        $this->dropColumn("toquv_pus_fine", "created_at");
        $this->dropColumn("toquv_pus_fine", "updated_at");
        $this->dropColumn("toquv_pus_fine", "created_by");

        /*    toquv_thread table   */
        $this->dropColumn("toquv_thread", "status");
        $this->dropColumn("toquv_thread", "created_at");
        $this->dropColumn("toquv_thread", "updated_at");
        $this->dropColumn("toquv_thread", "created_by");


    }
}
