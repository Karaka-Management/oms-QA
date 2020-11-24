<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
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
use phpOMS\Localization\Defaults\LanguageMapper;

/**
 * Category mapper class.
 *
 * @package Modules\QA\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @todo Do I really want to create a relation to the language mapper? It's not really needed right?
 */
final class QACategoryL11nMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    protected static array $columns = [
        'qa_category_l11n_id'            => ['name' => 'qa_category_l11n_id',       'type' => 'int',    'internal' => 'id'],
        'qa_category_l11n_name'          => ['name' => 'qa_category_l11n_name',    'type' => 'string', 'internal' => 'name', 'autocomplete' => true],
        'qa_category_l11n_category'      => ['name' => 'qa_category_l11n_category',      'type' => 'int',    'internal' => 'category'],
        'qa_category_l11n_language'      => ['name' => 'qa_category_l11n_language', 'type' => 'string', 'internal' => 'language'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    protected static array $ownsOne = [
        'language' => [
            'mapper'            => LanguageMapper::class,
            'external'          => 'qa_category_l11n_language',
            'by'                => 'code2',
            'column'            => 'code2',
            'conditional'       => true,
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = 'qa_category_l11n';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $primaryField = 'qa_category_l11n_id';
}
