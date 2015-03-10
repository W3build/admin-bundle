<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 12.1.15
 * Time: 23:08
 */

namespace W3build\AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class Role
 * @package W3build\AdminBundle\Entity
 *
 * @ORM\Table(name="admin_role")
 * @ORM\Entity(repositoryClass="W3build\AdminBundle\Repository\RoleRepository")
 */
class Role implements RoleInterface {

    const ENTITY_NAME = 'W3buildAdminBundle:Role';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection;
     *
     * @ORM\OneToMany(targetEntity="W3build\AdminBundle\Entity\Role", mappedBy="parent")
     */
    private $children;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="W3build\AdminBundle\Entity\Role", inversedBy="children")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $parent;

    public function __construct(){
        $this->children = new ArrayCollection();
    }

    /**
     * Returns the role.
     *
     * This method returns a string representation whenever possible.
     *
     * When the role cannot be represented with sufficient precision by a
     * string, it should return null.
     *
     * @return string|null A string representation of the role, or null
     */
    public function getRole()
    {
        return $this->getId();
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param $children
     * @return $this
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return Role
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param $parent
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

}