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

/**
 * Answer class.
 *
 * @package Modules\QA\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class QAAnswer implements \JsonSerializable
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Status.
     *
     * @var int
     * @since 1.0.0
     */
    public int $status = QAAnswerStatus::ACTIVE;

    /**
     * Answer.
     *
     * @var string
     * @since 1.0.0
     */
    public string $answer = '';

    /**
     * Answer raw.
     *
     * @var string
     * @since 1.0.0
     */
    public string $answerRaw = '';

    /**
     * Question
     *
     * @var QAQuestion
     * @since 1.0.0
     */
    public QAQuestion $question;

    /**
     * Is accepted answer.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $isAccepted = false;

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
     * Votes.
     *
     * @var array
     * @since 1.0.0
     */
    public array $votes = [];

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->createdBy = new NullProfile();
        $this->question  = new NullQAQuestion();
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
     * Get all votes
     *
     * @return QAAnswerVote[]
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
     * @param QAAnswerVote $vote Vote
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addVote(QAAnswerVote $vote) : void
    {
        $this->votes[] = $vote;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'         => $this->id,
            'status'     => $this->status,
            'answer'     => $this->answer,
            'answerRaw'  => $this->answerRaw,
            'question'   => $this->question,
            'isAccepted' => $this->isAccepted,
            'createdBy'  => $this->createdBy,
            'createdAt'  => $this->createdAt,
            'votes'      => $this->votes,
            'media'      => $this->files,
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
