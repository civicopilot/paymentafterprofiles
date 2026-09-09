<div id="paymentafterprofiles-option" class="crm-section">
  {$form.paymentafterprofiles_enabled.html}
  {$form.paymentafterprofiles_enabled.label}
</div>
{literal}
<script type="text/javascript">
  CRM.$(function ($) {
    $('#paymentafterprofiles-option').appendTo(
      '.crm-contribution-contributionpage-custom-form-block-custom_post_id td.html-adjust'
    );
  });
</script>
{/literal}
