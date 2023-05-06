<?php
/**
 * Karaka
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

use Modules\QA\Models\NullQAApp;

/**
 * @internal
 */
final class NullQAAppTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\QA\Models\NullQAApp
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\QA\Models\QAApp', new NullQAApp());
    }

    /**
     * @covers Modules\QA\Models\NullQAApp
     * @group module
     */
    public function testId() : void
    {
        $null = new NullQAApp(2);
        self::assertEquals(2, $null->id);
    }
}
