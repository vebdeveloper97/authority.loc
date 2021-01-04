<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%musteri}}`.
 */
class m190805_115743_add_musteri_type_id_column_to_musteri_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("musteri", "musteri_type_id", $this->integer(11));
        $this->addColumn("musteri", "tel", $this->string(50));
        $this->addColumn("musteri", "address", $this->text());

        $this->createIndex(
            'idx-musteri-musteri_type_id',
            'musteri',
            'musteri_type_id'
        );


        $this->addForeignKey(
            'fk-musteri-musteri_type_id',
            'musteri',
            'musteri_type_id',
            'musteri_type',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-musteri-musteri_type_id',
            'musteri'
        );


        $this->dropIndex(
            'idx-musteri-musteri_type_id',
            'musteri'
        );

        $this->dropColumn("musteri", "musteri_type_id");
        $this->dropColumn("musteri", "tel");
        $this->dropColumn("musteri", "address");
    }
}
