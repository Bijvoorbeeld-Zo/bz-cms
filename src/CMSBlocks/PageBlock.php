<?php

namespace JornBoerema\BzCMS\CMSBlocks;

abstract class PageBlock
{
    protected string $view = '';
    protected string $label = '';

    abstract public function form(): array;

    public function render()
    {
        return view($this->view);
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
