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
use Modules\QA\Models\QAAnswerVote;
use Modules\QA\Models\QAAnswerVoteMapper;

/**
 * @internal
 */
final class QAAnswerVoteMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @depends Modules\QA\tests\Models\QAAnswerMapperTest::testCRUD
     * @covers Modules\QA\Models\QAAnswerVoteMapper
     * @group module
     */
    public function testCRUD() : void
    {
        $vote             = new QAAnswerVote();
        $vote->answer     = 1;
        $vote->score      = 1;
        $vote->createdBy  = new NullAccount(1);
        $vote->createdFor = 2;

        $id = QAAnswerVoteMapper::create()->execute($vote);
        self::assertGreaterThan(0, $vote->getId());
        self::assertEquals($id, $vote->getId());

        $voteR = QAAnswerVoteMapper::get()->where('id', $vote->getId())->execute();
        self::assertEquals($vote->answer, $voteR->answer);
        self::assertEquals($vote->score, $voteR->score);

        self::assertEquals(1,
            QAAnswerVoteMapper::get()
                ->where('answer', 1)
                ->where('createdBy', 1)
                ->limit(1)
                ->execute()
                ->getId()
        );
    }
}
