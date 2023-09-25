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
use Modules\QA\Models\QAQuestionVote;
use Modules\QA\Models\QAQuestionVoteMapper;

/**
 * @internal
 */
final class QAQuestionVoteMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @depends Modules\QA\tests\Models\QAQuestionMapperTest::testCRUD
     * @covers Modules\QA\Models\QAQuestionVoteMapper
     * @group module
     */
    public function testCRUD() : void
    {
        \Modules\Admin\tests\Helper::createAccounts(1);

        $vote             = new QAQuestionVote();
        $vote->question   = 1;
        $vote->score      = 1;
        $vote->createdBy  = new NullAccount(1);
        $vote->createdFor = 2;

        $id = QAQuestionVoteMapper::create()->execute($vote);
        self::assertGreaterThan(0, $vote->id);
        self::assertEquals($id, $vote->id);

        $voteR = QAQuestionVoteMapper::get()->where('id', $vote->id)->execute();
        self::assertEquals($vote->question, $voteR->question);
        self::assertEquals($vote->score, $voteR->score);

        self::assertEquals(1,
            QAQuestionVoteMapper::get()
                ->where('question', 1)
                ->where('createdBy', 1)
                ->limit(1)
                ->execute()->id
        );
    }
}
