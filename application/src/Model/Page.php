<?php
namespace Main\Model;

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
    protected $name;

    /** @Column(type="string") */
    protected $description;

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

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('#%s: %s', $this->name, $this->description);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $name
     * @return Page
     */
    public function setName($name)
    {
        $this->name = !empty($name)
            ? StringTools::toUrl(trim($name, '#'), true)
            : null;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param  string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image ?: null;
        return $this;
    }

    /**
     * @return Page\Choice[]
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * @param  Page\Choice|array|int $choice
     * @param  bool $keepConsistency
     * @return $this
     */
    public function addToChoices($choice, $keepConsistency = true)
    {
        $this->_addTo('choices', $choice, $keepConsistency);
        return $this;
    }

    /**
     * @return Page\Choice[]
     */
    public function findSourceChoices()
    {
        return Page\Choice::findBy([ 'target' => $this->name ]);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function toUrl()
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'route' => 'page',
        ];
    }
}