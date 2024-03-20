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

use Modules\Admin\Models\NullAccount;
use Modules\QA\Models\QAAnswer;
use Modules\QA\Models\QAAnswerStatus;
use Modules\QA\Models\QAAnswerVote;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\QA\Models\QAAnswer::class)]
final class QAAnswerTest extends \PHPUnit\Framework\TestCase
{
    private QAAnswer $answer;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->answer = new QAAnswer();
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->answer->id);
        self::assertEquals('', $this->answer->answer);
        self::assertEquals(0, $this->answer->question->id);
        self::assertFalse($this->answer->isAccepted);
        self::assertEquals(QAAnswerStatus::ACTIVE, $this->answer->status);
        self::assertEquals(0, $this->answer->createdBy->id);
        self::assertEquals(0, $this->answer->getVoteScore());
        self::assertEquals(0, $this->answer->getAccountVoteScore(0));
        self::assertEquals([], $this->answer->files);
        self::assertEquals([], $this->answer->getVotes());
        self::assertInstanceOf('\DateTimeImmutable', $this->answer->createdAt);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testVoteInputOutput() : void
    {
        $vote            = new QAAnswerVote();
        $vote->createdBy = new NullAccount(1);
        $vote->score     = 1;

        $this->answer->addVote($vote);
        self::assertCount(1, $this->answer->getVotes());
        self::assertEquals(1, $this->answer->getVoteScore());
        self::assertEquals(1, $this->answer->getAccountVoteScore(1));
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testSerialize() : void
    {
        $this->answer->status    = QAAnswerStatus::ACTIVE;
        $this->answer->answer    = 'Answer';
        $this->answer->answerRaw = 'AnswerRaw';

        $serialized = $this->answer->jsonSerialize();
        unset($serialized['question']);
        unset($serialized['createdBy']);
        unset($serialized['createdAt']);

        self::assertEquals(
            [
                'id'         => 0,
                'status'     => QAAnswerStatus::ACTIVE,
                'answer'     => 'Answer',
                'answerRaw'  => 'AnswerRaw',
                'isAccepted' => false,
                'votes'      => [],
                'media'      => [],
            ],
            $serialized
        );
    }
}
