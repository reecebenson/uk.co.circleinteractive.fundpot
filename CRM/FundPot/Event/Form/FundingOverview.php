<?php
/**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 */
use CRM_FundPot_ExtensionUtil as E;
use CRM_FundPot_Utils as U;

class CRM_FundPot_Event_Form_FundingOverview extends CRM_Event_Form_ManageEvent {

  public function preProcess() {
    parent::preProcess();

    // Get Funding Data
    try {
      $funders = civicrm_api3("FpEvent", "get", [
        "event_ref" => $this->_id,
        "options" => ["grouped" => 1],
      ])["values"];
      $this->assign("funders", $funders);
    }
    catch (CiviCRM_API3_Exception $e) {
      throw new Exception("Unable to process the funding for this event.");
    }

    // Get Funder Names
    try {
      $this->assign("funder_names", U::getFunders($this->_id));
    }
    catch (CiviCRM_API3_Exception $e) {
      throw new Exception("Unable to process the funder names for this event.");
    }

    // Template variables
    $this->assign("type", "event");
    $this->assign("collapsed", FALSE);
  }

  public function run() {
    return parent::run();
  }
}