<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%question_answer}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%question}}`
 */
class m230425_050039_create_question_answer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%question_answer}}', [
            'id' => $this->primaryKey(),
            'question_id' => $this->integer()->notNull(),
            'text' => $this->string(256)->notNull(),
            'is_correct' => $this->boolean()->notNull(),
        ]);

        // creates index for column `question_id`
        $this->createIndex(
            '{{%idx-question_answer-question_id}}',
            '{{%question_answer}}',
            'question_id'
        );

        // add foreign key for table `{{%question}}`
        $this->addForeignKey(
            '{{%fk-question_answer-question_id}}',
            '{{%question_answer}}',
            'question_id',
            '{{%question}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%question}}`
        $this->dropForeignKey(
            '{{%fk-question_answer-question_id}}',
            '{{%question_answer}}'
        );

        // drops index for column `question_id`
        $this->dropIndex(
            '{{%idx-question_answer-question_id}}',
            '{{%question_answer}}'
        );

        $this->dropTable('{{%question_answer}}');
    }
}
