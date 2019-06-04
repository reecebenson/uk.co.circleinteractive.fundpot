<?php
/**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 */

class CRM_FundPot_Contribution_Form_FundSummary {
    public function run(&$form) {
        CRM_Core_Region::instance("page-body")->add([
          "template" => "CRM/FundPot/Contribution/FundSummary.tpl",
        ]);
    }
}