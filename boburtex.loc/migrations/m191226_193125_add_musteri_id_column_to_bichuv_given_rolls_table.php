<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_given_rolls}}`.
 */
class m191226_193125_add_musteri_id_column_to_bichuv_given_rolls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_given_rolls', 'musteri_id', $this->bigInteger(20));

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-bichuv_given_rolls-musteri_id}}',
            '{{%bichuv_given_rolls}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_rolls-musteri_id}}',
            '{{%bichuv_given_rolls}}',
            'musteri_id',
            '{{%musteri}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%musteri_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_given_rolls-musteri_id}}',
            '{{%bichuv_given_rolls}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_rolls-musteri_id}}',
            '{{%bichuv_given_rolls}}'
        );
        $this->dropColumn('bichuv_given_rolls', 'musteri_id');
    }
}
