{strip}
  <table class="selector row-highlight">
    <thead class="sticky">
    <tr>
      {foreach from=$columnHeaders item=header}
        <th scope="col">
            {$header}
        </th>
      {/foreach}
    </tr>
    </thead>

    {foreach from=$membershipDurations item=membershipduration}
      <tr>
          <td>{$membershipduration.start_date|crmDate}</td>
          <td>{$membershipduration.end_date|crmDate}</td>
          <!--  <td>{$membershipduration.renew_timestamp|crmDate}</td>  -->
          <td>
              {if $membershipduration.total_contribution_amount}
                <center>
                  {$membershipduration.total_contribution_amount|crmMoney:$membershipduration.contribution_currency}<br>
                  <a href="{$membershipduration.contribution_url}" class="action-item crm-hover-button">View Details</a>
                </center>
              {else}
                  <center>No Contribution</center>
              {/if}
          </td>
      </tr>
    {/foreach}
  </table>
{/strip}
