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

/**
 * Mapper class.
 *
 * @package Modules\QA\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class QAAnswerVoteMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    protected static array $columns = [
        'qa_answer_vote_id'          => ['name' => 'qa_answer_vote_id',          'type' => 'int',      'internal' => 'id'],
        'qa_answer_vote_score'  => ['name' => 'qa_answer_vote_score',  'type' => 'int',      'internal' => 'score'],
        'qa_answer_vote_answer'  => ['name' => 'qa_answer_vote_comment',  'type' => 'int',      'internal' => 'comment', 'readonly' => true],
        'qa_answer_vote_created_by'  => ['name' => 'qa_answer_vote_created_by',  'type' => 'int',      'internal' => 'createdBy', 'readonly' => true],
        'qa_answer_vote_created_at'  => ['name' => 'qa_answer_vote_created_at',  'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = 'qa_answer_vote';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $createdAt = 'qa_answer_vote_created_at';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $primaryField = 'qa_answer_vote_id';
}
