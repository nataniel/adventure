<?php
namespace Main\Model;

use Doctrine\ORM\PersistentCollection;
use E4u\Common\StringTools;
use E4u\Model\Entity;

/**
 * @Entity
 * @Table(name="pages", uniqueConstraints={
 *     @UniqueConstraint(name="game_page", columns={"game_id", "name"})
 * })
 */
class Page extends Entity
{
    /**
     * @var Game
     * @ManyToOne(targetEntity="Main\Model\Game", inversedBy="pages")
     */
    protected $game;

    /** @Column(type="string") */
    protected $name = '';

    /** @Column(type="string") */
    protected $description = '';

    /** @Column(type="text") */
    protected $content = '';

    /** @Column(type="string") */
    protected $status = '';

    /** @Column(type="string", nullable=true) */
    protected $image;

    /** @Column(type="datetime", nullable=true) */
    protected $created_at;

    /** @Column(type="datetime", nullable=true) */
    protected $updated_at;

    /**
     * @var Page\Choice[]
     * @OneToMany(targetEntity="Main\Model\Page\Choice", mappedBy="parent", cascade={"all"})
     * @OrderBy({"position" = "ASC"})
     **/
    protected $choices;

    public function __toString(): string
    {
        return sprintf('#%s: %s', $this->name, $this->description);
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setName(string $name): self
    {
        $this->name = !empty($name)
            ? StringTools::toUrl(trim($name, '#'), true)
            : null;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image ?: null;
        return $this;
    }

    /**
     * @return Page\Choice[]|PersistentCollection
     */
    public function getChoices(): PersistentCollection
    {
        return $this->choices;
    }

    /**
     * @param  Page\Choice|array|int $choice
     * @param  bool $keepConsistency
     * @return $this
     */
    public function addToChoices($choice, bool $keepConsistency = true): self
    {
        $this->_addTo('choices', $choice, $keepConsistency);
        return $this;
    }

    /**
     * @todo przerobic na poprawne zapytanie SQL
     * @return Page\Choice[]
     */
    public function findSourceChoices(): array
    {
        $choices = [];
        foreach (Page\Choice::findBy([ 'target' => $this->name ]) as $choice) {
            if ($choice->getParent()->getGame()->id() == $this->getGame()->id()) {
                $choices[] = $choice;
            }
        }

        return $choices;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function toUrl(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'route' => 'page',
        ];
    }
}