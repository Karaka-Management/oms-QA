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

use Modules\Media\Models\MediaMapper;
use Modules\Profile\Models\ProfileMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\QA\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class QAAnswerMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'qa_answer_id'         => ['name' => 'qa_answer_id',         'type' => 'int',               'internal' => 'id'],
        'qa_answer_answer_raw' => ['name' => 'qa_answer_answer_raw', 'type' => 'string',            'internal' => 'answerRaw'],
        'qa_answer_answer'     => ['name' => 'qa_answer_answer',     'type' => 'string',            'internal' => 'answer'],
        'qa_answer_question'   => ['name' => 'qa_answer_question',   'type' => 'int',               'internal' => 'question'],
        'qa_answer_status'     => ['name' => 'qa_answer_status',     'type' => 'int',               'internal' => 'status'],
        'qa_answer_accepted'   => ['name' => 'qa_answer_accepted',   'type' => 'bool',              'internal' => 'isAccepted'],
        'qa_answer_created_by' => ['name' => 'qa_answer_created_by', 'type' => 'int',               'internal' => 'createdBy', 'readonly' => true],
        'qa_answer_created_at' => ['name' => 'qa_answer_created_at', 'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:string, external:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'createdBy' => [
            'mapper'   => ProfileMapper::class,
            'external' => 'qa_answer_created_by',
            'by'       => 'account',
        ],
        'question' => [
            'mapper'   => QAQuestionMapper::class,
            'external' => 'qa_answer_question',
        ],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'votes' => [
            'mapper'   => QAAnswerVoteMapper::class,
            'table'    => 'qa_answer_vote',
            'self'     => 'qa_answer_vote_answer',
            'external' => null,
        ],
        'media'        => [
            'mapper'   => MediaMapper::class,
            'table'    => 'qa_answer_media',
            'external' => 'qa_answer_media_dst',
            'self'     => 'qa_answer_media_src',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'qa_answer';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'qa_answer_created_at';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='qa_answer_id';
}
