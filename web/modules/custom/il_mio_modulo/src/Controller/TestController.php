<?php

namespace Drupal\il_mio_modulo\Controller;

use Drupal\Core\Controller\ControllerBase;

class TestController extends ControllerBase {
  public function build() {
    return [
      '#markup' => 'Finalmente funziona tutto!',
    ];
  }
}