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

use Modules\Admin\Models\NullAccount;
use Modules\Profile\Models\NullProfile;
use Modules\Tag\Models\Tag;
use Modules\Profile\Models\Profile;

/**
 * Task class.
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
     * Category.
     *
     * @var QACategory
     * @since 1.0.0
     */
    private ?QACategory $category = null;

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
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->createdBy = new NullProfile();
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
     * Does the question have a accepted answer?
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasAccepted() : bool
    {
        foreach ($this->answers as $answer) {
            if ($answer->isAccepted()) {
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
     * Get the category
     *
     * @return QACategory
     *
     * @since 1.0.0
     */
    public function getCategory() : QACategory
    {
        return $this->category ?? new NullQACategory();
    }

    /**
     * Set the category
     *
     * @param null|QACategory $category Category
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setCategory(?QACategory $category) : void
    {
        $this->category = $category;
    }

    /**
     * Get tags
     *
     * @return array
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
     */
    public function addTag($tag) : void
    {
        $this->tags[] = $tag;
    }

    /**
     * Set tags to question
     *
     * @param array<int, int|Tag> $tags Tags
     */
    public function setTags(array $tags) : void
    {
        $this->tags = $tags;
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
    public function jsonSerialize() : array
    {
        return [];
    }
}
