<?php
/**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 */

return [
  // Option Groups
  // ---------------------------------
  [
    "module" => "uk.co.circleinteractive.fundpot",
    "name" => "fundpot_empty_optiongroup",
    "entity" => "OptionGroup",
    "update" => "never",
    "params" => [
      "version" => 3,
      "name" => "fundpot_eventcontrib",
      "title" => "Event / Contribution Options",
      "data_type" => "String",
      "is_reserved" => 1,
      "is_active" => 1,
      "is_locked" => 1,
      "options" => ["match" => "name"],
    ],
  ],

  // Custom Groups
  // ---------------------------------
  // Funding Resources
  [
    "module" => "uk.co.circleinteractive.fundpot",
    "name" => "fundpot_funding_resources",
    "entity" => "CustomGroup",
    "update" => "never",
    "params" => [
      "version" => 3,
      "name" => "FP_Funding_Resources",
      "title" => "Funding Resources",
      "extends" => "Contribution",
      "style" => "Inline",
      "collapse_adv_display" => 0,
      "is_active" => 1,
      "is_reserved" => 1,
      "table_name" => "civicrm_fundpot_funding_resources",
      "created_date" => date("Y-m-d H:i:s"),
      "options" => ["match" => "name"],
    ],
  ],

  // Funding Resources Information
  [
    "module" => "uk.co.circleinteractive.fundpot",
    "name" => "fundpot_funding_resources_info",
    "entity" => "CustomGroup",
    "update" => "never",
    "params" => [
      "version" => 3,
      "name" => "FP_Funding_Resources_Information",
      "title" => "Funding Resources Information",
      "extends" => "Case",
      "style" => "Inline",
      "collapse_adv_display" => 0,
      "is_active" => 1,
      "is_reserved" => 1,
      "table_name" => "civicrm_fundpot_funding_resources_info",
      "created_date" => date("Y-m-d H:i:s"),
      "options" => ["match" => "name"],
    ],
  ],

  // Event Cost
  [
    "module" => "uk.co.circleinteractive.fundpot",
    "name" => "civicrm_fundpot_event_cost",
    "entity" => "CustomGroup",
    "update" => "never",
    "params" => [
      "version" => 3,
      "name" => "FP_Event_Cost",
      "title" => "Event Cost",
      "extends" => "Event",
      "style" => "Inline",
      "collapse_adv_display" => 0,
      "is_active" => 1,
      "is_reserved" => 1,
      "table_name" => "civicrm_fundpot_event_cost",
      "created_date" => date("Y-m-d H:i:s"),
      "options" => ["match" => "name"],
    ],
  ],

  // Custom Fields
  // ---------------------------------
  // Event Reference
  [
    "module" => "uk.co.circleinteractive.fundpot",
    "name" => "fundpot_event_reference",
    "entity" => "CustomField",
    "update" => "never",
    "params" => [
      "version" => 3,
      "name" => "fundpot_cf_event_reference",
      "label" => "Event Reference",
      "data_type" => "String",
      "html_type" => "Select",
      "is_required" => 0,
      "is_active" => 1,
      "text_length" => 255,
      "note_columns" => 60,
      "note_rows" => 4,
      "column_name" => "fundpot_cf_event_reference",
      "custom_group_id" => "FP_Funding_Resources",
      "option_group_id" => "fundpot_eventcontrib",
      "options" => ["match" => "name"],
    ],
  ],

  // Contribution Source
  [
    "module" => "uk.co.circleinteractive.fundpot",
    "name" => "fundpot_contrib_source",
    "entity" => "CustomField",
    "update" => "never",
    "params" => [
      "version" => 3,
      "name" => "fundpot_cf_contrib_source",
      "label" => "Contribution Source",
      "data_type" => "String",
      "html_type" => "Select",
      "is_required" => 0,
      "is_active" => 1,
      "text_length" => 255,
      "note_columns" => 60,
      "note_rows" => 4,
      "column_name" => "fundpot_cf_contrib_source",
      "custom_group_id" => "FP_Funding_Resources",
      "option_group_id" => "fundpot_eventcontrib",
      "options" => ["match" => "name"],
    ],
  ],

  // Funding Amount
  [
    "module" => "uk.co.circleinteractive.fundpot",
    "name" => "fundpot_fund_amount",
    "entity" => "CustomField",
    "update" => "never",
    "params" => [
      "version" => 3,
      "name" => "fundpot_cf_fund_amount",
      "label" => "Funding Amount",
      "data_type" => "Money",
      "html_type" => "Text",
      "is_required" => 0,
      "in_selector" => 0,
      "is_searchable" => 1,
      "is_active" => 1,
      "is_view" => 0,
      "text_length" => 255,
      "note_columns" => 60,
      "note_rows" => 4,
      "column_name" => "fundpot_cf_fund_amount",
      "custom_group_id" => "FP_Funding_Resources_Information",
      "options" => ["match" => "name"],
    ],
  ],

  // Event Cost
  [
    "module" => "uk.co.circleinteractive.fundpot",
    "name" => "fundpot_event_cost",
    "entity" => "CustomField",
    "update" => "never",
    "params" => [
      "version" => 3,
      "name" => "fundpot_cf_event_cost",
      "label" => "Funding Amount",
      "data_type" => "Money",
      "html_type" => "Text",
      "is_required" => 0,
      "in_selector" => 0,
      "is_searchable" => 1,
      "is_active" => 1,
      "is_view" => 0,
      "text_length" => 255,
      "note_columns" => 60,
      "note_rows" => 4,
      "column_name" => "fundpot_cf_event_cost",
      "custom_group_id" => "FP_Event_Cost",
      "options" => ["match" => "name"],
    ],
  ],

  // Financial Types
  // ---------------------------------
  // FundPot Fundraiser
  [
    "module" => "uk.co.circleinteractive.fundpot",
    "name" => "fundpot_ft_fundraiser",
    "entity" => "FinancialType",
    "update" => "never",
    "params" => [
      "version" => 3,
      "name" => "FundPot Fundraiser",
      "is_active" => 1,
      "is_reserved" => 1,
      "options" => ["match" => "name"],
      "description" => "A financial type used for the Funding Pot extension.",
    ],
  ],
];
