<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\QA\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\QA\Models;

use Modules\Admin\Models\AccountMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\QA\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class QAQuestionVoteMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'qa_question_vote_id'          => ['name' => 'qa_question_vote_id',          'type' => 'int',               'internal' => 'id'],
        'qa_question_vote_score'       => ['name' => 'qa_question_vote_score',       'type' => 'int',               'internal' => 'score'],
        'qa_question_vote_question'    => ['name' => 'qa_question_vote_question',    'type' => 'int',               'internal' => 'question',  'readonly' => true],
        'qa_question_vote_created_by'  => ['name' => 'qa_question_vote_created_by',  'type' => 'int',               'internal' => 'createdBy', 'readonly' => true],
        'qa_question_vote_created_for' => ['name' => 'qa_question_vote_created_for',  'type' => 'int',               'internal' => 'createdFor', 'readonly' => true],
        'qa_question_vote_created_at'  => ['name' => 'qa_question_vote_created_at',  'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'createdBy' => [
            'mapper'   => AccountMapper::class,
            'external' => 'qa_question_vote_created_by',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'qa_question_vote';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'qa_question_vote_created_at';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='qa_question_vote_id';
}
