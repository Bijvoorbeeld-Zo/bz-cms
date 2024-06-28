<?php

namespace JornBoerema\BzCMS\Livewire;

use Illuminate\View\View;
use JornBoerema\BzCMS\Models\Page;
use Livewire\Component;

class PageTemplate extends Component
{
    public Page $page;

    public function mount(): void
    {
        $this->page = Page::where('slug', '=', request()->path())->firstOrFail();
    }

    public function render(): View
    {
        return view('bz-cms::livewire.page-template');
    }
}
