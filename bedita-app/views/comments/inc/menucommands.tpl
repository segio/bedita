{*
Template incluso.
Menu a SX valido per tutte le pagine del controller.
*}

<script type="text/javascript">
{literal}
$(document).ready(function(){
	$("#delBEObject").submitConfirm({
		{/literal}
		action: "{if !empty($delparam)}{$html->url($delparam)}{else}{$html->url('delete/')}{/if}",
		message: "{t}Are you sure that you want to delete the item?{/t}",
		formId: "updateForm"
		{literal}
	});
	
	$("div.insidecol input[@name='save']").click(function() {
		$("#updateForm").submit();
	});
});
</script>
{/literal}

<div class="secondacolonna {if !empty($fixed)}fixed{/if}">
	
	{if !empty($method) && $method != "index"}
		{assign var="back" value=$session->read("backFromView")}
	{else}
		{assign_concat var="back" 0="/" 1=$currentModule.path}
	{/if}

	<div class="modules">
		<label class="{$moduleName}" rel="{$back}">{t}{$currentModule.label}{/t}</label>
	</div> 
	
	{include file="../common_inc/messages.tpl"}
	
	
	{if !empty($method) && $method != "index" && $module_modify eq '1'}
	<div class="insidecol">
		
		<input class="bemaincommands" type="button" value=" {t}Save{/t} " name="save" />	
		<input class="bemaincommands" type="button" value="{t}Delete{/t}" name="delete" id="delBEObject" {if !($object.id|default:false)}disabled="1"{/if} />

	</div>
	
		{include file="../common_inc/prevnext.tpl"}
	
	{/if}



</div>

