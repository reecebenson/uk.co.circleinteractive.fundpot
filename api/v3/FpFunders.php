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
 * Get a FpEvent API Request
 * Used to get the contributions sent to an event, separated by their Case
 *
 * @param array $params
 * @return array
 */
function civicrm_api3_fp_funders_get($params) {
  // Parameters
  $eventId = $params["event_ref"];

  // Check if our Event ID exists
  if (!U::eventExists($eventId)) {
    throw new API_Exception("Event does not exist.");
  }

  // Get this events contributions
  $contributions = U::getEventContributionsGroupedByCase($eventId);
  return civicrm_api3_create_success($contributions, $params, "FpFunders");
}

/**
 * Adjust Metadata for get action.
 *
 * The metadata is used for setting defaults, documentation & validation.
 *
 * @param array $params
 *   Array of parameters determined by getfields.
 */
function _civicrm_api3_fp_funders_get_spec(&$params) {
  return ($params += [
    "event_ref" => [
      "title" => "Event ID",
      "description" => "The Event Reference",
      "api.required" => 1,
    ],
  ]);
}
