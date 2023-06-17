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

use Modules\Admin\Models\Account;
use Modules\Admin\Models\NullAccount;

/**
 * QA question vote class.
 *
 * @package Modules\QA\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class QAQuestionVote
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Account.
     *
     * @var Account
     * @since 1.0.0
     */
    public Account $createdBy;

    /**
     * Account
     *
     * @var int
     * @since 1.0.0
     */
    public int $createdFor = 0;

    /**
     * Created at
     *
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    public \DateTimeImmutable $createdAt;

    /**
     * Comment
     *
     * @var int
     * @since 1.0.0
     */
    public int $question = 0;

    /**
     * Score
     *
     * @var int
     * @since 1.0.0
     */
    public int $score = 0;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdBy = new NullAccount();
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Get model id.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }
}
