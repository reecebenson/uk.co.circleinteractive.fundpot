{**
 * ------------------------------------------------------------------
 * Circle Interactive
 * ------------------------------------------------------------------
 * @package   FundingPot
 * @author    Reece Benson <reece@circle-interactive.co.uk>
 * @since     v1.0.0
 *}
<tr>
    <td>{$fund.total_amount|crmMoney:$fund.currency}</td>
    <td>{$fund.currency}</td>
    <td>{$fund.receive_date|truncate:10:''|crmDate}</td>
    <td><a href="{crmURL p='civicrm/contact/view' q="action=view&reset=1&cid=`$fund.contact_id`"}">{$fund.contact_name}</a></td>
</tr>