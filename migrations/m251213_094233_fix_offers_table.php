<?php

use yii\db\Migration;

class m251213_094233_fix_offers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->alterColumn('offers', 'performer_id', 'int not null');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251213_094233_fix_offers_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251213_094233_fix_offers_table cannot be reverted.\n";

        return false;
    }
    */
}
