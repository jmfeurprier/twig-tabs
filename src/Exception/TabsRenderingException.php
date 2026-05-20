<?php

declare(strict_types=1);

namespace Jmf\Twig\Extension\Tabs\Exception;

use Exception;
use Throwable;

class TabsRenderingException extends Exception
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        private readonly string $templatePath,
        private readonly array $context,
        private readonly Throwable $previous,
    ) {
        parent::__construct(
            message:  "Failed rendering Twig tabs from template at {$this->templatePath}",
            code:     $this->previous->getCode(),
            previous: $this->previous,
        );
    }

    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
