<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Concerns\Livewire\WithSessionPagination;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class Issue extends Component
{
    use WithSessionPagination;

    public function render(): View
    {
        return view('livewire.issue', [
            'items' => $this->getItems(),
        ]);
    }

    protected function getItems(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(range(1, 5), 100, 5);
    }
}
