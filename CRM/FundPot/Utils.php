<?php
/**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 */

class CRM_FundPot_Utils {
  /**
   * Definitions
   */
  public static $CF_EVENT_REFERENCE;
  public static $CF_CASE_REFERENCE;
  public static $CF_FUNDING_AMOUNT;
  public static $CF_EVENT_COST;

  /**
   * Get Custom Field IDs of our managed entities
   *
   * @param $type string
   */
  public static function getCustomFieldId($type) {
    try {
      switch ($type) {
        case "event_ref": {
          if (isset(self::$CF_EVENT_REFERENCE)) {
            return self::$CF_EVENT_REFERENCE;
          }
          else {
            return (self::$CF_EVENT_REFERENCE = civicrm_api3("CustomField", "getvalue", [
              "return" => "id",
              "custom_group_id" => "FP_Funding_Resources",
              "name" => "fundpot_cf_event_reference",
            ]));
          }
        }
        break;

        case "case_ref": {
          if (isset(self::$CF_CASE_REFERENCE)) {
            return self::$CF_CASE_REFERENCE;
          }
          else {
            return (self::$CF_CASE_REFERENCE = civicrm_api3("CustomField", "getvalue", [
              "return" => "id",
              "custom_group_id" => "FP_Funding_Resources",
              "name" => "fundpot_cf_contrib_source",
            ]));
          }
        }
        break;

        case "funding_amount": {
          if (isset(self::$CF_FUNDING_AMOUNT)) {
            return self::$CF_FUNDING_AMOUNT;
          }
          else {
            return (self::$CF_FUNDING_AMOUNT = civicrm_api3("CustomField", "getvalue", [
              "return" => "id",
              "custom_group_id" => "FP_Funding_Resources_Information",
              "name" => "fundpot_cf_fund_amount",
            ]));
          }
        }
        break;

        case "event_cost": {
          if (isset(self::$CF_EVENT_COST)) {
            return self::$CF_EVENT_COST;
          }
          else {
            return (self::$CF_EVENT_COST = civicrm_api3("CustomField", "getvalue", [
              "return" => "id",
              "custom_group_id" => "FP_Event_Cost",
              "name" => "fundpot_cf_event_cost",
            ]));
          }
        }
        break;

        case "case_id": {
          if (isset(self::$CT_FUNDPOT)) {
            return self::$CT_FUNDPOT;
          }
          else {
            return (self::$CT_FUNDPOT = civicrm_api3("Case", "getvalue", [
              "return" => "id",
              "name" => "FundPot",
            ]));
          }
        }
        break;

        default: {
          return;
        }
        break;
      }
    }
    catch (Exception $e) {
      // ignore exception
      CRM_Core_Error::debug_var("Error", $e->getMessage());
    }
    return;
  }

  /**
   * Get the funders of a specific Event
   * - Used for Event View
   *
   * @param int $eventId | The Event ID that we want to get our funders list from
   * @return void
   */
  public static function getFunders($eventId) : array {
    try {
      $funders = civicrm_api3("Contribution", "get", [
        "sequential" => 1,
        "contact_id" => ["IS NOT NULL" => 1],
        "custom_".self::getCustomFieldId("event_ref") => ["LIKE" => $eventId],
        "return" => ["contact_id"],
      ])["values"];

      // Add names to each contribution
      array_walk($funders, function(&$funder) {
        $funder = civicrm_api3("Contact", "getvalue", [
          "return" => "display_name",
          "id" => $funder["contact_id"],
        ]);
      });

      return array_unique($funders);
    }
    catch (CiviCRM_API3_Exception $e) {
      return [];
    }
  }

  /**
   * Get the financial summary of a Case
   *
   * @param int $caseId
   * @return array
   */
  public static function getCaseSummary($caseId) {
    $fundsAllocated = 0.00;
    $totalFunded = 0.00;
    $fundsAvailable = 0.00;

    // Get the allocated funds
    try {
      $fundsAllocated = civicrm_api3("Case", "getvalue", [
        "return" => "custom_".self::getCustomFieldId("funding_amount"),
        "case_type_id" => "FundPot",
        "id" => $caseId,
      ]);
    }
    catch (CiviCRM_API3_Exception $e) {
      CRM_Core_Error::debug_var("FundPot:getEventSummary", "Unable to get case summary [{$caseId}].");
    }

    // Work out the total spent on a case
    $contributions = self::getCasesContributionsGroupedByEvent($caseId);
    array_walk($contributions, function ($contribution) use (&$totalFunded) {
      $totalFunded += $contribution["total"];
    });

    // Work out the funds available
    $fundsAvailable = ($fundsAllocated - $totalFunded);

    return [
      "funds_allocated" => $fundsAllocated,
      "total_funded" => $totalFunded,
      "funds_available" => $fundsAvailable,
    ];
  }

  /**
   * Get the financial summary of an Event
   *
   * @param int $eventId
   * @return array
   */
  public static function getEventSummary($eventId) {
    $totalCost = 0.00;
    $totalFunded = 0.00;
    $outstandingBalance = 0.00;

    // Get the allocated funds
    try {
      $totalCost = civicrm_api3("Event", "getvalue", [
        "return" => "custom_".self::getCustomFieldId("event_cost"),
        "id" => $eventId,
      ]);

      // Work out the total spent on a case
      $contributions = self::getEventContributionsGroupedByCase($eventId);
      array_walk($contributions, function ($contribution) use (&$totalFunded) {
        $totalFunded += $contribution["total"];
      });

      // Work out the funds available
      $outstandingBalance = ($totalCost - $totalFunded);
    }
    catch (CiviCRM_API3_Exception $e) {
      CRM_Core_Error::debug_var("FundPot:getEventSummary", "Unable to get event summary [{$eventId}].");

      // Defaults for an event with no total cost
      $totalCost = "N/A";
    }

    return [
      "total_cost" => $totalCost,
      "total_funded" => $totalFunded,
      "outstanding_balance" => $outstandingBalance,
    ];
  }

  /**
   * Check if an event exists
   *
   * @param int $eventId
   * @return boolean
   */
  public static function eventExists($eventId) {
    // Check if the Event ID is null
    if ($eventId === NULL) {
      return FALSE;
    }

    // Grab the Events
    $params = [
      "id" => $eventId,
      "options" => ["limit" => 1],
    ];

    $events = _civicrm_api3_basic_get(_civicrm_api3_get_BAO("civicrm_api3_event_get"), $params, FALSE, "Event", $sql = NULL, TRUE);
    return !empty($events);
  }

  /**
   * Check if a case exists
   *
   * @param int $caseId
   * @return boolean
   */
  public static function caseExists($caseId) {
    // Check if the Event ID is null
    if ($caseId === NULL) {
      return FALSE;
    }

    // Grab the Cases
    $params = [
      "id" => $caseId,
      "case_type_id" => self::getCustomFieldId("case_id"),
      "options" => ["limit" => 1],
    ];

    $cases = _civicrm_api3_basic_get(_civicrm_api3_get_BAO("civicrm_api3_case_get"), $params, FALSE, "Case", $sql = NULL, TRUE);
    CRM_Core_Error::debug_var("cases", $cases);
    return !empty($cases);
  }

  /**
   * Get the subject of a Case
   *
   * @param int $caseId
   * @return string
   */
  public static function getCaseData($caseId) {
    // Check if the Case ID is null
    if ($caseId === NULL) {
      return [];
    }

    // Grab the Case Subject
    try {
      return civicrm_api3("Case", "get", [
        "return" => ["subject", "contact_id"],
        "case_type_id" => "FundPot",
        "id" => $caseId,
        "options" => ["limit" => 1],
      ])["values"][$caseId];
    }
    catch (CiviCRM_API3_Exception $e) {
      throw new Exception("Case does not exist.");
    }
  }

  /**
   * Get the name of an Event
   *
   * @param int $caseId
   * @return string
   */
  public static function getEventData($eventId) {
    // Check if the Event ID is null
    if ($eventId === NULL) {
      return [];
    }

    // Grab the Event Title
    try {
      return civicrm_api3("Event", "get", [
        "return" => ["title"],
        "id" => $eventId,
        "options" => ["limit" => 1],
      ])["values"][$eventId];
    }
    catch (CiviCRM_API3_Exception $e) {
      throw new Exception("Event does not exist.");
    }
  }

  /**
   * Get a specific events contributions
   *
   * @param int $eventId
   * @return array
   */
  public static function getEventContributions($eventId) {
    try {
      $contributions = civicrm_api3("Contribution", "get", [
        "sequential" => 1,
        "contact_id" => ["IS NOT NULL" => 1],
        "custom_".self::getCustomFieldId("event_ref") => $eventId,
        "return" => ["contact_id", "currency", "total_amount", "receive_date", "custom_".self::getCustomFieldId("case_ref")],
      ])["values"];

      // Add names to each contribution
      array_walk($contributions, function (&$contribution) {
        $contribution["contact_name"] = civicrm_api3("Contact", "getvalue", [
          "return" => "display_name",
          "id" => $contribution["contact_id"],
        ]);
      });

      return $contributions;
    }
    catch (CiviCRM_API3_Exception $e) {
      return [];
    }
  }

  /**
   * Get a specific events contributions, grouped by Case
   *
   * @param int $eventId
   * @return array
   */
  public static function getEventContributionsGroupedByCase($eventId) {
    try {
      $contributions = self::getEventContributions($eventId);

      // Group contributions
      $grouped = [];
      array_walk($contributions, function ($contribution) use (&$grouped) {
        $groupId = $contribution["custom_".self::getCustomFieldId("case_ref")];

        if (!is_array($grouped[$groupId]) && self::caseExists($groupId)) {
          $case = self::getCaseData($groupId);

          $grouped[$groupId] = [
            "ref_id" => $groupId,
            "contact_id" => array_pop(array_reverse($case["contact_id"])),
            "subject" => $case["subject"],
            "total" => 0.00,
            "contributions" => [],
          ];
        }
        else {
          unset($contribution);
        }

        if (is_numeric($contribution["total_amount"])) {
          $grouped[$groupId]["total"] += (float)$contribution["total_amount"];
        }

        $grouped[$groupId]["contributions"][] = $contribution;
      });

      return $grouped;
    }
    catch (CiviCRM_API3_Exception $e) {
      return [];
    }
  }

  /**
   * Get a specific cases contributions
   *
   * @param int $caseId
   * @return array
   */
  public static function getCasesContributions($caseId) {
    try {
      $contributions = civicrm_api3("Contribution", "get", [
        "sequential" => 1,
        "contact_id" => ["IS NOT NULL" => 1],
        "custom_".self::getCustomFieldId("case_ref") => $caseId,
        "return" => ["contact_id", "currency", "total_amount", "receive_date", "custom_".self::getCustomFieldId("event_ref")],
      ])["values"];

      // Add names to each contribution
      array_walk($contributions, function (&$contribution) {
        $contribution["contact_name"] = civicrm_api3("Contact", "getvalue", [
          "return" => "display_name",
          "id" => $contribution["contact_id"],
        ]);
      });

      return $contributions;
    }
    catch (CiviCRM_API3_Exception $e) {
      return [];
    }
  }

  /**
   * Get a specific cases contributions, grouped by Event
   *
   * @param int $caseId
   * @return array
   */
  public static function getCasesContributionsGroupedByEvent($caseId) {
    try {
      $contributions = self::getCasesContributions($caseId);

      // Group contributions
      $grouped = [];
      array_walk($contributions, function ($contribution) use (&$grouped) {
        $eventId = $contribution["custom_".self::getCustomFieldId("event_ref")];

        if (!is_array($grouped[$eventId]) && self::eventExists($eventId)) {
          $event = self::getEventData($eventId);
          $grouped[$eventId] = [
            "ref_id" => $eventId,
            "contact_id" => 1,
            "subject" => $event["title"],
            "total" => 0.00,
            "contributions" => [],
          ];
        }

        if (is_numeric($contribution["total_amount"])) {
          $grouped[$eventId]["total"] += (float)$contribution["total_amount"];
        }

        $grouped[$eventId]["contributions"][] = $contribution;
      });

      return $grouped;
    }
    catch (CiviCRM_API3_Exception $e) {
      return [];
    }
  }

  public static function getContributionsReductionsFromCase($caseId) {
    try {
      $contributions = civicrm_api3("Contribution", "get", [
        "sequential" => TRUE,
        "return" => ["total_amount"],
        "total_amount" => [">" => 0, "IS NOT NULL" => TRUE],
        "custom_".self::getCustomFieldId("case_ref") => $caseId,
      ])["values"];

      $totalSpent = 0.00;
      array_walk($contributions, function ($contribution) use (&$totalSpent) {
        $totalSpent += $contribution["total_amount"];
      });

      return $totalSpent;
    }
    catch (Exception $e) {
      return "N/A";
    }
  }
}
