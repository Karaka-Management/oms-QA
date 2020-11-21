<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
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
     * @var null|QACategory
     * @since 1.0.0
     */
    private ?self $parent = null;

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
     * @param string|TagL11n $name Tag article name
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
            $this->name->setName($name);
        } elseif (\is_string($name)) {
            $this->name = new QACategoryL11n();
            $this->name->setName($name);
            $this->name->setLanguage($lang);
        }
    }

    /**
     * Get the parent category
     *
     * @return null|self
     *
     * @since 1.0.0
     */
    public function getParent() : ?self
    {
        return $this->parent;
    }

    /**
     * Set the parent category
     *
     * @param null|self $parent Parent category
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setParent(?self $parent) : void
    {
        $this->parent = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : array
    {
        return [];
    }
}
