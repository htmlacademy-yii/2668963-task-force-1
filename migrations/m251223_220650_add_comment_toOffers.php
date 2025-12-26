<?php

use yii\db\Migration;

class m251223_220650_add_comment_toOffers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('offers', 'comment', 'varchar(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251223_220650_add_comment_toOffers cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251223_220650_add_comment_toOffers cannot be reverted.\n";

        return false;
    }
    */
}
