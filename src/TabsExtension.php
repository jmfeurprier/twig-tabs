<?php

declare(strict_types=1);

namespace Jmf\Twig\Extension\Tabs;

use Jmf\TemplateRendering\TemplateRendererInterface;
use Jmf\Twig\Extension\Tabs\Exception\TabsRenderingException;
use Override;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Extension\AbstractExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TabsExtension extends AbstractExtension
{
    public const string PREFIX_DEFAULT = '';

    /**
     * @throws LoaderError
     */
    public function __construct(
        private readonly TemplateRendererInterface $templateRenderer,
        FilesystemLoader $filesystemLoader,
        private readonly string $templatePath = '@JmfTwigTabs/bootstrap/tabs.html.twig',
        private readonly string $prefix = self::PREFIX_DEFAULT,
    ) {
        $filesystemLoader->addPath(__DIR__ . '/../templates', 'JmfTwigTabs');
    }

    #[Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                "{$this->prefix}tabs",
                $this->tabs(...),
                [
                    'is_safe' => ['html'],
                ],
            ),
            new TwigFunction(
                "{$this->prefix}tab",
                $this->tabBuilder(...),
            ),
        ];
    }

    /**
     * Accepts either multiple TabBuilder arguments or a single array of TabBuilders.
     *
     * @param TabBuilder|list<TabBuilder> ...$args
     *
     * @throws TabsRenderingException
     */
    public function tabs(mixed ...$args): string
    {
        if (count($args) === 1 && is_array($args[0])) {
            /** @var list<TabBuilder> $tabBuilders */
            $tabBuilders = $args[0];
        } else {
            /** @var list<TabBuilder> $tabBuilders */
            $tabBuilders = $args;
        }

        $tabDefinitions = array_map(
            static fn(
                TabBuilder $tabBuilder,
            ): TabDefinition => $tabBuilder->build(),
            $tabBuilders,
        );

        $context = [
            'tabs' => $tabDefinitions,
        ];

        try {
            return $this->templateRenderer->renderFromFile(
                $this->templatePath,
                $context,
            );
        } catch (Throwable $e) {
            throw new TabsRenderingException(
                templatePath: $this->templatePath,
                context:      $context,
                previous:     $e,
            );
        }
    }

    public function tabBuilder(string $id): TabBuilder
    {
        return new TabBuilder(
            $this->templateRenderer,
            $id,
        );
    }
}
