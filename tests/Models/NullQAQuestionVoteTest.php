<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\QA\tests\Models;

use Modules\QA\Models\NullQAQuestionVote;

/**
 * @internal
 */
final class NullQAQuestionVoteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\QA\Models\NullQAQuestionVote
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\QA\Models\QAQuestionVote', new NullQAQuestionVote());
    }

    /**
     * @covers Modules\QA\Models\NullQAQuestionVote
     * @group module
     */
    public function testId() : void
    {
        $null = new NullQAQuestionVote(2);
        self::assertEquals(2, $null->getId());
    }
}
