{*
Template incluso.
Menu a SX valido per tutte le pagine del controller.
*}
{if !empty($object_master)}
{assign_concat var=back_url 0="/" 1=$object_master.ObjectType.module 2="/view/" 3=$object_master.id}
<script type="text/javascript">
{literal}
$(document).ready(function(){
	$("#delLangText").submitConfirm({
		{/literal}
		action: "{if !empty($delparam)}{$html->url($delparam)}{else}{$html->url('delete/')}{/if}",
		message: "{t}Are you sure that you want to delete the item?{/t}",
		formId: "updateForm"
		{literal}
	});

	$("div.insidecol input[@name='save']").click(function() {
		$("#updateForm").submit();
	});
	
	var urlBack = '{/literal}{$html->url("$back_url")}{literal}';
	$("#backBEObject").click(function() {
		document.location = urlBack;
	});
});
</script>
{/literal}
{/if}

<div class="secondacolonna {if !empty($fixed)}fixed{/if}">

	{if !empty($method) && $method != "index"}
		{assign var="back" value=$session->read("backFromView")}
	{else}
		{assign_concat var="back" 0="/" 1=$currentModule.path}
	{/if}

	<div class="modules">
		<label class="{$moduleName}" rel="{$back}">{t}{$currentModule.label}{/t}</label>
	</div>
	
	
	{assign var="user" value=$session->read('BEAuthUser')}

	{if !empty($method) && $method != "index"} 
	<div class="insidecol">
		{if $module_modify eq '1'}		
			<input class="bemaincommands" type="button" value=" {t}Save{/t} " name="save" />
			<input class="bemaincommands" type="button" value="{t}Delete{/t}" name="delete" id="delLangText" {if !($object_translation.id|default:false)}disabled="1"{/if} />
		{/if}
		<input class="bemaincommands" type="button" value="{t}Back to {$object_master.ObjectType.name}{/t}" name="back" id="backBEObject"/>
	</div>
	{/if}

</div>
