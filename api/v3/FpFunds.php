<?php
/**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 */
use CRM_FundPot_Utils as U;

/**
 * Get a FpFunds API Request
 * Used to get the specific cases contributions
 *
 * @param array $params
 * @return array
 */
function civicrm_api3_fp_funds_get($params) {
  // Parameters
  $caseId = $params["case_ref"];

  // Check if our Event ID exists
  if (!U::caseExists($caseId)) {
    throw new API_Exception("Case does not exist.");
  }

  // Get this events contributions
  $contributions = isset($params["options"]["grouped"]) ?
    U::getCasesContributionsGroupedByEvent($caseId) :
    U::getCasesContributions($caseId);
  return civicrm_api3_create_success($contributions, $params, "FpFunds");
}

/**
 * Adjust Metadata for get action.
 *
 * The metadata is used for setting defaults, documentation & validation.
 *
 * @param array $params
 *   Array of parameters determined by getfields.
 */
function _civicrm_api3_fp_funds_get_spec(&$params) {
  return ($params += [
    "case_ref" => [
      "title" => "Case ID",
      "description" => "The Case Reference",
      "api.required" => 1,
    ],
  ]);
}
