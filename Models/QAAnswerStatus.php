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

use phpOMS\Stdlib\Base\Enum;

/**
 * QA answer status enum.
 *
 * @package Modules\QA\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class QAAnswerStatus extends Enum
{
    public const ACTIVE = 1;

    public const INACTIVE = 2;

    public const BLOCKED = 4;
}
