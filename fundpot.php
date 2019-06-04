<?php
/**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 */

require_once "fundpot.civix.php";
use CRM_FundPot_ExtensionUtil as E;
use CRM_FundPot_Utils as U;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function fundpot_civicrm_config(&$config) {
  _fundpot_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function fundpot_civicrm_xmlMenu(&$files) {
  _fundpot_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function fundpot_civicrm_install() {
  _fundpot_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function fundpot_civicrm_postInstall() {
  _fundpot_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function fundpot_civicrm_uninstall() {
  _fundpot_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function fundpot_civicrm_enable() {
  _fundpot_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function fundpot_civicrm_disable() {
  _fundpot_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function fundpot_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _fundpot_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function fundpot_civicrm_managed(&$entities) {
  _fundpot_civix_civicrm_managed($entities);
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
function fundpot_civicrm_caseTypes(&$caseTypes) {
  _fundpot_civix_civicrm_caseTypes($caseTypes);
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
function fundpot_civicrm_angularModules(&$angularModules) {
  _fundpot_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function fundpot_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _fundpot_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function fundpot_civicrm_entityTypes(&$entityTypes) {
  _fundpot_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_tabset().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_tabset/
 */
function fundpot_civicrm_tabset($tabsetName, &$tabs, $context) {
  // Create a new tab for Funding Resource
  $tab = [];

  // Check if the tabset is "Event/Manage"
  if ($tabsetName == "civicrm/event/manage") {
    if (!empty($context)) {
      $eventID = $context["event_id"];
      $url = CRM_Utils_System::url("civicrm/event/manage/funding",
        "reset=1&snippet=5&force=1&id={$eventID}&action=update&component=event");

      $tab["funding"] = [
        "title" => ts("Funding"),
        "link" => $url,
        "valid" => 1,
        "active" => 1,
        "current" => false,
      ];
    }
    else {
      $tab[] = [
        "title" => ts("Funding Overview"),
        "url" => "civicrm/event/manage/funding",
      ];
    }

    // Add tab
    $tabs = array_merge($tabs, $tab);
  }
}

/**
 * Implements hook_civicrm_fieldOptions().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_fieldOptions/
 */
function fundpot_civicrm_fieldOptions($entity, $field, &$options, $params) {
  if ($entity == "Contribution") {
    switch ($field) {
      // Funding Resources -> Event Reference
      case "custom_".U::getCustomFieldId("event_ref"):
      {
        $options = [];

        try {
          $options = array_column(
            civicrm_api3("Event", "get",
              [
                "options" => ["limit" => 0],
                "is_active" => TRUE,
                "return" => "title",
              ]
            )["values"],
            "title",
            "id"
          );
        }
        catch (CiviCRM_API3_Exception $e) {
          CRM_Core_Error::debug("FundPot: Unable to assign field options for Event Reference");
        }
      }
      break;

      // Funding Resources -> Contribution Source
      case "custom_".U::getCustomFieldId("case_ref"):
      {
        $options = [];

        try {
          $options = array_column(
            civicrm_api3("Case", "get", [
              "sequential" => 1,
              "options" => ["limit" => 0],
              "case_type_id" => "FundPot",
              "status_id" => ["NOT IN" => ["Closed"]],
              "contact_id" => ["IS NOT NULL" => TRUE],
              "custom_".U::getCustomFieldId("funding_amount") => ["IS NOT NULL" => TRUE],
              "return" => ["subject", "custom_".U::getCustomFieldId("funding_amount")],
            ])["values"],
            "subject",
            "id"
          );
        }
        catch (CiviCRM_API3_Exception $e) {
          CRM_Core_Error::debug("FundPot: Unable to assign field options for Contribution Source");
        }
      }
      break;
    }
  }
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm/
 */
function fundpot_civicrm_buildForm($formName, &$form) {
  if ("CRM_Case_Form_CaseView" === $formName) {
    if (isset($form->_caseType) && $form->_caseType == "FundPot") {
      // Get Funding Data
      (new CRM_FundPot_Case_Form_OutgoingFunds)->run($form);

      // Add Funds Summary
      (new CRM_FundPot_Case_Form_FundSummary)->run($form);
    }
  }
  else if("CRM_Custom_Form_CustomDataByType" === $formName) {
    $fundingResources = FALSE;
    foreach ($form->_groupTree as $key => $value) {
      if ($value["name"] == "FP_Funding_Resources") {
        $fundingResources = TRUE;
        break;
      }
    }

    if ($fundingResources) {
      // Add Funding Resources Elements
      (new CRM_FundPot_Contribution_Form_FundSummary)->run($form);
    }
  }
}