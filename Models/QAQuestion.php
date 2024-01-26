<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\QA\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\QA\Models;

use Modules\Profile\Models\NullProfile;
use Modules\Profile\Models\Profile;
use Modules\Tag\Models\Tag;
use phpOMS\Localization\ISO639x1Enum;

/**
 * QA question class.
 *
 * @package Modules\QA\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class QAQuestion implements \JsonSerializable
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Title.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Question status.
     *
     * @var int
     * @since 1.0.0
     */
    public int $status = QAQuestionStatus::ACTIVE;

    /**
     * Question.
     *
     * @var string
     * @since 1.0.0
     */
    public string $question = '';

    /**
     * Question.
     *
     * @var string
     * @since 1.0.0
     */
    public string $questionRaw = '';

    /**
     * Language
     *
     * @var string
     * @since 1.0.0
     */
    public string $language = ISO639x1Enum::_EN;

    /**
     * Created by.
     *
     * @var Profile
     * @since 1.0.0
     */
    public Profile $createdBy;

    /**
     * Created at.
     *
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    public \DateTimeImmutable $createdAt;

    /**
     * Tags.
     *
     * @var array<int, Tag>
     * @since 1.0.0
     */
    public array $tags = [];

    /**
     * Answers.
     *
     * @var array
     * @since 1.0.0
     */
    public array $answers = [];

    /**
     * Votes.
     *
     * @var array
     * @since 1.0.0
     */
    public array $votes = [];

    /**
     * App
     * @var QAApp
     */
    public QAApp $app;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->createdBy = new NullProfile();
        $this->app       = new NullQAApp(1);
    }

    /**
     * Finds all accounts in the question
     * e.g. asked by and all accounts who answered
     *
     * @return array
     */
    public function getAccounts() : array
    {
        $accounts   = [];
        $accounts[] = $this->createdBy->account->id;

        foreach ($this->answers as $answer) {
            $accounts[] = $answer->createdBy->account->id;
        }

        return \array_unique($accounts);
    }

    /**
     * Does the question have a accepted answer?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasAccepted() : bool
    {
        foreach ($this->answers as $answer) {
            if ($answer->isAccepted) {
                return true;
            }
        }

        return false;
    }

    /**
     * Count the answers
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getAnswerCount() : int
    {
        return \count($this->answers);
    }

    /**
     * Get the total vote score
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getVoteScore() : int
    {
        $score = 0;
        foreach ($this->votes as $vote) {
            $score += $vote->score;
        }

        return $score;
    }

    /**
     * Get all votes
     *
     * @return QAQuestionVote[]
     *
     * @since 1.0.0
     */
    public function getVotes() : array
    {
        return $this->votes;
    }

    /**
     * Add vote
     *
     * @param QAQuestionVote $vote Vote
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addVote(QAQuestionVote $vote) : void
    {
        $this->votes[] = $vote;
    }

    /**
     * Get the vote score from an account
     *
     * @param int $account Account id
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getAccountVoteScore(int $account) : int
    {
        foreach ($this->votes as $vote) {
            if ($vote->createdBy->id === $account) {
                return $vote->score;
            }
        }

        return 0;
    }

    /**
     * Get answers
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getAnswers() : array
    {
        return $this->answers;
    }

    /**
     * Get answers by score
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getAnswersByScore() : array
    {
        $answers = $this->answers;
        \uasort($answers, [$this, 'sortByScore']);

        return $answers;
    }

    /**
     * Sort by score
     *
     * @param QAAnswer $a1 Answer 1
     * @param QAAnswer $a2 Answer 2
     *
     * @return int
     */
    private function sortByScore(QAAnswer $a1, QAAnswer $a2) : int
    {
        return $a2->getVoteScore() <=> $a1->getVoteScore();
    }

    /**
     * Add answer to question
     *
     * @param int|QAAnswer $answer Answer to the question
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addAnswer($answer) : void
    {
        $this->answers[] = $answer;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'status'      => $this->status,
            'question'    => $this->question,
            'questionRaw' => $this->questionRaw,
            'language'    => $this->language,
            'createdBy'   => $this->createdBy,
            'createdAt'   => $this->createdAt,
            'app'         => $this->app,
            'tags'        => $this->tags,
            'answers'     => $this->votes,
            'votes'       => $this->votes,
            'media'       => $this->files,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }

    use \Modules\Media\Models\MediaListTrait;
}
