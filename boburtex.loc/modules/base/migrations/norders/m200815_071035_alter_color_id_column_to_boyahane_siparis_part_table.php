<?php

use yii\db\Migration;

/**
 * Class m200815_071035_alter_color_id_column_to_boyahane_siparis_part_table
 */
class m200815_071035_alter_color_id_column_to_boyahane_siparis_part_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%boyahane_siparis_part}}', 'color_id', $this->integer());
        $this->alterColumn('{{%boyahane_siparis_part}}', 'color_group_id', $this->integer());
        $this->alterColumn('{{%boyahane_siparis_part}}', 'boyama_turi', $this->integer()->defaultValue(0));
        $this->alterColumn('{{%boyahane_siparis_part}}', 'color_tone', $this->integer());
        $this->alterColumn('{{%boyahane_siparis_part}}', 'color_type', $this->integer());
        $this->alterColumn('{{%boyahane_siparis_part}}', 'color_id2', $this->integer());
        $this->alterColumn('{{%boyahane_siparis_part}}', 'yumshatma_id', $this->smallInteger());
        $this->alterColumn('{{%boyahane_siparis_part}}', 'product_id', $this->integer());
        $this->alterColumn('{{%boyahane_siparis_part}}', 'user_uid', $this->integer());
        $this->alterColumn('{{%boyahane_siparis_part}}', 'reg_date', $this->dateTime());

        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'gr_m2', $this->string(50)->defaultValue(0));
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'thread_consist', $this->string(100));
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'roll', $this->integer()->defaultValue(0));
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'accepted_roll', $this->integer());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'weight', $this->float());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'accepted_weight', $this->float());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'ribana', $this->float());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'yaka_soni', $this->integer());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'shardon', $this->tinyInteger()->defaultValue(0));
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'samo_weav', $this->tinyInteger());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'fiksa', $this->tinyInteger());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'emzin', $this->tinyInteger());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'selikon', $this->tinyInteger());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'firchas', $this->tinyInteger());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'yakma', $this->tinyInteger());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'baski', $this->tinyInteger()->comment('Tub baski'));
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'ham_en', $this->string(50));
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'material_width', $this->string(50));
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'finish_gr', $this->string(50));
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'add_info', $this->string(300));
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'accepted_status', $this->smallInteger());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'user_uid', $this->integer());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'reg_date', $this->dateTime());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'tamirga_ketgan_holati', $this->tinyInteger());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'tamir_user', $this->integer());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'tamirdan_qaytgan_holati', $this->tinyInteger()->defaultValue(0));
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'tamir_sabab_id', $this->tinyInteger());
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'tamir_izoh', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
