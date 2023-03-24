<?php

namespace App\Entity;

class FormPhase {
  private string $uuid;
  private int $phase;

  public function __construct() {
    $this->phase = 0;
    $this->uuid = uniqid();
  }

  /**
   * @return string
   */
  public function getUuid(): string {
    return $this->uuid;
  }

  /**
   * @param string $uuid
   */
  public function setUuid(string $uuid): void {
    $this->uuid = $uuid;
  }

  /**
   * @return int
   */
  public function getPhase(): int {
    return $this->phase;
  }

  /**
   * @param int $phase
   */
  public function setPhase(int $phase): void {
    $this->phase = $phase;
  }
}