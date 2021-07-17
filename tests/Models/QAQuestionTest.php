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

use Modules\Profile\Models\NullProfile;
use Modules\Admin\Models\NullAccount;
use Modules\QA\Models\QAQuestion;
use Modules\QA\Models\QAQuestionStatus;

/**
 * @internal
 */
class QAQuestionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\QA\Models\QAQuestion
     * @group module
     */
    public function testDefault() : void
    {
        $question = new QAQuestion();

        self::assertEquals(0, $question->getId());
        self::assertEquals('', $question->name);
        self::assertEquals('', $question->question);
        self::assertEquals(QAQuestionStatus::ACTIVE, $question->getStatus());
        self::assertEquals('', $question->getLanguage());
        self::assertEquals(0, $question->createdBy->getId());
        self::assertInstanceOf('\DateTimeImmutable', $question->createdAt);
        self::assertEquals([], $question->getTags());
    }

    /**
     * @covers Modules\QA\Models\QAQuestion
     * @group module
     */
    public function testSetGet() : void
    {
        $question = new QAQuestion();

        $question->name     = 'Question Name';
        $question->question = 'Question content';
        $question->setStatus(QAQuestionStatus::ACTIVE);
        $question->createdBy = new NullProfile(1);
        $question->setLanguage('en');

        self::assertEquals('Question Name', $question->name);
        self::assertEquals('Question content', $question->question);
        self::assertEquals(QAQuestionStatus::ACTIVE, $question->getStatus());
        self::assertEquals('en', $question->getLanguage());
        self::assertEquals(1, $question->createdBy->getId());
    }
}
