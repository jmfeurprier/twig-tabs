<?php

declare(strict_types=1);

namespace Jmf\Twig\Extension\Tabs;

use Jmf\TemplateRendering\TemplateRendererInterface;
use Override;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

final class TabBuilderTest extends TestCase
{
    private TemplateRendererInterface & Stub $templateRenderer;

    #[Override]
    protected function setUp(): void
    {
        $this->templateRenderer = $this->createStub(TemplateRendererInterface::class);
    }

    public function testBuildDefaultValues(): void
    {
        $tabBuilder = new TabBuilder($this->templateRenderer, 'my-id');

        $tabDefinition = $tabBuilder->build();

        self::assertSame('my-id', $tabDefinition->getId());
        self::assertSame('', $tabDefinition->getLabel());
        self::assertSame('', $tabDefinition->getContent());
        self::assertNull($tabDefinition->getBadge());
    }

    public function testBuildWithValues(): void
    {
        $tabBuilder = new TabBuilder($this->templateRenderer, 'my-id');

        $tabDefinition = $tabBuilder
            ->label('My Tab')
            ->content('<p>content</p>')
            ->badge('5')
            ->build()
        ;

        self::assertSame('my-id', $tabDefinition->getId());
        self::assertSame('My Tab', $tabDefinition->getLabel());
        self::assertSame('<p>content</p>', $tabDefinition->getContent());
        self::assertSame('5', $tabDefinition->getBadge());
    }

    public function testInclude(): void
    {
        $this->templateRenderer
            ->method('renderFromFile')
            ->willReturn('<p>rendered</p>')
        ;

        $tabBuilder = new TabBuilder($this->templateRenderer, 'my-id');

        $tabDefinition = $tabBuilder
            ->include('some/template.html.twig', ['foo' => 'bar'])
            ->build()
        ;

        self::assertSame('<p>rendered</p>', $tabDefinition->getContent());
    }
}
