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
 * Used to get the financial details of a Funding Pot
 *
 * @param array $params
 * @return array
 */
function civicrm_api3_fp_summary_get($params) {
  // Parameters
  $caseId = $params["case_ref"] ?? "";
  $eventId = $params["event_ref"] ?? "";

  // Check if our Case ID exists
  if (!empty($caseId) && !U::caseExists($caseId)) {
    throw new API_Exception("Case does not exist [$caseId].");
  }

  // Check if our Event ID exists
  if (!empty($eventId) && !U::eventExists($eventId)) {
    throw new API_Exception("Event does not exist.");
  }

  // Check if we're trying to handle both Event & Case
  if (!empty($eventId) && !empty($caseId)) {
    throw new API_Exception("Please only specify one reference, not both Case & Event references.");
  }

  // Get the case / event summary
  if (!empty($caseId)) {
    $summary = U::getCaseSummary($caseId);
  }
  elseif (!empty($eventId)) {
    $summary = U::getEventSummary($eventId);
  }
  else {
    throw new API_Exception("Please input atleast one reference.");
  }

  return civicrm_api3_create_success([$summary], $params, "FpSummary");
}

/**
 * Adjust Metadata for get action.
 *
 * The metadata is used for setting defaults, documentation & validation.
 *
 * @param array $params
 *   Array of parameters determined by getfields.
 */
function _civicrm_api3_fp_summary_get_spec(&$params) {
  return ($params += [
    "case_ref" => [
      "title" => "Case ID",
      "description" => "The Case Reference",
    ],
    "event_ref" => [
      "title" => "Event ID",
      "description" => "The Event Reference",
    ],
  ]);
}
