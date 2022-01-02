<?php

require_once 'updatemailingcontent.civix.php';
// phpcs:disable
use CRM_Updatemailingcontent_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function updatemailingcontent_civicrm_config(&$config) {
  _updatemailingcontent_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function updatemailingcontent_civicrm_xmlMenu(&$files) {
  _updatemailingcontent_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function updatemailingcontent_civicrm_install() {
  _updatemailingcontent_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function updatemailingcontent_civicrm_postInstall() {
  _updatemailingcontent_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function updatemailingcontent_civicrm_uninstall() {
  _updatemailingcontent_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function updatemailingcontent_civicrm_enable() {
  _updatemailingcontent_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function updatemailingcontent_civicrm_disable() {
  _updatemailingcontent_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function updatemailingcontent_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _updatemailingcontent_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function updatemailingcontent_civicrm_managed(&$entities) {
  _updatemailingcontent_civix_civicrm_managed($entities);
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
function updatemailingcontent_civicrm_caseTypes(&$caseTypes) {
  _updatemailingcontent_civix_civicrm_caseTypes($caseTypes);
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
function updatemailingcontent_civicrm_angularModules(&$angularModules) {
  _updatemailingcontent_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function updatemailingcontent_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _updatemailingcontent_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function updatemailingcontent_civicrm_entityTypes(&$entityTypes) {
  _updatemailingcontent_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_themes().
 */
function updatemailingcontent_civicrm_themes(&$themes) {
  _updatemailingcontent_civix_civicrm_themes($themes);
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postProcess
 */
function updatemailingcontent_civicrm_postProcess($formName, &$form) {
  if ('CRM_Admin_Form_MessageTemplates' == $formName && ($form->getVar('_action') & CRM_Core_Action::UPDATE)) {
    if (!_updatemailingcontent_civicrm_checkMessageTemplateInUse($form->getVar('_id'))) {
      return;
    }
    if (!empty($form->_submitValues['update_scheduled_mailings'])) {
      $mailingIds = _updatemailingcontent_civicrm_checkMessageTemplateInUse($form->getVar('_id'), TRUE);
      foreach ($mailingIds as $mailingId) {
        civicrm_api3('Mailing', 'create', [
          'id' => $mailingId['id'],
          'body_html' => $form->_submitValues['msg_html'],
          'body_text' => $form->_submitValues['msg_text'],
        ]);
      }
    }

  }
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function updatemailingcontent_civicrm_buildForm($formName, &$form) {
  if ('CRM_Admin_Form_MessageTemplates' == $formName && ($form->getVar('_action') & CRM_Core_Action::UPDATE)) {
    if (!_updatemailingcontent_civicrm_checkMessageTemplateInUse($form->getVar('_id'))) {
      return;
    }
    $form->addYesNo('update_scheduled_mailings', ts('Update all recurring scheduled mailings?'));
    $form->setdefaults(['update_scheduled_mailings' => FALSE]);
    CRM_Core_Region::instance('page-body')->add([
      'template' => 'CRM/UpdateMailingContent/Field.tpl',
    ]);
  }

}

function _updatemailingcontent_civicrm_checkMessageTemplateInUse($messageTemplateId, $all = FALSE) {
  $select  = 'COUNT(*)';
  if ($all === TRUE) {
    $select = 'civicrm_mailing.id';
  }
  $query = "SELECT {$select}
    FROM civicrm_mailing
      INNER JOIN civicrm_mailing_job
      	ON civicrm_mailing_job.mailing_id = civicrm_mailing.id
        	AND civicrm_mailing_job.is_test = 0
            AND civicrm_mailing_job.parent_id IS NULL
      INNER JOIN civicrm_mailing_recurrence cmr
        ON cmr.mailing_id = civicrm_mailing.id
    WHERE civicrm_mailing.sms_provider_id IS NULL
      AND civicrm_mailing_job.status IN ('Scheduled')
      AND (civicrm_mailing.is_archived IS NULL OR civicrm_mailing.is_archived = 0)
      AND civicrm_mailing.msg_template_id = %1;
  ";
  if ($all === TRUE) {
    return CRM_Core_DAO::executeQuery($query, [1 => [$messageTemplateId, 'Integer']])->fetchAll();
  }
  return CRM_Core_DAO::singleValueQuery($query, [1 => [$messageTemplateId, 'Integer']]);
}
