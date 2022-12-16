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
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\QA\tests\Models;

use Modules\Admin\Models\NullAccount;
use Modules\Profile\Models\Profile;
use Modules\QA\Models\NullQAQuestion;
use Modules\QA\Models\QAAnswer;
use Modules\QA\Models\QAAnswerMapper;
use Modules\QA\Models\QAAnswerStatus;

/**
 * @internal
 */
final class QAAnswerMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @depends Modules\QA\tests\Models\QAQuestionMapperTest::testCRUD
     * @covers Modules\QA\Models\QAAnswerMapper
     * @group module
     */
    public function testCRUD() : void
    {
        $answer = new QAAnswer();

        $answer->answer = 'Answer content';
        $answer->setStatus(QAAnswerStatus::ACTIVE);
        $answer->createdBy  = new Profile(new NullAccount(1));
        $answer->question   = new NullQAQuestion(1);
        $answer->isAccepted = true;

        $id = QAAnswerMapper::create()->execute($answer);
        self::assertGreaterThan(0, $answer->getId());
        self::assertEquals($id, $answer->getId());

        $answerR = QAAnswerMapper::get()->with('createdBy')->with('account')->where('id', $answer->getId())->execute();
        self::assertEquals($answer->answer, $answerR->answer);
        self::assertEquals($answer->question->getId(), $answerR->question->getId());
        self::assertEquals($answer->getStatus(), $answerR->getStatus());
        self::assertEquals($answer->isAccepted, $answerR->isAccepted);
        self::assertEquals($answer->createdBy->account->getId(), $answerR->createdBy->account->getId());
    }
}
