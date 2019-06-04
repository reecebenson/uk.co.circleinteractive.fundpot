{**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 *}
{literal}
<style type="text/css">
  .fund-summary td:nth-child(2),
  .fund-summary td:nth-child(4) {
    vertical-align: middle;
    padding-top: 4px;
  }

  .fund-summary.center {
    text-align: center;
    border-collapse: collapse;
    border-bottom: 1px solid black;
    background-color: rgba(255, 255, 255, 0.15);
  }
</style>
{/literal}

<table>
  <tr class="fund-summary">
    <td class="label">&nbsp;</td>
    <td colspan="3" style="font-weight: bold; color: red;">
      <span id="fundpot-error-message-event">&nbsp;</span>
      <br/>
      <span id="fundpot-error-message-contrib">&nbsp;</span>
    </td>
  </tr>
  <tr class="fund-summary center">
    <td colspan="2" class="bold">Contribution Source</td>
    <td colspan="2" class="bold">Event Reference</td>
  </tr>
  <tr class="fund-summary first">
    <td class="label">
      <label for="fundpot-total-allocated">Total Allocated:</label>
    </td>
    <td>
      <span id="fundpot-total-allocated">[ Please select a Contribution Source ]</span>
    </td>
    <td class="label">
      <label for="fundpot-event-cost">Total Cost:</label>
    </td>
    <td>
      <span id="fundpot-event-cost">[ Please select an Event Reference ]</span>
    </td>
  </tr>
  <tr class="fund-summary">
    <td class="label">
      <label for="fundpot-total-funded">Total Funded:</label>
    </td>
    <td>
      <span id="fundpot-total-funded">[ Please select a Contribution Source ]</span>
    </td>
    <td class="label">
      <label for="fundpot-event-funded">Total Funded:</label>
    </td>
    <td>
      <span id="fundpot-event-funded">[ Please select an Event Reference ]</span>
    </td>
  </tr>
  <tr class="fund-summary">
    <td class="label">
      <label for="fundpot-funds-available">Funds Available:</label>
    </td>
    <td>
      <span id="fundpot-funds-available">[ Please select a Contribution Source ]</span>
    </td>
    <td class="label">
      <label for="fundpot-event-outstanding">Outstanding Balance:</label>
    </td>
    <td>
      <span id="fundpot-event-outstanding">[ Please select an Event Reference ]</span>
    </td>
  </tr>
  <tr class="fund-summary">
    <td class="label">
      <label for="fundpot-funds-after">Funds After This Contribution:</label>
    </td>
    <td>
      <span id="fundpot-funds-after">[ Please select a Contribution Source ]</span>
    </td>
    <td class="label">
      <label for="fundpot-event-outstanding-after">Outstanding After This Contribution:</label>
    </td>
    <td>
      <span id="fundpot-event-outstanding-after">[ Please select an Event Reference ]</span>
    </td>
  </tr>
</table>

{literal}
<script type="text/javascript">
(function($) {
  /**
   * Move case summary to custom group
   */
  let moveElements = (function() {
    let fundResources = $("#customData .custom-group.custom-group-FP_Funding_Resources");
    let table = $(".crm-accordion-body table", fundResources);

    if (!fundResources.length || !table.length) {
      return console.error("FundPot: No funding resources custom group.");
    }

    $(".fund-summary").appendTo(table);
  });

  /**
   * Set an error message
   */
  let setErrorMessage = (function(msg, who) {
    let errorMessage = $("#fundpot-error-message-" + who);

    if (!errorMessage.length) {
      return console.error("FundPot: Unable to find error message container.");
    }

    errorMessage.text(msg);
  });

  /**
   * Save button handling
   */
  let toggleSaveButtons = (function(state) {
    let buttons = $(".crm-form-submit").not("cancel");

    switch (state) {
      case "show": {
        buttons.removeAttr("disabled");
      }
      break;

      case "hide": {
        buttons.attr("disabled", "disabled");
      }
      break;
    }
  });

  /**
   * Listener for when the case select element changes
   */
  let listenToCaseElement = (function() {
    let contribSource = $("select[data-crm-custom='FP_Funding_Resources:fundpot_cf_contrib_source']");
    let totalAmount = $("input#total_amount");

    if (!contribSource.length) {
      return console.error("FundPot: Unable to find contribution source.");
    } else if (!totalAmount.length) {
      return console.error("FundPot: Total amount not available.");
    }

    let fundPotElements = {
      totalAllocated: $("#fundpot-total-allocated"),
      totalFunded: $("#fundpot-total-funded"),
      fundsAvailable: $("#fundpot-funds-available"),
      fundsAfter: $("#fundpot-funds-after")
    };

    let checkFunds = (function(data) {
      if (!data) return;

      if (data.funds_after < 0) {
        toggleSaveButtons("hide");
        setErrorMessage("Please select another source as there are no funds (or not enough funds) available.", "contrib");
      }
      else {
        toggleSaveButtons("show");
        setErrorMessage("", "contrib");
      }
    });

    let data = null;

    contribSource.on('change', function() {
      let value = $(this).val();
      if (value === null)
        return console.error("FundPot: Invalid Case ID");

      Object.keys(fundPotElements).forEach(function(key) {
        fundPotElements[key].html("Loading...");
      });

      CRM.api3('FpSummary', 'get', {
        "sequential": 1,
        "case_ref": $(this).val()
      }).done(function(result) {
        if (result.is_error === 0 && result.count == 1) {
          data = result.values[0];
          data["funds_after"] = parseFloat(data.funds_available - totalAmount.val());
          Object.keys(data).forEach(function(key) {
            data[key] = parseFloat(data[key]).toFixed(2);
          });

          fundPotElements.totalAllocated.html("£  " + data.funds_allocated);
          fundPotElements.totalFunded.html("£  " + data.total_funded);
          fundPotElements.fundsAvailable.html("£  " + data.funds_available);
          fundPotElements.fundsAfter.html("£  " + data.funds_after);

          checkFunds(data);
        }
        else {
          Object.keys(fundPotElements).forEach(function(key) {
            fundPotElements[key].html("[ Please select a Contribution Source ]");
          });
        }
      });
    });

    totalAmount.on('change', function() {
      if (!data) return;
      data["funds_after"] = parseFloat(data.funds_available - totalAmount.val()).toFixed(2);
      fundPotElements.fundsAfter.html("£  " + data.funds_after);
      checkFunds(data);
    });
  });

  /**
   * Listener for when the event select element changes
   */
  let listenToEventElement = (function() {
    let eventRef = $("select[data-crm-custom='FP_Funding_Resources:fundpot_cf_event_reference']");
    let totalAmount = $("input#total_amount");

    if (!eventRef.length) {
      return console.error("FundPot: Unable to find event reference.");
    } else if (!totalAmount.length) {
      return console.error("FundPot: Total amount not available.");
    }

    let fundPotElements = {
      totalCost: $("#fundpot-event-cost"),
      totalFunded: $("#fundpot-event-funded"),
      eventBalance: $("#fundpot-event-outstanding"),
      fundsAfter: $("#fundpot-event-outstanding-after")
    };

    let checkFunds = (function(data) {
      if (data === null) return;

      if (data.total_cost == "N/A") {
        toggleSaveButtons("hide");
        setErrorMessage("This event does not currently have a set total cost.", "event");

        let URL = "{/literal}{crmURL p="civicrm/event/manage/settings" q="reset=1&action=update&id="}{literal}";
        URL += data.event_id;
        $("#fundpot-error-message-event").append("&nbsp;<a href='" + URL + "' target='_blank'>Click here to amend this event.</a>");
      }
      else if (data.outstanding_after < 0) {
        toggleSaveButtons("hide");
        setErrorMessage("Please lower the total amount of this contribution as it is funding more than the outstanding balance.", "event");
      }
      else {
        toggleSaveButtons("show");
        setErrorMessage("", "event");
      }
    });

    let data = null;

    eventRef.on('change', function() {
      let value = $(this).val();
      if (value === null)
        return console.error("FundPot: Invalid Event Reference");

      Object.keys(fundPotElements).forEach(function(key) {
        fundPotElements[key].html("Loading...");
      });

      CRM.api3('FpSummary', 'get', {
        "sequential": 1,
        "event_ref": value
      }).done(function(result) {
        if (result.is_error === 0 && result.count == 1 && result.values[0] != "") {
          data = result.values[0];
          data["event_id"] = value;
          data["outstanding_after"] = parseFloat(data.outstanding_balance - totalAmount.val()).toFixed(2);

          fundPotElements.totalCost.html("£  " + data.total_cost);
          fundPotElements.totalFunded.html("£  " + parseFloat(data.total_funded).toFixed(2));
          fundPotElements.eventBalance.html("£  " + parseFloat(data.outstanding_balance).toFixed(2));
          fundPotElements.fundsAfter.html("£  " + data.outstanding_after);

          checkFunds(data);
        }
        else {
          Object.keys(fundPotElements).forEach(function(key) {
            fundPotElements[key].html("[ Please select an Event Reference ]");
          });
        }
      });
    });

    totalAmount.on('change', function() {
      if (!data) return;
      data["outstanding_after"] = parseFloat(data.outstanding_balance - totalAmount.val()).toFixed(2);
      fundPotElements.fundsAfter.html("£  " + data.outstanding_after);
      checkFunds(data);
    });
  });

  $(document).ready(function() {
    moveElements();
    listenToCaseElement();
    listenToEventElement();
  });
})(cj);
</script>
{/literal}