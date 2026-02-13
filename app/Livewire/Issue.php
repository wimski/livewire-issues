<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;

class Issue extends Component
{
    #[Session]
    #[Url(history: true, except: '')]
    public string $value = '';

    public function render(): View
    {
        return view('livewire.issue');
    }
}
