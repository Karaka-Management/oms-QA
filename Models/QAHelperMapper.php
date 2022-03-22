<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\QA\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
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
 * @link    https://karaka.app
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
        $questionScore = $query->select('qa_question_vote_created_for')
            ->selectAs('SUM(qa_question_vote_score)', 'score')
            ->from(QAQuestionVoteMapper::TABLE)
            ->where(QAQuestionVoteMapper::TABLE . '.qa_question_vote_created_for', 'in', $accounts)
            ->groupBy('qa_question_vote_created_for')
            ->execute()
            ->fetchAll();

        foreach ($questionScore as $votes) {
            $scores[(int) $votes['qa_question_vote_created_for']] = (int) $votes['score'];
        }

        $query       = new Builder(self::$db);
        $answerScore = $query->select('qa_answer_vote_created_for')
            ->selectAs('SUM(qa_answer_vote_score)', 'score')
            ->from(QAAnswerVoteMapper::TABLE)
            ->where(QAAnswerMapper::TABLE . '.qa_answer_vote_created_for', 'in', $accounts)
            ->groupBy('qa_answer_vote_created_for')
            ->execute()
            ->fetchAll();

        foreach ($answerScore as $votes) {
            $scores[(int) $votes['qa_answer_vote_created_for']] ??= 0;
            $scores[(int) $votes['qa_answer_vote_created_for']]  += (int) $votes['score'];
        }

        return $scores;
    }
}
