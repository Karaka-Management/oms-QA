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
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\QA\Models;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\QA\Models
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class QAAppMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'qa_app_id'   => ['name' => 'qa_app_id',   'type' => 'int',    'internal' => 'id'],
        'qa_app_name' => ['name' => 'qa_app_name', 'type' => 'string', 'internal' => 'name'],
        'qa_app_unit' => ['name' => 'qa_app_unit', 'type' => 'int', 'internal' => 'unit'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'qa_app';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'qa_app_id';
}
