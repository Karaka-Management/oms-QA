<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\QA\tests\Models;

use Modules\QA\Models\QAHelperMapper;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\QA\Models\QAHelperMapper::class)]
final class QAHelperMapperTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\DependsExternal('\Modules\QA\tests\Models\QAAnswerVoteMapperTest', 'testCRUD')]
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testAccountScore() : void
    {
        self::assertGreaterThan(0, QAHelperMapper::getAccountScore([1])[1]);
    }
}
