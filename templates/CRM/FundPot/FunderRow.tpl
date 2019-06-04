{**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 *}

<div id="fund-{$case_id}" class="crm-accordion-wrapper collapse{if $collapsed}d{/if}">
    <div class="crm-accordion-header active"><p>{$funder.subject}</p></div>
    <div class="crm-accordion-body">
        <p style="margin-left: 5px;">
        Fund Amount: {$funder.total|crmMoney}
        {if $type == "case"}
            (<a href="{crmURL p='civicrm/event/manage/funding' q="action=update&reset=1&id=`$funder.ref_id`"}">View more details</a>)
        {elseif $type == "event"}
            (<a href="{crmURL p='civicrm/contact/view/case' q="action=view&reset=1&cid=`$funder.contact_id`&id=`$funder.ref_id`"}">View more details</a>)
        {/if}
        </p>

        <table id="active_pledge_{$id}" class="display">
            <thead>
                <tr>
                    <th style="width:25%;">Contribution Amount</th>
                    <th style="width:25%;">Currency</th>
                    <th style="width:25%;">Date</th>
                    <th style="width:25%;">Donor</th>
                </tr>
            </thead>
            <tbody>
            {foreach from=$funder.contributions item=fp_funds}
                {include file="CRM/FundPot/FundTableRow.tpl" fund="$fp_funds"}
            {/foreach}
            </tbody>
        </table>
    </div>
</div>