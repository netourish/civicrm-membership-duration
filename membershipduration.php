<?php

require_once 'membershipduration.civix.php';
use CRM_Membershipduration_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function membershipduration_civicrm_config(&$config) {
  _membershipduration_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function membershipduration_civicrm_xmlMenu(&$files) {
  _membershipduration_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function membershipduration_civicrm_install() {
  _membershipduration_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function membershipduration_civicrm_postInstall() {
  _membershipduration_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function membershipduration_civicrm_uninstall() {
  _membershipduration_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function membershipduration_civicrm_enable() {
  _membershipduration_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function membershipduration_civicrm_disable() {
  _membershipduration_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function membershipduration_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _membershipduration_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function membershipduration_civicrm_managed(&$entities) {
  _membershipduration_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function membershipduration_civicrm_caseTypes(&$caseTypes) {
  _membershipduration_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function membershipduration_civicrm_angularModules(&$angularModules) {
  _membershipduration_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function membershipduration_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _membershipduration_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function membershipduration_civicrm_entityTypes(&$entityTypes) {
  _membershipduration_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function membershipduration_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function membershipduration_civicrm_navigationMenu(&$menu) {
  _membershipduration_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _membershipduration_civix_navigationMenu($menu);
} // */

function membershipduration_civicrm_postProcess($formName, &$form) {
  /*echo $formName;
  exit;*/
}

/**
 * Implementation of hook_civicrm_post
 */
function membershipduration_civicrm_post($op, $objectName, $objectId, &$objectRef) {

  /*echo $objectName;
  exit;*/

  if(($objectName != 'Membership' && $objectName != 'MembershipPayment') || $op=='delete') {
      return;
  }

  $dataToUpdate = array();

  if ($objectName == 'Membership') {
    $dataToUpdate['membership_id'] = $objectRef->id;
    $dataToUpdate['contact_id'] = $objectRef->contact_id;
    $dataToUpdate['start_date'] = $objectRef->start_date;
    $dataToUpdate['end_date'] = $objectRef->end_date;
    CRM_Membershipduration_BAO_MembershipDuration::insertData($dataToUpdate);
  }

  if ($objectName == 'MembershipPayment') {
    $dataToUpdate['membership_id'] = $objectRef->membership_id;
    $dataToUpdate['contribution_id'] = $objectRef->contribution_id;
    CRM_Membershipduration_BAO_MembershipDuration::updateContribution($dataToUpdate);
  }

}

/**
  * Implements hook_civicrm_links().
  * Implementing this to add a custom link to view membership druation for each membership of a contact.
  */
function membershipduration_civicrm_links($op, $objectName, $objectId, &$links, &$mask, &$values) {
  if($op=="membership.tab.row" || $op=="membership.selector.row") {
      $links[] = array(
         'name' => ts('Membership Duration'),
         'url' => 'civicrm/contact/view/membership/duration',
         'qs' => 'id=%%id%%',
         'title' => 'Membership Duration',
       );
  }
}

function membershipduration_civicrm_tabset($tabsetName, &$tabs, $context) {
  if ($tabsetName == 'civicrm/contact/view') {

    $contactId = $context['contact_id'];

    $url = CRM_Utils_System::url( 'civicrm/contact/view/membership/duration', "reset=1&id=$contactId" );

    $tabs[] = array( 'id'    => 'membership_duration_tab',
      'url'   => $url,
      'title' => 'Membership Duration',
      'weight' => 300,
    );
  }
}
