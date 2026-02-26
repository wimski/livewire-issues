<?php

declare(strict_types=1);

namespace App\Concerns\Livewire;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Livewire\Features\SupportSession\BaseSession;
use Livewire\WithPagination;

trait WithSessionPagination
{
    use WithPagination;

    protected Session $session;

    /**
     * @return string
     */
    abstract function getName();

    /**
     * @return list<string>
     */
    public function getSessionablePageNames(): array
    {
        return ['page'];
    }

    public function bootWithSessionPagination(Session $session): void
    {
        $this->session = $session;
    }

    public function mountWithSessionPagination(Request $request): void
    {
        foreach ($this->getSessionablePageNames() as $pageName) {
            $this->initPaginatorSession($request, $pageName);
        }
    }

    protected function initPaginatorSession(Request $request, string $pageName): void
    {
        if ($request->query->has($pageName)) {
            $this->setPaginatorSession($pageName, (int) $request->query->get($pageName));
            return;
        }

        $sessionKey = $this->makePaginatorSessionKey($pageName);

        if (! $this->session->has($sessionKey)) {
            return;
        }

        $request->query->set($pageName, $this->session->get($sessionKey));
    }

    public function updatedPaginators(int $page, string $pageName): void
    {
        if (! in_array($pageName, $this->getSessionablePageNames())) {
            return;
        }

        $this->setPaginatorSession($pageName, $page);
    }

    protected function setPaginatorSession(string $pageName, int $page): void
    {
        $this->session->put($this->makePaginatorSessionKey($pageName), $page);
    }

    /**
     * @see BaseSession::key()
     */
    protected function makePaginatorSessionKey(string $pageName): string
    {
        return 'lw' . crc32($this->getName() . $pageName);
    }
}
