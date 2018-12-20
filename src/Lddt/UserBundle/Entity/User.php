<?php

namespace Lddt\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Lddt\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(name="avatar_path", type="string", length=255,nullable=true)
     */
    private $avatarPath;

    /**
     * @ORM\OneToOne(targetEntity="Lddt\MainBundle\Entity\Pic",cascade={"persist"},inversedBy="user")
     * @ORM\JoinColumn(name="id_avatar",referencedColumnName="id")
     */
    private $avatar;


    /**
     * @ORM\Column(name="instagram_username",type="string",length=255,nullable=true)
     */
    private $InstagramUsername;

    /**
     * @ORM\Column(name="instagram_id",type="string",length=255,nullable=true)
     */
    private $InstagramId;

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
     * Set avatarPath
     *
     * @param string $avatarPath
     *
     * @return User
     */
    public function setAvatarPath($avatarPath)
    {
        $this->avatarPath = $avatarPath;

        return $this;
    }

    /**
     * Get avatarPath
     *
     * @return string
     */
    public function getAvatarPath()
    {
        return $this->avatarPath;
    }

    /**
     * Set avatar
     *
     * @param \Lddt\MainBundle\Entity\Pic $avatar
     *
     * @return User
     */
    public function setAvatar(\Lddt\MainBundle\Entity\Pic $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Lddt\MainBundle\Entity\Pic
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set instagramUsername
     *
     * @param string $instagramUsername
     *
     * @return User
     */
    public function setInstagramUsername($instagramUsername)
    {
        $this->InstagramUsername = $instagramUsername;

        return $this;
    }

    /**
     * Get instagramUsername
     *
     * @return string
     */
    public function getInstagramUsername()
    {
        return $this->InstagramUsername;
    }

    /**
     * Set instagramId
     *
     * @param string $instagramId
     *
     * @return User
     */
    public function setInstagramId($instagramId)
    {
        $this->InstagramId = $instagramId;

        return $this;
    }

    /**
     * Get instagramId
     *
     * @return string
     */
    public function getInstagramId()
    {
        return $this->InstagramId;
    }
}
