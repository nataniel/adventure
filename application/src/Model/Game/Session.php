<?php
namespace Main\Model\Game;

use Main\Model\Page;

class Session
{
    private $current_page;
    private $statuses = [];
    private $player_name;
    private $loaded_page;
    private $game;

    public function __construct(int $game, string $player_name, string $page = 'start')
    {
        $this->game = $game;
        $this->player_name = $player_name;
        $this->setCurrentPage($page);
    }

    private function setCurrentPage(string $name)
    {
        $this->current_page = $name;
        $this->loadCurrentPage();
        $this->applyCurrentStatuses();
    }

    public function getCurrentPage(): string
    {
        return $this->current_page;
    }

    public function getGame(): int
    {
        return $this->game;
    }

    public function applyChoice(int $choice): bool
    {
        $current_page = $this->current_page;
        foreach ($this->getAvailableChoices() as $available) {
            if ($choice === $available->id()) {

                $target_page = $available->getTarget();
                try {
                    $this->setCurrentPage($target_page);
                    return true;
                }
                catch (Exception $ex) {
                    $this->current_page = $current_page;
                    throw new Exception(sprintf('Target page for choice "%s" (#%s) is missing, game master has to create it.', $available->getDescription(), $target_page));
                }

            }
        }

        return false;
    }

    private function loadCurrentPage()
    {
        $page = Page::findOneBy([ 'game' => $this->game, 'name' => $this->current_page ]);
        if (empty($page)) {
            throw new Exception(sprintf('Page #%s is missing. Restart game?', $this->current_page));
        }

        $this->loaded_page = $page;
    }

    private function applyCurrentStatuses()
    {
        $page = $this->getLoadedPage();
        foreach (explode(',', $page->getStatus()) as $status) {
            $this->applyStatus($status);
        }
    }

    private function applyStatus(string $status)
    {
        $status = trim($status);
        if (empty($status)) {
            return;
        }

        if (substr($status, 0, 1) == '-') {
            $status = substr($status, 1);
            $this->removeStatus($status);
            return;
        }

        if (substr($status, 0, 1) == '+') {
            $status = substr($status, 1);
        }

        $this->addStatus($status);
    }

    private function removeStatus(string $status)
    {
        if (($key = array_search($status, $this->statuses)) !== false) {
            unset($this->statuses[ $key ]);
        }
    }

    private function addStatus(string $status)
    {
        if (!$this->hasStatus($status)) {
            $this->statuses[] = $status;
        }
    }

    private function hasStatus(string $status): bool
    {
        return array_search($status, $this->statuses) !== false;
    }

    /**
     * @return Page\Choice[]
     */
    public function getAvailableChoices(): array
    {
        $choices = [];
        foreach ($this->getLoadedPage()->getChoices() as $choice) {
            if ($this->hasRequiredStatus($choice->getStatus())) {
                $choices[] = $choice;
            }
        }

        return $choices;
    }

    public function hasRequiredStatus(string $status): bool
    {
        $status = trim($status);
        if (empty($status)) {
            return true;
        }

        if (substr($status, 0, 1) == '-') {
            $status = substr($status, 1);
            return !$this->hasStatus($status);
        }

        if (substr($status, 0, 1) == '+') {
            $status = substr($status, 1);
        }

        return $this->hasStatus($status);
    }

    public function getPlayerName(): string
    {
        return $this->player_name;
    }

    public function getStatuses(): array
    {
        return $this->statuses;
    }

    public function getLoadedPage(): Page
    {
        if (null === $this->loaded_page) {
            throw new Exception(sprintf('No page is loaded (current page: #%s.)', $this->current_page));
        }

        return $this->loaded_page;
    }

    public function __sleep(): array
    {
        return [ 'current_page', 'statuses', 'player_name', 'game' ];
    }

    public function __wakeup()
    {
        try {
            $this->loadCurrentPage();
        }
        catch (Exception $ex) {

        }
    }
}