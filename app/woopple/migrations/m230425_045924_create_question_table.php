<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%question}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%test}}`
 */
class m230425_045924_create_question_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%question}}', [
            'id' => $this->primaryKey(),
            'test_id' => $this->integer()->notNull(),
            'title' => $this->string(256)->notNull(),
            'description' => $this->string(),
            'type' => $this->string()->notNull()->defaultValue('closed'),
            'is_strict' => $this->boolean()->defaultValue(true),
            'is_multiple' => $this->boolean()->defaultValue(false)
        ]);

        // creates index for column `test_id`
        $this->createIndex(
            '{{%idx-question-test_id}}',
            '{{%question}}',
            'test_id'
        );

        // add foreign key for table `{{%test}}`
        $this->addForeignKey(
            '{{%fk-question-test_id}}',
            '{{%question}}',
            'test_id',
            '{{%test}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%test}}`
        $this->dropForeignKey(
            '{{%fk-question-test_id}}',
            '{{%question}}'
        );

        // drops index for column `test_id`
        $this->dropIndex(
            '{{%idx-question-test_id}}',
            '{{%question}}'
        );

        $this->dropTable('{{%question}}');
    }
}
