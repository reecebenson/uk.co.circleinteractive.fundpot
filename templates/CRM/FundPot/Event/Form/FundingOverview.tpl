{**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 *}

{if not empty($funders)}
  <div class="help">
    You can see &amp; monitor the incoming funds for this event here.
  </div>
  This event has been funded by <strong>{", "|implode:$funder_names}</strong>
  <hr/>
  <div class="crm-fundpot-funders">
    {foreach from=$funders key=case_id item=funder}
      {include file="CRM/FundPot/FunderRow.tpl" funder="$funder"}
    {/foreach}
  </div>
{else}
  <div class="help">
    There have been no funds allocated to this event.
  </div>
{/if}