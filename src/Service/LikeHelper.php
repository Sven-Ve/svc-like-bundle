<?php

namespace Svc\LikeBundle\Service;

use Svc\LikeBundle\Entity\Likes;
use Svc\LikeBundle\Repository\LikesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;

/**
 * helper function for links
 */
class LikeHelper
{

  public const SOURCE_VIDEO = 1;

  public const LIKE_TYPE_LIKE = 1;  // for the future, mabe we have more than one like type (dislike??)

  private $likesRep;
  private $security;
  private $entityManager;

  public function __construct(LikesRepository $likesRep, Security $security, EntityManagerInterface $entityManager)
  {
    $this->likesRep = $likesRep;
    $this->security = $security;
    $this->entityManager = $entityManager;
  }

  /**
   * check, if a like for this source und this sourceID and this user exists
   *
   * @param integer $source type of source, one of the constants LikeHelper::SOURE*
   * @param integer $sourceID internal id within the source
   * @param integer|null $likeType type of like (future...)
   * @return boolean true: like already set
   */
  public function isLiked(int $source, int $sourceID, ?int $likeType = LikeHelper::LIKE_TYPE_LIKE): bool
  {
    if ($user = $this->security->getUser()) {
      return $this->likesRep->findOneBy(['source' => $source, 'sourceID' => $sourceID, 'userID' => $user->getId()]) ? true : false;
    } else {
      return array_key_exists($this->createCookieName($source, $sourceID), $_COOKIE);
    }
    return false;
  }

  /**
   * add a like (store it in the entity)
   *
   * @param integer $source type of source, one of the constants LikeHelper::SOURE*
   * @param integer $sourceID internal id within the source
   * @param integer|null $likeType type of like (future...)
   * @param string|null $cookieName
   * @return boolean true: successfull, false: failed, maybe like exists
   */
  public function addLike(int $source, int $sourceID, ?int $likeType = LikeHelper::LIKE_TYPE_LIKE, ?string &$cookieName = null): bool
  {
    if ($user = $this->security->getUser()) {
      $like = new Likes();
      $like->setSource($source);
      $like->setSourceID($sourceID);
      $like->setUserID($user->getId());
      $this->entityManager->persist($like);
      try {
        $this->entityManager->flush();
      } catch (Exception $e) {
        return false;
      }
    } else {
      $cookieName = $this->createCookieName($source, $sourceID);
    }
    return true;
  }

  /**
   * define the cookie name for the current object
   *
   * @param integer $source type of source, one of the constants LikeHelper::SOURE*
   * @param integer $sourceID internal id within the source
   * @param integer|null $likeType type of like (future...)
   * @return string the name of the cookie
   */
  private function createCookieName(int $source, int $sourceID, ?int $likeType = LikeHelper::LIKE_TYPE_LIKE): string
  {
    return 'Like-' . $source . '-' . $sourceID;
  }
}
