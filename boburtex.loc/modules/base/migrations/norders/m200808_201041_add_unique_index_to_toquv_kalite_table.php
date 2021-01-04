<?php

use yii\db\Migration;

/**
 * Class m200808_201041_add_unique_index_to_toquv_kalite_table
 */
class m200808_201041_add_unique_index_to_toquv_kalite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $change_duplicat_column = "UPDATE toquv_kalite tk, ((SELECT COUNT(tk.code) count_code,MIN(tk.id) id
                                                  FROM toquv_kalite tk
                                                  GROUP BY tk.code
                                                  HAVING count_code > 1)) tk2
                                    SET tk.`code` = CONCAT(tk.`code`,'-1') WHERE tk.`id` = tk2.id";
        $this->execute($change_duplicat_column);
        $set_unique_sql = "ALTER TABLE `toquv_kalite` ADD UNIQUE(`code`);";
        $this->execute($set_unique_sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200808_201041_add_unique_index_to_toquv_kalite_table cannot be reverted.\n";

        return true;
    }
}
