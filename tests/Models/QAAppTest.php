<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\QA\tests\Models;

use Modules\QA\Models\QAApp;

/**
 * @internal
 */
final class QAAppTest extends \PHPUnit\Framework\TestCase
{
    private QAApp $app;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->app = new QAApp();
    }

    /**
     * @covers Modules\QA\Models\QAApp
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->app->getId());
        self::assertEquals('', $this->app->name);
    }

    /**
     * @covers Modules\QA\Models\QAApp
     * @group module
     */
    public function testSerialize() : void
    {
        $this->app->name = 'Test Title';

        $serialized = $this->app->jsonSerialize();

        self::assertEquals(
            [
                'id'          => 0,
                'name'        => 'Test Title',
            ],
            $serialized
        );
    }
}
