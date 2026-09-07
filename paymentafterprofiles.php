<?php

require_once 'paymentafterprofiles.civix.php';

use CRM_Paymentafterprofiles_ExtensionUtil as E;

/**
 * Implements hook_civicrm_check().
 */
function paymentafterprofiles_civicrm_check(&$messages): void {
  // Hash of the original CORE template
  // Update both values after reviewing and incorporating core changes.
  $reviewedCoreVersion = '6.17.3';
  $reviewedCoreHash = '6803e048e3cc0eeee2ea4daa60e9519fbe7c1bd1ffd197b2c813914c60518995';
  $coreTemplate = Civi::paths()->getPath('[civicrm.root]/templates/CRM/Contribute/Form/Contribution/Main.tpl');
  // Report read failures through System Status, including failures during hashing.
  $currentHash = is_file($coreTemplate) && is_readable($coreTemplate)
    ? @hash_file('sha256', $coreTemplate)
    : FALSE;

  if ($currentHash === $reviewedCoreHash) {
    return;
  }

  if ($currentHash === FALSE) {
    $title = E::ts('Contribution template verification failed');
    $message = E::ts('Unable to verify core Main.tpl because the file could not be read.');
  }
  else {
    $title = E::ts('Contribution template override needs review');
    $message = E::ts('Your installed core Main.tpl differs from the template reviewed for the Payment After Profiles override in CiviCRM %1. A developer should compare the installed core template with the extension override.', [1 => $reviewedCoreVersion]);
  }

  $messages[] = new CRM_Utils_Check_Message(
    'paymentafterprofiles_core_template',
    $message,
    $title,
    \Psr\Log\LogLevel::WARNING,
    'fa-file-code-o'
  );
}

/**
 * Implements hook_civicrm_alterTemplateFile().
 */
function paymentafterprofiles_civicrm_alterTemplateFile($formName, &$form, $context, &$tplName): void {
  if ($formName === 'CRM_Contribute_Form_Contribution_Main' && $context === 'form') {
    $tplName = E::path('templates/CRM/Contribute/Form/Contribution/Main.tpl');
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function paymentafterprofiles_civicrm_config(&$config): void {
  _paymentafterprofiles_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function paymentafterprofiles_civicrm_install(): void {
  _paymentafterprofiles_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function paymentafterprofiles_civicrm_enable(): void {
  _paymentafterprofiles_civix_civicrm_enable();
}
