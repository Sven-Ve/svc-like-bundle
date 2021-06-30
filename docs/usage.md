# Usage

## Integration in the controller (example)

```php
use Svc\LikeBundle\Service\LikeHelper;
...
  return $this->render('@SvcVideo/video/run.html.twig', [
    'video' => $video,
    'enableLikes' => $this->enableLikes,
    'liked' => $likeHelper->isLiked(LikeHelper::SOURCE_VIDEO, $video->getId())
    ...
  ]);
```

## Callback (Ajax) function to increment the like count (example)

```php
  /**
   * increase the like count
   *
   * @param Video $video
   * @param LikeHelper $likeHelper
   * @return Response
   */
  public function incLikes(Video $video, LikeHelper $likeHelper): Response
  {
    $response = new JsonResponse();
    $cookieName = null;

    if ($likeHelper->addLike(LikeHelper::SOURCE_VIDEO, $video->getId(), null, $cookieName)) {

      if ($cookieName) {
        $response->headers->setCookie(new Cookie($cookieName, 1, new DateTime('+1 week')));
      }

      $newValue = $video->incLikes();
      $this->getDoctrine()->getManager()->flush();

      $response->setData(['likes' => $newValue]);
    } else {
      $response->setStatusCode(422);
    }

    return $response;
  }
```

## Integration the encore-controller in Twig

```twig
{% if liked is defined and liked %}
  <i class="fas fa-heart like-liked"></i> {{ video.likes }}
{% else %}
  <span data-controller='like' data-like-url-value='{{ path("svc_video_inc_likes", {id: video.id}) }}'>
    <i class="fas fa-heart like-not-liked" data-action="click->like#inc"></i>
    <span data-like-target="counter">{{ video.likes }}</span>
  </span> 
{% endif %}
```