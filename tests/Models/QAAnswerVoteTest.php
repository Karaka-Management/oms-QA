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

use Modules\QA\Models\QAAnswerVote;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\QA\Models\QAAnswerVote::class)]
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

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->vote->id);
        self::assertEquals(0, $this->vote->answer);
        self::assertEquals(0, $this->vote->score);
    }
}
