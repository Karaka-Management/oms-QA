<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\QA\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\QA\Models;

use Modules\Admin\Models\Account;
use Modules\Admin\Models\NullAccount;

/**
 * QA answer vote class.
 *
 * @package Modules\QA\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class QAAnswerVote
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Account.
     *
     * @var Account
     * @since 1.0.0
     */
    public Account $createdBy;

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
    public int $answer = 0;

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
