<?php
/**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 */

class CRM_FundPot_Case_Form_FundSummary {
    public function run(&$form) {
        try {
          $summary = civicrm_api3("FpSummary", "get", [
            "case_ref" => $form->_caseID,
            "options" => ["grouped" => 1],
          ])["values"];

          if (count($summary) > 0) {
            $result = $summary[0];
            $form->assign("funds_allocated", $result["funds_allocated"]);
            $form->assign("total_funded", $result["total_funded"]);
            $form->assign("funds_available", $result["funds_available"]);
          }
        }
        catch (CiviCRM_API3_Exception $e) {
          // Unable to find funds for this Case, assign funds as zero
          $form->assign("funds_allocated", 0);
          $form->assign("total_funded", 0);
          $form->assign("funds_available", 0);

          // CRM_Core_Error::debug_var("FundingPot", "Unable to process the funding summary for this case [" . $form->_caseID . "]");
        }

        CRM_Core_Region::instance("page-body")->add([
          "template" => "CRM/FundPot/Case/FundSummary.tpl",
        ]);
    }
}