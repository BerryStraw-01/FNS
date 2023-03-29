<?php

namespace App;

use function dump;

function debug(mixed ...$moreVars) {
  return debugTrace(2, 1, ...$moreVars);
}

function debugTrace(int $traceStart, int $traceSize, mixed ...$moreVars) {
  if (!$_ENV["APP_DEBUG"]) return null;

  $traces = debug_backtrace();
  $str = "____________________debug trace "."__________".$traces[$traceStart]["function"]."_______________";
  for ($i = $traceStart; $i < $traceStart + $traceSize; $i++) {
    /** @var array $trace */
    $trace = $traces[$i];

    $str = $str."     \n";

    if (array_key_exists("class", $trace))
      $str = $str.$trace["class"]." # ";

    $str = $str.$trace["function"]." : ".$trace["line"];
  }
  return dump($str, ...$moreVars);
}
