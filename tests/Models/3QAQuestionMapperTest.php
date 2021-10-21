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

use Modules\Admin\Models\NullAccount;
use Modules\Profile\Models\Profile;
use Modules\QA\Models\QAQuestion;
use Modules\QA\Models\QAQuestionMapper;
use Modules\QA\Models\QAQuestionStatus;
use phpOMS\Utils\RnG\Text;

/**
 * @internal
 */
final class QAQuestionMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\QA\Models\QAQuestionMapper
     * @group module
     */
    public function testCRUD() : void
    {
        $question = new QAQuestion();

        $question->name     = 'Question Name';
        $question->question = 'Question content';
        $question->setStatus(QAQuestionStatus::ACTIVE);
        $question->createdBy = new Profile(new NullAccount(1));
        $question->setLanguage('en');

        $id = QAQuestionMapper::create($question);
        self::assertGreaterThan(0, $question->getId());
        self::assertEquals($id, $question->getId());

        $questionR = QAQuestionMapper::get($question->getId());
        self::assertEquals($question->name, $questionR->name);
        self::assertEquals($question->question, $questionR->question);
        self::assertEquals($question->getStatus(), $questionR->getStatus());
        self::assertEquals($question->getLanguage(), $questionR->getLanguage());
        self::assertEquals($question->createdBy->account->getId(), $questionR->createdBy->account->getId());
    }

    /**
     * @group volume
     * @group module
     * @coversNothing
     */
    public function testVolume() : void
    {
        for ($i = 1; $i < 30; ++$i) {
            $text     = new Text();
            $question = new QAQuestion();

            $question->name     = $text->generateText(\mt_rand(1, 3));
            $question->question = $text->generateText(\mt_rand(100, 500));
            $question->setStatus(QAQuestionStatus::ACTIVE);
            $question->createdBy = new Profile(new NullAccount(1));
            $question->setLanguage('en');

            $id = QAQuestionMapper::create($question);
        }
    }
}
