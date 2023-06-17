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
final class QAHelperMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @depends Modules\QA\tests\Models\QAAnswerVoteMapperTest::testCRUD
     * @covers Modules\QA\Models\QAHelperMapper
     * @group module
     */
    public function testAccountScore() : void
    {
        self::assertGreaterThan(0, QAHelperMapper::getAccountScore([1])[1]);
    }
}
