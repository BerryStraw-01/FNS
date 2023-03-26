<?php

namespace App\FormEntity\Auth;

use Symfony\Component\Uid\Uuid;

class FormUuid {
  private Uuid $uuid;

  public function __construct(Uuid $uuid = null) {
    if ($uuid == null) $this->uuid = new Uuid();
    else $this->uuid = $uuid;
  }

  /**
   * @return Uuid
   */
  public function getUuid(): Uuid {
    return $this->uuid;
  }

  /**
   * @param Uuid $uuid
   */
  public function setUuid(Uuid $uuid): void {
    $this->uuid = $uuid;
  }
}