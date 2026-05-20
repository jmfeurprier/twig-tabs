<?php

declare(strict_types=1);

namespace Jmf\Twig\Extension\Tabs;

use PHPUnit\Framework\TestCase;

final class TabDefinitionTest extends TestCase
{
    public function testGetters(): void
    {
        $tabDefinition = new TabDefinition('my-id', 'My Label', '<p>content</p>', 'badge-text');

        self::assertSame('my-id', $tabDefinition->getId());
        self::assertSame('My Label', $tabDefinition->getLabel());
        self::assertSame('<p>content</p>', $tabDefinition->getContent());
        self::assertSame('badge-text', $tabDefinition->getBadge());
    }

    public function testGetBadgeCanBeNull(): void
    {
        $tabDefinition = new TabDefinition('id', 'label', 'content', null);

        self::assertNull($tabDefinition->getBadge());
    }
}
