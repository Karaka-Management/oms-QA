<?php
/**
 * Karaka
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

use Modules\QA\Models\NullQAAnswer;

/**
 * @internal
 */
final class NullQAAnswerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\QA\Models\NullQAAnswer
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\QA\Models\QAAnswer', new NullQAAnswer());
    }

    /**
     * @covers Modules\QA\Models\NullQAAnswer
     * @group module
     */
    public function testId() : void
    {
        $null = new NullQAAnswer(2);
        self::assertEquals(2, $null->getId());
    }
}
