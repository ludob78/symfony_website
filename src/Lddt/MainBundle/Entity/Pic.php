<?php

namespace Lddt\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * http://symfony.com/doc/current/doctrine/lifecycle_callbacks.html
 * Pic
 *
 * @ORM\Table(name="pic")
 * @ORM\Entity(repositoryClass="Lddt\MainBundle\Repository\PicRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Pic
{
    /**
     * @ORM\OneToOne(targetEntity="Lddt\UserBundle\Entity\User",mappedBy="avatar")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="Lddt\MainBundle\Entity\Draw",mappedBy="pic")
     */
    private $draw;

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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * http://symfony.com/doc/current/reference/constraints/File.html#maxsizemessage
     * https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Complete_list_of_MIME_types
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage="Votre image doit faire moins de 1Mo",
     *     mimeTypes = {"image/jpeg", "image/gif","image/png"},
     *     mimeTypesMessage = "Merci de charger une image gif, jpg, jpeg ou png"
     * )
     */
    private $file;

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
     * Set path
     *
     * @param string $path
     *
     * @return Pic
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getFile(){
        return $this->file;
    }

    public function setFile(UploadedFile $file){
        $this->file=$file;

        return $this;
    }

    //Retourner le chemin absolu d'un fichier img
    public function getAbsolutePath(){
        return null === $this->path ? null:$this->getUploadRootDir().'/'.$this->path;
    }

    protected function getUploadRootDir(){
        //Le chemin absolu du répertoire où les fichiers images doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir(){
        //Nom du répertoire upload (si changement doit être fait, c'est ici qu'il faut le faire, les chemins des images téléchargées dans votre projets et appelés dans vos vues twigs seront modifiées.
        return "upload/pics";
    }

    //Méthode pour afficher les images dans les vues twigs
    public function getWebPath(){
        return null === $this->path ? null:$this->getUploadDir().'/'.$this->path;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload(){
        //si fichier chargé
        if (null !== $this->file){
            $this->path=sha1($this->path.uniqid(mt_rand(),true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */

    public function upload(){
        if (null === $this->file){
            return false;
        }
        //Si fichier chargé, on le déplace de la mémoire tmp de apache vers le rép d'upload
        $this->file->move($this->getUploadRootDir(),$this->path);
        //On vide la mémoire tmp une fois que le fichier est déplacé.
        $this->file=null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemove(){
        if ($file=$this->getAbsolutePath()){
            unlink($file);
        }
       /* ou
        if ($this->getAbsolutePath()== true){
            $file=$this->getAbsolutePath();
            unlink($file);
        }*/

    }


    /**
     * Set draw
     *
     * @param \Lddt\MainBundle\Entity\Draw $draw
     *
     * @return Pic
     */
    public function setDraw(\Lddt\MainBundle\Entity\Draw $draw = null)
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

    /**
     * Set user
     *
     * @param \Lddt\UserBundle\Entity\User $user
     *
     * @return Pic
     */
    public function setUser(\Lddt\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Lddt\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
