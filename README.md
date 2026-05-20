Twig extension : tabs
=====================

Renders tabbed content from Twig templates.

## Installation

```shell
composer require jmf/twig-tabs
```

## Setup

Pass the Twig `FilesystemLoader` when registering the extension.
The `@JmfTwigTabs` namespace is registered automatically:

```php
$twig->addExtension(new TabsExtension($templateRenderer, $loader));
```

## Usage in Twig templates

### tabs() and tab()

```twig
{{ tabs(
    tab('articles').label('First tab').content('<p>Content 1</p>'),
    tab('authors').label('Second tab').include('path/to/template.html.twig', {foo: bar}),
    tab('comments').label('With badge').content('<p>Content 3</p>').badge('5'),
) }}
```

Or passing an array:

```twig
{{ tabs(myTabBuilders) }}
```

### tab() builder methods

| Method | Description |
|--------|-------------|
| `.label(string $label)` | Tab header label |
| `.content(string $html)` | Raw HTML content for the tab pane |
| `.include(string $path, array $parameters)` | Render a template as the tab pane content |
| `.badge(?string $badge)` | Optional badge displayed next to the label |

## Customising templates

Override individual templates by pointing `$templatePath` to your own entry point, or replace the `@JmfTwigTabs` namespace with your own templates directory. The five templates are:

- `bootstrap/tabs.html.twig` — entry point, includes nav and content
- `bootstrap/nav-tabs.html.twig` — `<ul>` tab navigation
- `bootstrap/nav-item.html.twig` — individual tab `<li>` + `<a>`
- `bootstrap/tab-content.html.twig` — `<div class="tab-content">` wrapper
- `bootstrap/tab-pane.html.twig` — individual tab pane `<div>`
