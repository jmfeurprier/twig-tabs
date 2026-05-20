<?php

declare(strict_types=1);

namespace Jmf\Twig\Extension\Tabs;

readonly class TabDefinition
{
    public function __construct(
        private string $id,
        private string $label,
        private string $content,
        private ?string $badge,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getBadge(): ?string
    {
        return $this->badge;
    }
}
