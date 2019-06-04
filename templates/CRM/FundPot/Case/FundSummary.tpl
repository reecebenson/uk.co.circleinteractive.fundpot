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
  .crm-case-caseview-case_type.label.divider {
    background-color: rgba(128, 128, 128, 0.15) !important;
    width: 5px;
  }

  #fund-summary .bold {
    font-weight: bold;
  }

  td.danger {
    background-color: #F2B6B6 !important;
  }
</style>
{/literal}

<table>
  <tr id="fund-summary">
    <td class="crm-case-caseview-case_type label divider"></td>
    <td class="crm-case-caseview-case_type label">
      <span class="crm-case-summary-label bold">Total Allocated:</span><br/>
      {$funds_allocated|crmMoney}
    </td>
    <td class="crm-case-caseview-case_type label">
      <span class="crm-case-summary-label bold">Total Funded:</span><br/>
      {$total_funded|crmMoney}
    </td>
    <td class="crm-case-caseview-case_type label {if $funds_available < 0}danger{/if}">
      <span class="crm-case-summary-label bold">Funds Available:</span><br/>
      {$funds_available|crmMoney}
    </td>
  </tr>
</table>

{literal}
<script type="text/javascript">
(function($) {
  /**
   * Move td elements to case summary
   */
  let moveTableElements = (function() {
    let caseSummary = $(".report.crm-entity.case-summary");
    let tableContent = $("tbody > tr", caseSummary).first();

    if (!caseSummary.length) {
      return console.error("FundPot: No case summary.");
    }

    $("#fund-summary").children().appendTo(tableContent);
  });

  $(document).ready(function() {
    moveTableElements();
  });
})(cj);
</script>
{/literal}