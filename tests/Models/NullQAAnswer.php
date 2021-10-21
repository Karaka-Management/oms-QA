<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\QA\tests\Models;

use Modules\QA\Models\NullQAAnswer;

/**
 * @internal
 */
final class Null extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\QA\Models\NullQAAnswer
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\QA\Models\QAAnswer', new NullQAAnswer());
    }

    /**
     * @covers Modules\QA\Models\NullQAAnswer
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullQAAnswer(2);
        self::assertEquals(2, $null->getId());
    }
}
