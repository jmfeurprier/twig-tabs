<?php

declare(strict_types=1);

namespace Jmf\Twig\Extension\Tabs;

use Jmf\TemplateRendering\TemplateRendererInterface;
use Override;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

final class TabsExtensionTest extends TestCase
{
    private TabsExtension $tabsExtension;

    private TemplateRendererInterface & Stub $templateRenderer;

    private FilesystemLoader $filesystemLoader;

    #[Override]
    protected function setUp(): void
    {
        $this->templateRenderer = $this->createStub(TemplateRendererInterface::class);
        $this->filesystemLoader = new FilesystemLoader();

        $this->tabsExtension = new TabsExtension(
            $this->templateRenderer,
            $this->filesystemLoader,
        );
    }

    public function testGetFunctionNames(): void
    {
        $names = array_map(
            fn(
                TwigFunction $twigFunction,
            ): string => $twigFunction->getName(),
            $this->tabsExtension->getFunctions(),
        );

        self::assertContains('tabs', $names);
        self::assertContains('tab', $names);
    }

    public function testGetFunctionNamesWithPrefix(): void
    {
        $tabsExtension = new TabsExtension(
                    $this->templateRenderer,
                    $this->filesystemLoader,
            prefix: 'my_',
        );

        $names = array_map(
            fn(
                TwigFunction $twigFunction,
            ): string => $twigFunction->getName(),
            $tabsExtension->getFunctions(),
        );

        self::assertContains('my_tabs', $names);
        self::assertContains('my_tab', $names);
    }

    public function testTabsWithMultipleBuilders(): void
    {
        $this->templateRenderer
            ->method('renderFromFile')
            ->willReturn('<div>tabs</div>')
        ;

        $result = $this->tabsExtension->tabs(
            new TabBuilder($this->templateRenderer, 'tab-1'),
            new TabBuilder($this->templateRenderer, 'tab-2'),
        );

        self::assertSame('<div>tabs</div>', $result);
    }

    public function testTabsWithArrayOfBuilders(): void
    {
        $this->templateRenderer
            ->method('renderFromFile')
            ->willReturn('<div>tabs</div>')
        ;

        $result = $this->tabsExtension->tabs(
            [
                new TabBuilder($this->templateRenderer, 'tab-1'),
                new TabBuilder($this->templateRenderer, 'tab-2'),
            ],
        );

        self::assertSame('<div>tabs</div>', $result);
    }

    public function testTabBuilderReturnsBuilderWithCorrectId(): void
    {
        $tabBuilder = $this->tabsExtension->tabBuilder('my-tab');

        self::assertSame('my-tab', $tabBuilder->build()->getId());
    }

    public function testLoaderPathIsRegistered(): void
    {
        $registeredPaths = array_map(
            realpath(...),
            $this->filesystemLoader->getPaths('JmfTwigTabs'),
        );

        self::assertContains(
            realpath(__DIR__ . '/../../templates'),
            $registeredPaths,
        );
    }
}
