<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\QA\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\QA\Models;

use Modules\Profile\Models\NullProfile;
use Modules\Profile\Models\Profile;
use Modules\Tag\Models\Tag;

/**
 * QA question class.
 *
 * @package Modules\QA\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
    protected int $id = 0;

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
    private int $status = QAQuestionStatus::ACTIVE;

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
    private string $language = '';

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
     * @var array<int, int|Tag>
     * @since 1.0.0
     */
    private array $tags = [];

    /**
     * Answers.
     *
     * @var array
     * @since 1.0.0
     */
    private array $answers = [];

    /**
     * Votes.
     *
     * @var array
     * @since 1.0.0
     */
    private array $votes = [];

    /**
     * App
     * @var int
     */
    public QAApp $app;

    /**
     * Media files
     *
     * @var array
     * @since 1.0.0
     */
    protected array $media = [];

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->createdBy = new NullProfile();
        $this->app       = new QAApp();
    }

    /**
     * Get id.
     *
     * @return int Model id
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Finds all accounts in Question
     * e.g. asked by and all accoounts who answered
     *
     * @return array
     */
    public function getAccounts() : array
    {
        $accounts   = [];
        $accounts[] = $this->createdBy->account->getId();

        foreach ($this->answers as $answer) {
            $accounts[] = $answer->createdBy->account->getId();
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
     * Get the language
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getLanguage() : string
    {
        return $this->language;
    }

    /**
     * Set the language
     *
     * @param string $language Language
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setLanguage(string $language) : void
    {
        $this->language = $language;
    }

    /**
     * Is the question answered?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isAnswered() : bool
    {
        foreach ($this->answers as $answer) {
            if ($answer->isAccepted()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the status
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * Set the status
     *
     * @param int $status Status
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setStatus(int $status) : void
    {
        $this->status = $status;
    }

    /**
     * Get tags
     *
     * @return array
     *
     * @since 1.0.0
     *
     * @since 1.0.0
     */
    public function getTags() : array
    {
        return $this->tags;
    }

    /**
     * Add tag to question
     *
     * @param int|Tag $tag Tag
     *
     * @since 1.0.0
     */
    public function addTag(int | Tag $tag) : void
    {
        $this->tags[] = $tag;
    }

    /**
     * Set tags to question
     *
     * @param array<int, int|Tag> $tags Tags
     *
     * @since 1.0.0
     */
    public function setTags(array $tags) : void
    {
        $this->tags = $tags;
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
            if ($vote->createdBy->getId() === $account) {
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
     * Get all media
     *
     * @return Media[]
     *
     * @since 1.0.0
     */
    public function getMedia() : array
    {
        return $this->media;
    }

    /**
     * Add media
     *
     * @param Media $media Media to add
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addMedia(Media $media) : void
    {
        $this->media[] = $media;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : array
    {
        return [];
    }
}
