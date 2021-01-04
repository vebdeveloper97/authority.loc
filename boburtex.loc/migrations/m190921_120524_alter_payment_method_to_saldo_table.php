<?php

use yii\db\Migration;

/**
 * Class m190921_120524_alter_payment_method_to_saldo_table
 */
class m190921_120524_alter_payment_method_to_saldo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_saldo','payment_method', $this->integer()->defaultValue(1));

        $this->createIndex(
            '{{%idx-toquv_saldo-payment_method}}',
            '{{%toquv_saldo}}',
            'payment_method'
        );

        $this->addColumn('bichuv_saldo','payment_method',$this->integer()->defaultValue(1));

        $this->createIndex(
            '{{%idx-bichuv_saldo-payment_method}}',
            '{{%bichuv_saldo}}',
            'payment_method'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-toquv_saldo-payment_method}}',
            '{{%toquv_saldo}}'
        );

        $this->dropColumn("toquv_saldo", "payment_method");

        $this->dropIndex(
            '{{%idx-bichuv_saldo-payment_method}}',
            '{{%bichuv_saldo}}'
        );

        $this->dropColumn("bichuv_saldo", "payment_method");
    }

}
