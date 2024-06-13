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
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function updatemailingcontent_civicrm_install() {
  _updatemailingcontent_civix_civicrm_install();
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
