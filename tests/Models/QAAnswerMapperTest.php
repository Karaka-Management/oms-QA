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
use Modules\Profile\Models\Profile;
use Modules\QA\Models\NullQAQuestion;
use Modules\QA\Models\QAAnswer;
use Modules\QA\Models\QAAnswerMapper;
use Modules\QA\Models\QAAnswerStatus;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\QA\Models\QAAnswerMapper::class)]
final class QAAnswerMapperTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\DependsExternal('\Modules\QA\tests\Models\QAQuestionMapperTest', 'testCRUD')]
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testCRUD() : void
    {
        $answer = new QAAnswer();

        $answer->answer     = 'Answer content';
        $answer->status     = QAAnswerStatus::ACTIVE;
        $answer->createdBy  = new Profile(new NullAccount(1));
        $answer->question   = new NullQAQuestion(1);
        $answer->isAccepted = true;

        $id = QAAnswerMapper::create()->execute($answer);
        self::assertGreaterThan(0, $answer->id);
        self::assertEquals($id, $answer->id);

        $answerR = QAAnswerMapper::get()->with('createdBy')->with('account')->where('id', $answer->id)->execute();
        self::assertEquals($answer->answer, $answerR->answer);
        self::assertEquals($answer->question->id, $answerR->question->id);
        self::assertEquals($answer->status, $answerR->status);
        self::assertEquals($answer->isAccepted, $answerR->isAccepted);
        self::assertEquals($answer->createdBy->account->id, $answerR->createdBy->account->id);
    }
}
