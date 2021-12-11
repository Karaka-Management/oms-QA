<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\QA\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\QA\Models;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;
use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Mapper class.
 *
 * @package Modules\QA\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class QAHelperMapper extends DataMapperFactory
{
    /**
     * Get total score of account
     *
     * @param array $accounts Accounts
     *
     * @return array
     */
    public static function getAccountScore(array $accounts) : array
    {
        $scores = [];

        $query         = new Builder(self::$db);
        $questionScore = $query->select('qa_question_created_by')
            ->selectAs('SUM(qa_question_vote_score)', 'score')
            ->from(QAQuestionVoteMapper::TABLE)
            ->leftJoin(QAQuestionMapper::TABLE)
                ->on(QAQuestionVoteMapper::TABLE . '.qa_question_vote_question', '=', QAQuestionMapper::TABLE . '.qa_question_id')
            ->where(QAQuestionMapper::TABLE . '.qa_question_created_by', 'in', $accounts)
            ->groupBy('qa_question_created_by')
            ->execute()
            ->fetchAll();

        foreach ($questionScore as $votes) {
            $scores[(int) $votes['qa_question_created_by']] = (int) $votes['score'];
        }

        $query       = new Builder(self::$db);
        $answerScore = $query->select('qa_answer_created_by')
            ->selectAs('SUM(qa_answer_vote_score)', 'score')
            ->from(QAAnswerVoteMapper::TABLE)
            ->leftJoin(QAAnswerMapper::TABLE)
                ->on(QAAnswerVoteMapper::TABLE . '.qa_answer_vote_answer', '=', QAAnswerMapper::TABLE . '.qa_answer_id')
            ->where(QAAnswerMapper::TABLE . '.qa_answer_created_by', 'in', $accounts)
            ->groupBy('qa_answer_created_by')
            ->execute()
            ->fetchAll();

        foreach ($answerScore as $votes) {
            $scores[(int) $votes['qa_answer_created_by']] ??= 0;
            $scores[(int) $votes['qa_answer_created_by']]  += (int) $votes['score'];
        }

        return $scores;
    }
}
