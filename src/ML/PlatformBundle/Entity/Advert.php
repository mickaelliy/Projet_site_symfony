<?php

namespace ML\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="ML\PlatformBundle\Repository\AdvertRepository")
 */
class Advert
{
    /**
    * @ORM\OneToMany(targetEntity="ML\PlatformBundle\Entity\Application", mappedBy="advert")
    */
    private $applications;

    /**
    * @ORM\ManyToMany(targetEntity="ML\PlatformBundle\Entity\Category", cascade={"persist"})
    * @ORM\JoinTable(name="ml_advert_category")
    */
    private $categories;

    /**
    * @ORM\OneToOne(targetEntity="ML\PlatformBundle\Entity\Image", cascade={"persist", "remove"})
    *
    */
    private $image;

    /**
    * @ORM\ManytoMany(targetEntity="ML\PlatformBundle\Entity\Image", cascade={"persist"})
    *
    */

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;


    // Comme la propriété $categories doit être un ArrayCollection,
    // On doit la définir dans un constructeur :
    public function __construct()
    {
      $this->applications = new ArrayCollection();
      $this->date       = new \Datetime();
      $this->categories = new ArrayCollection();
    }

    // Notez le singulier, on ajoute une seule catégorie à la fois
    public function addCategory(Category $category)
    {
      // Ici, on utilise l'ArrayCollection vraiment comme un tableau
      $this->categories[] = $category;
    }

    public function removeCategory(Category $category)
    {
      // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
      $this->categories->removeElement($category);
    }

    // Notez le pluriel, on récupère une liste de catégories ici !
    public function getCategories()
    {
      return $this->categories;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author.
     *
     * @param string $author
     *
     * @return Advert
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set image.
     *
     * @param \ML\PlatformBundle\Entity\Image|null $image
     *
     * @return Advert
     */
    public function setImage(\ML\PlatformBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return \ML\PlatformBundle\Entity\Image|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add application.
     *
     * @param \ML\PlatformBundle\Entity\Application $application
     *
     * @return Advert
     */
    public function addApplication(\ML\PlatformBundle\Entity\Application $application)
    {
        $this->applications[] = $application;

        // On lie l'annonce à la candidature
        $application->setAdvert($this);

        return $this;
    }

    /**
     * Remove application.
     *
     * @param \ML\PlatformBundle\Entity\Application $application
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeApplication(\ML\PlatformBundle\Entity\Application $application)
    {
        return $this->applications->removeElement($application);
    }

    /**
     * Get applications.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplications()
    {
        return $this->applications;
    }
}
