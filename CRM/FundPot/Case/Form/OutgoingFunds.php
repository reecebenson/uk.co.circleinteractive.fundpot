<?php
/**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 */

class CRM_FundPot_Case_Form_OutgoingFunds {
    public function run(&$form) {
        try {
          $funders = civicrm_api3("FpFunds", "get", [
            "case_ref" => $form->_caseID,
            "options" => ["grouped" => 1],
          ])["values"];

          $form->assign("type", "case");
          $form->assign("collapsed", TRUE);
          $form->assign("funders", $funders);
        }
        catch (CiviCRM_API3_Exception $e) {
          // Unable to find funds for this Case, assign funds as zero
          $form->assign("type", "case");
          $form->assign("collapsed", TRUE);
          $form->assign("funders", []);

          // CRM_Core_Error::debug_var("FundingPot", "Unable to process the funding for this case [" . $form->_caseID . "]");
        }

        // Add Outgoing Funds table
        CRM_Core_Region::instance("page-body")->add([
          "template" => "CRM/FundPot/Case/OutgoingFunds.tpl",
        ]);
    }
}