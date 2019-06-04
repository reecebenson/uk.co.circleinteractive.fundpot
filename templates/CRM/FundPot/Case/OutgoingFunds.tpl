{**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 *}

<div id="funds-history" class="crm-accordion-wrapper crm-ajax-accordion collapsed">
  <div class="crm-accordion-header">
    Outgoing Funds
  </div>
  <div class="crm-accordion-body crm-ajax-container">
    <div id="funds-history">
      {foreach from=$funders key=case_id item=funder}
        {include file="CRM/FundPot/FunderRow.tpl" funder="$funder"}
      {/foreach}
      {if empty($funders)}
      <div style="margin: 0 5px;">
        <div class="help">There are no outgoing funds for this case.</div>
      </div>
      {/if}
    </div>
    <div class="clear"></div>
  </div>
</div>

{literal}
<script type="text/javascript">
(function($) {
  /**
   * Move "Outgoing Funds" to the top of the inline tabs
   */
  let moveOutgoingFunds = (function() {
    let caseControlPanel = $(".case-control-panel");
    if (!caseControlPanel.length) {
      return console.error("FundPot: No control panel.");
    }

    $("#funds-history").insertAfter(caseControlPanel);
  });

  $(document).ready(function() {
    moveOutgoingFunds();
  });
})(cj);
</script>
{/literal}