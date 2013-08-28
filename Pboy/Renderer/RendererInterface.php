<?php

namespace Pboy\Renderer;

interface RendererInterface
{
    public function render($items);

    public function renderView($view, $items);

    public function renderItem($items, $template);

    public function renderList($items, $template);

}
