<table class='crm-update_scheduled_mailings-main-update_scheduled_mailings section form-layout-compressed'>
  <tr class="crm-update_scheduled_mailings-update_scheduled_mailings section">
    <td class="label">{$form.update_scheduled_mailings.label}</td>
    <td class="content">{$form.update_scheduled_mailings.html}</td>
  </tr>
</table>

{literal}
<script type="text/javascript">
  CRM.$(function($) {
    $($('table.crm-update_scheduled_mailings-main-update_scheduled_mailings')).insertAfter('div#pdf_format');
  });
</script>
{/literal}
