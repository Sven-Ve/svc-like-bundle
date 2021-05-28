<?php

namespace Svc\LikeBundle\Entity;

use Svc\LikeBundle\Repository\LikesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=LikesRepository::class)
 * @UniqueEntity(fields={"source", "sourceID", "userID"},
 *    errorPath="sourceID",
 *    message="You cannot like twice for the same object."
 * )
 * @Table(uniqueConstraints={@UniqueConstraint(columns={"source", "source_id","user_id"})})  
 */
class Likes
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="smallint")
   */
  private $source;

  /**
   * @ORM\Column(type="integer")
   */
  private $sourceID;

  /**
   * @ORM\Column(type="integer")
   */
  private $userID;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getSource(): ?int
  {
    return $this->source;
  }

  public function setSource(int $source): self
  {
    $this->source = $source;

    return $this;
  }

  public function getSourceID(): ?int
  {
    return $this->sourceID;
  }

  public function setSourceID(int $sourceID): self
  {
    $this->sourceID = $sourceID;

    return $this;
  }

  public function getUserID(): ?int
  {
    return $this->userID;
  }

  public function setUserID(int $userID): self
  {
    $this->userID = $userID;

    return $this;
  }
}
