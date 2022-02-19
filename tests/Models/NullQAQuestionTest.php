<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\QA\tests\Models;

use Modules\QA\Models\NullQAQuestion;

/**
 * @internal
 */
final class NullQAQuestionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\QA\Models\NullQAQuestion
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\QA\Models\QAQuestion', new NullQAQuestion());
    }

    /**
     * @covers Modules\QA\Models\NullQAQuestion
     * @group module
     */
    public function testId() : void
    {
        $null = new NullQAQuestion(2);
        self::assertEquals(2, $null->getId());
    }
}
