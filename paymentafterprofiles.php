<?php

require_once 'paymentafterprofiles.civix.php';

use CRM_Paymentafterprofiles_ExtensionUtil as E;

/**
 * Implements hook_civicrm_check().
 * Warns when the core template differs from the reviewed copy or cannot be read.
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
 * Selects the reordered template only for contribution pages that opted in.
 */
function paymentafterprofiles_civicrm_alterTemplateFile($formName, &$form, $context, &$tplName): void {
  // CiviCRM's shared template loader passes 'page' even for forms.
  if ($formName === 'CRM_Contribute_Form_Contribution_Main'
    && in_array((int) $form->getVar('_id'), _paymentafterprofiles_page_ids(), TRUE)) {
    $tplName = E::path('templates/CRM/Paymentafterprofiles/Contribute/Form/Contribution/Main.tpl');
  }
}

/**
 * Return the contribution pages which opted into the layout.
 * Normalizes the saved setting to unique positive integer IDs.
 */
function _paymentafterprofiles_page_ids(): array {
  return array_values(array_unique(array_filter(array_map('intval',
    (array) Civi::settings()->get('paymentafterprofiles_page_ids')
  ), function ($id) {
    return $id > 0;
  })));
}

/**
 * Implements hook_civicrm_buildForm().
 * Adds the Include Profiles checkbox and loads this page's saved choice.
 */
function paymentafterprofiles_civicrm_buildForm($formName, &$form): void {
  if ($formName !== 'CRM_Contribute_Form_ContributionPage_Custom') {
    return;
  }

  $form->add('checkbox', 'paymentafterprofiles_enabled', E::ts('Show both profiles before payment details'));
  $form->setDefaults([
    'paymentafterprofiles_enabled' => in_array((int) $form->getVar('_id'), _paymentafterprofiles_page_ids(), TRUE),
  ]);
  CRM_Core_Region::instance('contribute-form-contributionpage-custom-post')->add([
    'template' => E::path('templates/CRM/Paymentafterprofiles/IncludeProfiles.tpl'),
  ]);
}

/**
 * Implements hook_civicrm_postProcess().
 * Saves this page's checkbox choice while preserving other pages' selections.
 */
function paymentafterprofiles_civicrm_postProcess($formName, &$form): void {
  if ($formName !== 'CRM_Contribute_Form_ContributionPage_Custom') {
    return;
  }

  $pageId = (int) $form->getVar('_id');
  if ($pageId <= 0) {
    return;
  }

  $values = $form->exportValues();
  // Read the current setting when saving, preserving the other pages' choices.
  $pageIds = array_values(array_diff(_paymentafterprofiles_page_ids(), [$pageId]));
  if (!empty($values['paymentafterprofiles_enabled'])) {
    $pageIds[] = $pageId;
  }
  sort($pageIds, SORT_NUMERIC);
  Civi::settings()->set('paymentafterprofiles_page_ids', $pageIds);
}

/**
 * Implements hook_civicrm_config().
 * Initializes the extension's PHP include path through Civix.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function paymentafterprofiles_civicrm_config(&$config): void {
  _paymentafterprofiles_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 * Runs the Civix installation setup.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function paymentafterprofiles_civicrm_install(): void {
  _paymentafterprofiles_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 * Runs the Civix setup when the extension is enabled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function paymentafterprofiles_civicrm_enable(): void {
  _paymentafterprofiles_civix_civicrm_enable();
}
