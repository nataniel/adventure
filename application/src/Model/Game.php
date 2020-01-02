<?php
namespace Main\Model;

class Game
{
    /** @var Page */
    private $current_page;

    private $current_name = 'start';
    private $statuses = [];
    private $player_name;

    public function __construct($player_name)
    {
        $this->player_name = $player_name;
        $this->applyCurrentStatuses();
    }

    /**
     * @param  int $choice
     * @return bool
     */
    public function applyChoice($choice)
    {
        foreach ($this->getAvailableChoices() as $available) {
            if ($choice === $available->id()) {

                $this->setCurrentPage($available->getTarget());
                $this->applyCurrentStatuses();
                return true;

            }
        }

        return false;
    }

    /**
     * @return $this
     * @throws Game\Exception
     */
    private function applyCurrentStatuses()
    {
        $page = $this->getCurrentPage();
        foreach (explode(',', $page->getStatus()) as $status) {
            $this->applyStatus($status);
        }

        return $this;
    }

    /**
     * @param  string $status
     * @return $this
     */
    private function applyStatus($status)
    {
        $status = trim($status);
        if (empty($status)) {
            return $this;
        }

        if (substr($status, 0, 1) == '-') {
            $status = substr($status, 1);
            $this->removeStatus($status);
            return $this;
        }

        if (substr($status, 0, 1) == '+') {
            $status = substr($status, 1);
        }

        $this->addStatus($status);
        return $this;
    }

    /**
     * @param  string $status
     * @return $this
     */
    private function removeStatus($status)
    {
        if (($key = array_search($status, $this->statuses)) !== false) {
            unset($this->statuses[ $key ]);
        }

        return $this;
    }

    /**
     * @param  string $status
     * @return $this
     */
    private function addStatus($status)
    {
        if (!$this->hasStatus($status)) {
            $this->statuses[] = $status;
        }

        return $this;
    }

    /**
     * @param  string $status
     * @return bool
     */
    private function hasStatus($status)
    {
        return array_search($status, $this->statuses) !== false;
    }

    /**
     * @param  string $name
     * @return $this
     */
    private function setCurrentPage($name)
    {
        $this->current_name = $name;
        $this->current_page = null;
        return $this;
    }

    /**
     * @return Page\Choice[]
     */
    public function getAvailableChoices()
    {
        $choices = [];
        foreach ($this->getCurrentPage()->getChoices() as $choice) {
            if ($this->hasRequiredStatus($choice->getStatus())) {
                $choices[] = $choice;
            }
        }

        return $choices;
    }

    /**
     * @param  string $status
     * @return bool
     */
    public function hasRequiredStatus($status)
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

    public function getPlayerName()
    {
        return $this->player_name;
    }

    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @return Page
     * @throws Game\Exception
     */
    public function getCurrentPage()
    {
        $this->loadCurrentPage();
        return $this->current_page;
    }

    /**
     * @return $this
     * @throws Game\Exception
     */
    private function loadCurrentPage()
    {
        if (empty($this->current_page)) {
            $this->current_page = Page::findOneBy([ 'name' => $this->current_name ]);
        }

        if (empty($this->current_page)) {
            throw new Game\Exception(sprintf('Page #%s is missing.', $this->current_name));
        }

        return $this;
    }

    public function __sleep()
    {
        return [ 'current_name', 'statuses', 'player_name' ];
    }
}