<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * User
 *
 * @ORM\Table(name="tweets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TweetRepository")
 */
class Tweet
{

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=100)
     */
	private $id;
	private $text;
	private $created_at;

    /**
     * @var bool
     *
     * @ORM\Column(name="hidden", type="boolean")
     */
    private $hidden;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100)
     */
    private $username;

	public function getId()
    {
        return $this->id;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getHidden()
    {
        return $this->hidden;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function __toString() {
        return "".$this->getText();
    }

    public function __construct()
    {
        $this->hidden = 0;
    }
}