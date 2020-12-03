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

use phpOMS\Localization\ISO639x1Enum;

/**
 * Task class.
 *
 * @package Modules\QA\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class QACategory implements \JsonSerializable
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Name.
     *
     * @var string|QACategoryL11n
     * @since 1.0.0
     */
    private $name = '';

    /**
     * Parent category.
     *
     * @var QACategory
     * @since 1.0.0
     */
    public self $parent;

    /**
     * Constructor.
     *
     * @sicne 1.0.0
     */
    public function __construct()
    {
        $this->parent = new NullQACategory();
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
     * Get name
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName() : string
    {
        return $this->name instanceof QACategoryL11n ? $this->name->getName() : $this->name;
    }

    /**
     * Set name
     *
     * @param string|QACategoryL11n $name Category name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setName($name, string $lang = ISO639x1Enum::_EN) : void
    {
        if ($name instanceof QACategoryL11n) {
            $this->name = $name;
        } elseif ($this->name instanceof QACategoryL11n && \is_string($name)) {
            $this->name->name = $name;
        } elseif (\is_string($name)) {
            $this->name       = new QACategoryL11n();
            $this->name->name = $name;
            $this->name->setLanguage($lang);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : array
    {
        return [];
    }
}
