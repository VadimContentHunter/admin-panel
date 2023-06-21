<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block\Tests;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block\Block;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\exceptions\BlockException;

class ChatGptBlockTest extends TestCase
{
    public function testSetAndGetName(): void
    {
        $block = new Block();
        $name = 'My Block';
        $block->setName($name);
        $this->assertSame($name, $block->getName());
    }

    public function testGetNameThrowsBlockExceptionIfNameIsNull(): void
    {
        $this->expectException(BlockException::class);
        $block = new Block();
        $block->getName();
    }

    public function testSetAndGetPathBlockView(): void
    {
        $path_file = __DIR__ . '/BlockViewTest.php';
        file_put_contents($path_file, 'BlockViewTest');

        $block = new Block();
        $block->setPathBlockView($path_file);
        $this->assertSame($path_file, $block->getPathBlockView());
        unlink($path_file);
    }

    public function testGetPathBlockViewThrowsBlockExceptionIfNameIsNull(): void
    {
        $this->expectException(BlockException::class);
        $block = new Block();
        $block->getPathBlockView();
    }

    public function testSetAndGetDescription(): void
    {
        $block = new Block();
        $description = 'Block description';
        $block->setDescription($description);
        $this->assertSame($description, $block->getDescription());
    }

    public function testGetDescriptionReturnsEmptyStringIfDescriptionIsNull(): void
    {
        $block = new Block();
        $this->assertSame('', $block->getDescription());
    }

    public function testSetAndGetParameters(): void
    {
        $block = new Block();
        $parameters = ['param1' => 'value1', 'param2' => 'value2'];
        $block->setParameters($parameters);
        $this->assertSame($parameters, $block->getParameters());
    }

    public function testGetParametersThrowsBlockExceptionIfParametersIsNull(): void
    {
        $this->expectException(BlockException::class);
        $block = new Block();
        $block->getParameters();
    }

    public function testGetParameterReturnsValueIfKeyExists(): void
    {
        $block = new Block();
        $parameters = ['param1' => 'value1', 'param2' => 'value2'];
        $block->setParameters($parameters);
        $this->assertSame('value1', $block->getParameter('param1'));
    }

    public function testGetParameterReturnsNullIfKeyDoesNotExist(): void
    {
        $block = new Block();
        $parameters = ['param1' => 'value1', 'param2' => 'value2'];
        $block->setParameters($parameters);
        $this->assertNull($block->getParameter('param3'));
    }
}
