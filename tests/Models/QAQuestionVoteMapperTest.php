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
 * @link      https://karaka.app
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
        $vote             = new QAQuestionVote();
        $vote->question   = 1;
        $vote->score      = 1;
        $vote->createdBy  = new NullAccount(1);
        $vote->createdFor = 2;

        $id = QAQuestionVoteMapper::create()->execute($vote);
        self::assertGreaterThan(0, $vote->getId());
        self::assertEquals($id, $vote->getId());

        $voteR = QAQuestionVoteMapper::get()->where('id', $vote->getId())->execute();
        self::assertEquals($vote->question, $voteR->question);
        self::assertEquals($vote->score, $voteR->score);

        self::assertEquals(1,
            QAQuestionVoteMapper::get()
                ->where('question', 1)
                ->where('createdBy', 1)
                ->limit(1)
                ->execute()->getId()
        );
    }
}
