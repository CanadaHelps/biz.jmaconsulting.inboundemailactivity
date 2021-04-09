<?php

require_once 'inboundemailactivity.civix.php';
// phpcs:disable
use CRM_Inboundemailactivity_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function inboundemailactivity_civicrm_config(&$config) {
  _inboundemailactivity_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function inboundemailactivity_civicrm_xmlMenu(&$files) {
  _inboundemailactivity_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function inboundemailactivity_civicrm_install() {
  _inboundemailactivity_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function inboundemailactivity_civicrm_postInstall() {
  _inboundemailactivity_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function inboundemailactivity_civicrm_uninstall() {
  _inboundemailactivity_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function inboundemailactivity_civicrm_enable() {
  _inboundemailactivity_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function inboundemailactivity_civicrm_disable() {
  _inboundemailactivity_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function inboundemailactivity_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _inboundemailactivity_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function inboundemailactivity_civicrm_managed(&$entities) {
  _inboundemailactivity_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function inboundemailactivity_civicrm_caseTypes(&$caseTypes) {
  _inboundemailactivity_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function inboundemailactivity_civicrm_angularModules(&$angularModules) {
  _inboundemailactivity_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function inboundemailactivity_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _inboundemailactivity_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function inboundemailactivity_civicrm_entityTypes(&$entityTypes) {
  _inboundemailactivity_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function inboundemailactivity_civicrm_themes(&$themes) {
  _inboundemailactivity_civix_civicrm_themes($themes);
}

function inboundemailactivity_civicrm_pre($op, $objectName, $id, &$params) {
  if ($objectName === 'Activity' && $op === 'create' && $params['activity_type_id'] === CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Inbound Email')) {
    // Take the first target contact originally set in deprecated_activity_buildmailparams which would have been the to line.
    $params['target_contact_id'] = [$params['source_contact_id']];
    // fetch the inbound email-to-activity email address contact, if no contact found then create one and use it for 'Added by' or source_contact_id 
    // if not found ignore and let the source contact be the From contact
    $emailToActivityContact = civicrm_api3('MailSettings', 'get', [
      'sequential' => 1,
      'is_default' => 0,
      'api.Email.get' => ['email' => "\$value.username", 'is_primary' => 1, 'sequential' => 1],
    ]);
    // if inbound email-to-activity email address found
    if (!empty($emailToActivityContact['values'])) {
      // if contact associated with inbound email-to-activity email address found
      if (!empty($emailToActivityContact['values'][0]['api.Email.get']['values'])) {
        $params['source_contact_id'] = $emailToActivityContact['values'][0]['api.Email.get']['values'][0]['contact_id'];
      }
      // if contact associated with inbound email-to-activity email address not found then create one
      else {
        $params['source_contact_id'] = civicrm_api3('Contact', 'create', [
          'contact_type' => "Individual",
          'email' => $emailToActivityContact['values']['username'],
        ])['id'];
      }
    }
    unset($params['assignee_contact_id']);
  }
}
