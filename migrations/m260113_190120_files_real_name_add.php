<?php

use yii\db\Migration;

class m260113_190120_files_real_name_add extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('files', 'real_file_name', 'varchar(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m260113_190120_files_real_name_add cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260113_190120_files_real_name_add cannot be reverted.\n";

        return false;
    }
    */
}
