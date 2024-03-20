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
use Modules\QA\Models\QAAnswerVote;
use Modules\QA\Models\QAAnswerVoteMapper;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\QA\Models\QAAnswerVoteMapper::class)]
final class QAAnswerVoteMapperTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\DependsExternal('\Modules\QA\tests\Models\QAAnswerMapperTest', 'testCRUD')]
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testCRUD() : void
    {
        \Modules\Admin\tests\Helper::createAccounts(1);

        $vote             = new QAAnswerVote();
        $vote->answer     = 1;
        $vote->score      = 1;
        $vote->createdBy  = new NullAccount(1);
        $vote->createdFor = 2;

        $id = QAAnswerVoteMapper::create()->execute($vote);
        self::assertGreaterThan(0, $vote->id);
        self::assertEquals($id, $vote->id);

        $voteR = QAAnswerVoteMapper::get()->where('id', $vote->id)->execute();
        self::assertEquals($vote->answer, $voteR->answer);
        self::assertEquals($vote->score, $voteR->score);

        self::assertEquals(1,
            QAAnswerVoteMapper::get()
                ->where('answer', 1)
                ->where('createdBy', 1)
                ->limit(1)
                ->execute()
                ->id
        );
    }
}
