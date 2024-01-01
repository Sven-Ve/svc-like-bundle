<?php

namespace Svc\LikeBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Svc\LikeBundle\Entity\Likes;
use Svc\LikeBundle\Repository\LikesRepository;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * helper function for links.
 */
class LikeHelper
{
  public const SOURCE_VIDEO = 1;

  public const LIKE_TYPE_LIKE = 1; // for the future, mabe we have more than one like type (dislike??)

  public function __construct(private LikesRepository $likesRep, private Security $security, private EntityManagerInterface $entityManager)
  {
  }

  /**
   * check, if a like for this source und this sourceID and this user exists.
   *
   * @param int      $source   type of source, one of the constants LikeHelper::SOURE*
   * @param int      $sourceID internal id within the source
   * @param int|null $likeType type of like (future...)
   *
   * @return bool true: like already set
   */
  public function isLiked(int $source, int $sourceID, ?int $likeType = LikeHelper::LIKE_TYPE_LIKE): bool
  {
    if ($user = $this->security->getUser()) {
      /* @phpstan-ignore-next-line */
      return $this->likesRep->findOneBy(['source' => $source, 'sourceID' => $sourceID, 'userID' => $user->getId()]) ? true : false;
    } else {
      return array_key_exists($this->createCookieName($source, $sourceID), $_COOKIE);
    }
  }

  /**
   * add a like (store it in the entity).
   *
   * @param int      $source   type of source, one of the constants LikeHelper::SOURE*
   * @param int      $sourceID internal id within the source
   * @param int|null $likeType type of like (future...)
   *
   * @return bool true: successfull, false: failed, maybe like exists
   */
  public function addLike(int $source, int $sourceID, ?int $likeType = LikeHelper::LIKE_TYPE_LIKE, string &$cookieName = null): bool
  {
    if ($user = $this->security->getUser()) {
      $like = new Likes();
      $like->setSource($source);
      $like->setSourceID($sourceID);
      $like->setUserID($user->getId()); /* @phpstan-ignore-line */
      $this->entityManager->persist($like);
      try {
        $this->entityManager->flush();
      } catch (\Exception) {
        return false;
      }
    } else {
      $cookieName = $this->createCookieName($source, $sourceID);
    }

    return true;
  }

  /**
   * define the cookie name for the current object.
   *
   * @param int      $source   type of source, one of the constants LikeHelper::SOURE*
   * @param int      $sourceID internal id within the source
   * @param int|null $likeType type of like (future...)
   *
   * @return string the name of the cookie
   */
  private function createCookieName(int $source, int $sourceID, ?int $likeType = LikeHelper::LIKE_TYPE_LIKE): string
  {
    return 'Like-' . $source . '-' . $sourceID;
  }
}
