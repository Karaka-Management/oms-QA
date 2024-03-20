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
use Modules\Profile\Models\NullProfile;
use Modules\QA\Models\QAAnswer;
use Modules\QA\Models\QAQuestion;
use Modules\QA\Models\QAQuestionStatus;
use Modules\QA\Models\QAQuestionVote;
use phpOMS\Localization\ISO639x1Enum;

/**
 * @internal
 */
final class QAQuestionTest extends \PHPUnit\Framework\TestCase
{
    private QAQuestion $question;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->question = new QAQuestion();
    }

    /**
     * @covers \Modules\QA\Models\QAQuestion
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->question->id);
        self::assertEquals('', $this->question->name);
        self::assertEquals('', $this->question->question);
        self::assertEquals('', $this->question->questionRaw);
        self::assertEquals(QAQuestionStatus::ACTIVE, $this->question->status);
        self::assertEquals(ISO639x1Enum::_EN, $this->question->language);
        self::assertEquals(0, $this->question->createdBy->id);
        self::assertInstanceOf('\DateTimeImmutable', $this->question->createdAt);
        self::assertFalse($this->question->hasAccepted());
        self::assertEquals([0 => 0], $this->question->getAccounts()); // includes createdBy
        self::assertEquals([], $this->question->tags);
        self::assertEquals([], $this->question->files);
        self::assertEquals([], $this->question->getAnswers());
        self::assertEquals([], $this->question->getAnswersByScore());
        self::assertEquals(0, $this->question->getVoteScore());
        self::assertEquals(0, $this->question->getAccountVoteScore(0));
        self::assertEquals(0, $this->question->getAnswerCount());
    }

    /**
     * @covers \Modules\QA\Models\QAQuestion
     * @group module
     */
    public function testAnswerInputOutput() : void
    {
        $answer             = new QAAnswer();
        $answer->createdBy  = new NullProfile(1);
        $answer->isAccepted = true;

        $this->question->addAnswer($answer);
        $this->question->addAnswer(clone $answer);
        self::assertTrue($this->question->hasAccepted());
        self::assertCount(1, $this->question->getAccounts());
        self::assertCount(2, $this->question->getAnswers());
        self::assertCount(2, $this->question->getAnswersByScore());
        self::assertEquals(2, $this->question->getAnswerCount());
    }

    /**
     * @covers \Modules\QA\Models\QAQuestion
     * @group module
     */
    public function testVoteInputOutput() : void
    {
        $vote            = new QAQuestionVote();
        $vote->createdBy = new NullAccount(1);
        $vote->score     = 1;

        $this->question->addVote($vote);
        self::assertCount(1, $this->question->getVotes());
        self::assertEquals(1, $this->question->getVoteScore());
        self::assertEquals(1, $this->question->getAccountVoteScore(1));
    }

    /**
     * @covers \Modules\QA\Models\QAQuestion
     * @group module
     */
    public function testSerialize() : void
    {
        $this->question->name        = 'Test Title';
        $this->question->status      = QAQuestionStatus::ACTIVE;
        $this->question->question    = 'Question';
        $this->question->questionRaw = 'QuestionRaw';
        $this->question->language    = ISO639x1Enum::_DE;

        $serialized = $this->question->jsonSerialize();
        unset($serialized['app']);
        unset($serialized['createdBy']);
        unset($serialized['createdAt']);

        self::assertEquals(
            [
                'id'          => 0,
                'name'        => 'Test Title',
                'status'      => QAQuestionStatus::ACTIVE,
                'question'    => 'Question',
                'questionRaw' => 'QuestionRaw',
                'language'    => ISO639x1Enum::_DE,
                'tags'        => [],
                'answers'     => [],
                'votes'       => [],
                'media'       => [],
            ],
            $serialized
        );
    }
}
