<?php

namespace Svc\LikeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SvcLikeBundle extends Bundle
{
  public function getPath(): string
  {
    return \dirname(__DIR__);
  }
}
