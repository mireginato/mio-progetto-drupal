<?php

namespace Drupal\il_mio_modulo\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Fornisce un blocco con un saluto personalizzato.
 *
 * @Block(
 * id = "saluto_block",
 * admin_label = @Translation("Il Mio Blocco Custom"),
 * )
 */
class SalutoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Questo è un blocco generato dal mio modulo su Docker!'),
    ];
  }
}