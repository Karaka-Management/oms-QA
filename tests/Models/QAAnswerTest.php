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

use Modules\Admin\Models\NullAccount;
use Modules\Media\Models\Media;
use Modules\QA\Models\QAAnswer;
use Modules\QA\Models\QAAnswerStatus;
use Modules\QA\Models\QAAnswerVote;

/**
 * @internal
 */
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

    /**
     * @covers Modules\QA\Models\QAAnswer
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->answer->getId());
        self::assertEquals('', $this->answer->answer);
        self::assertEquals(0, $this->answer->question->getId());
        self::assertFalse($this->answer->isAccepted);
        self::assertEquals(QAAnswerStatus::ACTIVE, $this->answer->getStatus());
        self::assertEquals(0, $this->answer->createdBy->getId());
        self::assertEquals(0, $this->answer->getVoteScore());
        self::assertEquals(0, $this->answer->getAccountVoteScore(0));
        self::assertEquals([], $this->answer->getMedia());
        self::assertEquals([], $this->answer->getVotes());
        self::assertInstanceOf('\DateTimeImmutable', $this->answer->createdAt);
    }

    /**
     * @covers Modules\QA\Models\QAAnswer
     * @group module
     */
    public function testStatusInputOutput() : void
    {
        $this->answer->setStatus(QAAnswerStatus::ACTIVE);
        self::assertEquals(QAAnswerStatus::ACTIVE, $this->answer->getStatus());
    }

    /**
     * @covers Modules\QA\Models\QAAnswer
     * @group module
     */
    public function testMediaInputOutput() : void
    {
        $this->answer->addMedia(new Media());
        self::assertCount(1, $this->answer->getMedia());
    }

    /**
     * @covers Modules\QA\Models\QAAnswer
     * @group module
     */
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

    /**
     * @covers Modules\QA\Models\QAAnswer
     * @group module
     */
    public function testSerialize() : void
    {
        $this->answer->setStatus(QAAnswerStatus::ACTIVE);
        $this->answer->answer           = 'Answer';
        $this->answer->answerRaw        = 'AnswerRaw';

        $serialized = $this->answer->jsonSerialize();
        unset($serialized['question']);
        unset($serialized['createdBy']);
        unset($serialized['createdAt']);

        self::assertEquals(
            [
                'id'            => 0,
                'status'        => QAAnswerStatus::ACTIVE,
                'answer'        => 'Answer',
                'answerRaw'     => 'AnswerRaw',
                'isAccepted'    => false,
                'votes'         => [],
                'media'         => [],
            ],
            $serialized
        );
    }
}
