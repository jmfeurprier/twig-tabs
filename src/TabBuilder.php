<?php

declare(strict_types=1);

namespace Jmf\Twig\Extension\Tabs;

use Jmf\TemplateRendering\TemplateRendererInterface;
use Jmf\Twig\Extension\Tabs\Exception\TabsRenderingException;
use Throwable;

class TabBuilder
{
    private string $label = '';

    private string $content = '';

    private ?string $badge = null;

    public function __construct(
        private readonly TemplateRendererInterface $templateRenderer,
        private readonly string $id,
    ) {
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @throws TabsRenderingException
     */
    public function include(
        string $path,
        array $parameters = [],
    ): self {
        try {
            return $this->content(
                $this->templateRenderer->renderFromFile($path, $parameters),
            );
        } catch (Throwable $e) {
            throw new TabsRenderingException(
                templatePath: $path,
                context:      $parameters,
                previous:     $e,
            );
        }
    }

    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function badge(?string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function build(): TabDefinition
    {
        return new TabDefinition(
            $this->id,
            $this->label,
            $this->content,
            $this->badge,
        );
    }
}
