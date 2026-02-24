<?php

namespace Drupal\il_mio_modulo\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 * id = "saluto_block",
 * admin_label = @Translation("Il Mio Blocco Custom"),
 * )
 */
class SalutoBlock extends BlockBase {

  // 1. Definisce il valore predefinito
  public function defaultConfiguration() {
    return ['messaggio_personalizzato' => 'Ciao dal codice!'];
  }

  // 2. Crea il campo nel pannello di amministrazione
  public function blockForm($form, FormStateInterface $form_state) {
    $form['messaggio_personalizzato'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Che messaggio vuoi visualizzare?'),
      '#default_value' => $this->configuration['messaggio_personalizzato'],
    ];
    return $form;
  }

  // 3. Salva il valore inserito
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['messaggio_personalizzato'] = $form_state->getValue('messaggio_personalizzato');
  }

  // 4. Mostra il risultato nel sito
  public function build() {
    $messaggio = $this->configuration['messaggio_personalizzato'];
    return [
      '#markup' => $this->t('Il messaggio salvato è: @text', ['@text' => $messaggio]),
    ];
  }
}