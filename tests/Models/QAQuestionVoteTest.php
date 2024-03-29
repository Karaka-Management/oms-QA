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

use Modules\QA\Models\QAQuestionVote;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\QA\Models\QAQuestionVote::class)]
final class QAQuestionVoteTest extends \PHPUnit\Framework\TestCase
{
    private QAQuestionVote $vote;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->vote = new QAQuestionVote();
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->vote->id);
        self::assertEquals(0, $this->vote->question);
        self::assertEquals(0, $this->vote->score);
    }
}
