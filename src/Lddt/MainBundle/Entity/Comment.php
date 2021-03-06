<?php

namespace Lddt\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lddt\UserBundle\Entity\User;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="Lddt\MainBundle\Repository\CommentRepository")
 */
class Comment
{
    //clé étrangère manuellement saisie
    /**
     * @ORM\ManyToOne(targetEntity="Lddt\MainBundle\Entity\Draw",inversedBy="comments")
     * @ORM\JoinColumn(name="id_draw",referencedColumnName="id",nullable=false)
     */
    private $draw;

    /**
     * @ORM\ManyToOne(targetEntity="Lddt\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="id_critic",referencedColumnName="id",onDelete="CASCADE")
     */
    private $critic;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255,nullable=true)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set pseudo
     *
     * @param string $pseudo
     *
     * @return Comment
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Comment
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set draw
     *
     * @param \Lddt\MainBundle\Entity\Draw $draw
     *
     * @return Comment
     */
    public function setDraw(\Lddt\MainBundle\Entity\Draw $draw)
    {
        $this->draw = $draw;

        return $this;
    }

    /**
     * Get draw
     *
     * @return \Lddt\MainBundle\Entity\Draw
     */
    public function getDraw()
    {
        return $this->draw;
    }
    public function __construct(Draw $draw,User $critic)
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->setDraw($draw);
        $this->setCritic($critic);
    }

    /**
     * Set critic
     *
     * @param \Lddt\UserBundle\Entity\User $critic
     *
     * @return Comment
     */
    public function setCritic(\Lddt\UserBundle\Entity\User $critic = null)
    {
        $this->critic = $critic;

        return $this;
    }

    /**
     * Get critic
     *
     * @return \Lddt\UserBundle\Entity\User
     */
    public function getCritic()
    {
        return $this->critic;
    }
}
