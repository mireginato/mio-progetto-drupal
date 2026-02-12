<?php

declare(strict_types=1);

namespace Drupal\import_excel_capitolato_mini\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;

use Drupal\Core\Url;
use PhpOffice\PhpSpreadsheet\IOFactory;



/**
 * Provides a ImportExcelCapitolatoMini form.
 */
final class ExcelFormMini extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string
  {
    return 'import_excel_capitolato_mini_excel_form_mini';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array
  {

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
    ];

    $form['excel_file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Carica il file Excel (.xlsx)'),
      '#upload_location' => 'public://imports/',
      '#upload_validators' => [
        'FileExtension' => ['extensions' => 'xlsx'],
      ],
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
      ],
    ];


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void
  {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if (mb_strlen($form_state->getValue('message')) < 10) {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('Message should be at least 10 characters.'),
    //     );
    //   }
    // @endcode
  }

  /**
   * {@inheritdoc}
   */
  /*
  public function submitForm(array &$form, FormStateInterface $form_state): void
  {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    $form_state->setRedirect('<front>');
  }
  use PhpOffice\PhpSpreadsheet\IOFactory;
use Drupal\Core\Database\Database;
*/

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $file_id = $form_state->getValue('excel_file')[0];

    $peek_memory = memory_get_usage(true) / 1024 / 1024;
    $this->messenger()->addStatus($this->t('inizio: Picco memoria utilizzata: @mem MB', [
      '@mem' => round($peek_memory, 2)
    ]));

    // use Drupal\file\Entity\File; 
    //  $file = \Drupal\Core\File\Entity\File::load($file_id);
    //    $file = \Drupal\File\Entity\File::load($file_id);
    $file = File::load($file_id);
    $path = \Drupal::service('file_system')->realpath($file->getFileUri());

    $spreadsheet = IOFactory::load($path);
    /*
    per alleggerire il caricamento del file Excel, puoi utilizzare le seguenti opzioni con PhpSpreadsheet:

$reader = IOFactory::createReader('Xlsx');

// 1. Carica solo i dati, ignora stili, bordi, colori e formattazione (RISPARMIO RAM ENORME)
$reader->setReadDataOnly(true); 

// 2. Opzionale: carica solo il primo foglio se sai che i dati sono lì
$reader->setLoadSheetsOnly(['Foglio1']); 

$spreadsheet = $reader->load($physical_path);
    */
    $data = $spreadsheet->getActiveSheet()->toArray();

    $connection = Database::getConnection();
    // Svuota completamente la tabella prima di inserire i nuovi dati
    $connection->truncate('dati_importati')->execute();

    // Saltiamo la prima riga se contiene le intestazioni
    foreach (array_slice($data, 1) as $row) {
      $connection->insert('dati_importati')
        ->fields([
          'alfa' => $row[0], // Colonna A
          'beta' => $row[1], // Colonna B
          'gamma' => $row[2], // Colonna C
        ])
        ->execute();
    }
    if ($file) {
      $file->setPermanent();
      $file->save();
    }

    $this->messenger()->addStatus($this->t('Importazione completata!'));

    //$peek_memory = memory_get_peak_usage(true) / 1024 / 1024;
    $peek_memory = memory_get_usage(true) / 1024 / 1024;
    $this->messenger()->addStatus($this->t('fine: Picco memoria utilizzata: @mem MB', [
      '@mem' => round($peek_memory, 2)
    ]));
  }
  // link: https://tuo-sito.ddev.site/admin/config/content/excel-import
  // link: http://mio-drupal.ddev.site/admin/reports/updates
  // http://mio-drupal.ddev.site/admin/config/content/import_excel_capitolato_mini


}
