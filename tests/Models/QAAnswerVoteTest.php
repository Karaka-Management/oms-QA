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

use Modules\QA\Models\QAAnswerVote;

/**
 * @internal
 */
final class QAAnswerVoteTest extends \PHPUnit\Framework\TestCase
{
    private QAAnswerVote $vote;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->vote = new QAAnswerVote();
    }

    /**
     * @covers Modules\QA\Models\QAAnswerVote
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->vote->getId());
        self::assertEquals(0, $this->vote->answer);
        self::assertEquals(0, $this->vote->score);
    }
}
