<?php
namespace Main\Model\Game;

use Main\Model\Page;

class Session
{
    private $current_page;
    private $statuses = [];
    private $player_name;
    private $loaded_page;

    public function __construct($player_name, $page = 'start')
    {
        $this->player_name = $player_name;
        $this->setCurrentPage($page);
    }

    /**
     * @param  string $name
     * @return $this
     * @throws Exception
     */
    private function setCurrentPage($name)
    {
        $this->current_page = $name;
        $this->loadCurrentPage();
        $this->applyCurrentStatuses();
        return $this;
    }

    public function getCurrentPage()
    {
        return $this->current_page;
    }

    /**
     * @param  int $choice
     * @return bool
     * @throws Exception
     */
    public function applyChoice($choice)
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
    /**
     * @return $this
     * @throws Exception
     */
    private function loadCurrentPage()
    {
        $page = Page::findOneBy([ 'name' => $this->current_page ]);
        if (empty($page)) {
            throw new Exception(sprintf('Page #%s is missing. Restart game?', $this->current_page));
        }

        $this->loaded_page = $page;
        return $this;
    }


    /**
     * @return $this
     */
    private function applyCurrentStatuses()
    {
        $page = $this->getLoadedPage();
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
     * @return Page\Choice[]
     */
    public function getAvailableChoices()
    {
        $choices = [];
        foreach ($this->getLoadedPage()->getChoices() as $choice) {
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

    /**
     * @return string
     */
    public function getPlayerName()
    {
        return $this->player_name;
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @return Page
     */
    public function getLoadedPage()
    {
        if (null === $this->loaded_page) {
            throw new Exception(sprintf('No page is loaded (current page: #%s.)', $this->current_page));
        }

        return $this->loaded_page;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return [ 'current_page', 'statuses', 'player_name' ];
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