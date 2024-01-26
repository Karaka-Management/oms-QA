<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\QA\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\QA\Models;

use Modules\Media\Models\MediaMapper;
use Modules\Profile\Models\ProfileMapper;
use Modules\Tag\Models\TagMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\QA\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of QAQuestion
 * @extends DataMapperFactory<T>
 */
final class QAQuestionMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'qa_question_id'           => ['name' => 'qa_question_id',           'type' => 'int',               'internal' => 'id'],
        'qa_question_title'        => ['name' => 'qa_question_title',        'type' => 'string',            'internal' => 'name'],
        'qa_question_language'     => ['name' => 'qa_question_language',     'type' => 'string',            'internal' => 'language'],
        'qa_question_question'     => ['name' => 'qa_question_question',     'type' => 'string',            'internal' => 'question'],
        'qa_question_question_raw' => ['name' => 'qa_question_question_raw', 'type' => 'string',            'internal' => 'questionRaw'],
        'qa_question_status'       => ['name' => 'qa_question_status',       'type' => 'int',               'internal' => 'status'],
        'qa_question_created_by'   => ['name' => 'qa_question_created_by',   'type' => 'int',               'internal' => 'createdBy', 'readonly' => true],
        'qa_question_created_at'   => ['name' => 'qa_question_created_at',   'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
        'qa_question_app'          => ['name' => 'qa_question_app',          'type' => 'int',               'internal' => 'app'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'tags' => [
            'mapper'   => TagMapper::class,
            'table'    => 'qa_tag',
            'self'     => 'qa_tag_dst',
            'external' => 'qa_tag_src',
        ],
        'answers' => [
            'mapper'   => QAAnswerMapper::class,
            'table'    => 'qa_answer',
            'self'     => 'qa_answer_question',
            'external' => null,
        ],
        'votes' => [
            'mapper'   => QAQuestionVoteMapper::class,
            'table'    => 'qa_question_vote',
            'self'     => 'qa_question_vote_question',
            'external' => null,
        ],
        'files' => [
            'mapper'   => MediaMapper::class,
            'table'    => 'qa_question_media',
            'external' => 'qa_question_media_dst',
            'self'     => 'qa_question_media_src',
        ],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:class-string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'createdBy' => [
            'mapper'   => ProfileMapper::class,
            'external' => 'qa_question_created_by',
            'by'       => 'account',
        ],
        'app' => [
            'mapper'   => QAAppMapper::class,
            'external' => 'qa_question_app',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'qa_question';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'qa_question_created_at';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'qa_question_id';
}
