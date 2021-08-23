<?php
namespace Main\Model\Page;

use E4u\Common\StringTools;
use E4u\Model\Entity;
use Main\Model\Page;
use Main\Model\Game;

/**
 * @Entity
 * @Table(name="pages_choices")
 */
class Choice extends Entity
{
    /**
     * @var Page
     * @ManyToOne(targetEntity="Main\Model\Page", inversedBy="choices")
     */
    protected $parent;

    /** @Column(type="string") */
    protected $target = '';

    /** @Column(type="text") */
    protected $description = '';

    /** @Column(type="string") */
    protected $status = '';

    /** @Column(type="integer", nullable=true) */
    protected $position;

    public function getParent(): ?Page
    {
        return $this->parent;
    }

    /**
     * @param  mixed $parent
     * @param  bool $keepConsistency
     * @return $this
     */
    public function setParent($parent, bool $keepConsistency = true): self
    {
        $this->_set('parent', $parent, $keepConsistency);
        return $this;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function findTargetPage(): ?Page
    {
        $game = $this->parent->getGame();
        return Page::findOneBy([ 'name' => $this->target, 'game' => $game->id() ]);
    }

    public function setTarget(string $target): self
    {
        $this->target = StringTools::toUrl(trim($target, '#'), true);
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function toString(): string
    {
        return sprintf('#%s: %s', $this->target, $this->description);
    }

    public function getStatusRequirements(): array
    {
        $result = [];
        foreach (explode(',', $this->status) as $status) {
            if ($requirement = $this->match($status)) {
                $result[] = $requirement;
            }
        }

        return $result;
    }

    private function match($status): ?array
    {
        $status = trim($status);
        $pattern = '/^(?<name>[[:alpha:]][[:alnum:]]+)(?<sign>=|\<|\>|>=|\<=)(?<value>(true|false|[0-9]+))?$/iu';
        return preg_match($pattern, $status, $matches)
            ? $matches
            : null;
    }

    public function testStatusRequirements()
    {
        foreach (explode(',', $this->status) as $status) {
            $requirement = $this->match($status);
            if (!empty($status) && is_null($requirement)) {
                throw new Game\Exception(sprintf('Invalid status requirement: <strong>%s</strong>', $status));
            }
        }
    }
}