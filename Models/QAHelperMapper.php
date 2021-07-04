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

use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Mapper class.
 *
 * @package Modules\QA\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class QAHelperMapper extends DataMapperAbstract
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
            ->from(QAQuestionVoteMapper::getTable())
            ->leftJoin(QAQuestionMapper::getTable())
                ->on(QAQuestionVoteMapper::getTable() . '.qa_question_vote_question', '=', QAQuestionMapper::getTable() . '.qa_question_id')
            ->where(QAQuestionMapper::getTable() . '.qa_question_created_by', 'in', $accounts)
            ->groupBy('qa_question_created_by')
            ->execute()
            ->fetchAll();

        foreach ($questionScore as $votes) {
            $scores[(int) $votes['qa_question_created_by']] = (int) $votes['score'];
        }

        $query       = new Builder(self::$db);
        $answerScore = $query->select('qa_answer_created_by')
            ->selectAs('SUM(qa_answer_vote_score)', 'score')
            ->from(QAAnswerVoteMapper::getTable())
            ->leftJoin(QAAnswerMapper::getTable())
                ->on(QAAnswerVoteMapper::getTable() . '.qa_answer_vote_answer', '=', QAAnswerMapper::getTable() . '.qa_answer_id')
            ->where(QAAnswerMapper::getTable() . '.qa_answer_created_by', 'in', $accounts)
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
