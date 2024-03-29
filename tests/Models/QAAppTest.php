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

use Modules\QA\Models\QAApp;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\QA\Models\QAApp::class)]
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

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->app->id);
        self::assertEquals('', $this->app->name);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testSerialize() : void
    {
        $this->app->name = 'Test Title';

        $serialized = $this->app->jsonSerialize();

        self::assertEquals(
            [
                'id'   => 0,
                'name' => 'Test Title',
            ],
            $serialized
        );
    }
}
