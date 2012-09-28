{*
Template incluso.
Menu a SX valido per tutte le pagine del controller.
*}

<div class="primacolonna">

	<div class="modules"><label class="bedita" rel="{$this->Html->url('/')}">{$conf->projectName|default:$conf->userVersion}</label></div>

	
	
		<ul class="menuleft insidecol">
		<li {if $view->action eq 'index'}class="on"{/if}>{$this->Tr->link('News', '/news')}</li>
		<li {if $view->action eq 'categories'}class="on"{/if}>{$this->Tr->link('Categories', '/news/categories')}</li>
		{if $module_modify eq '1'}
		<li><a href="{$this->Html->url('/news/view')}">{t}Create news{/t}</a></li>
		{/if}
	</ul>

{$view->element('export')}

{if (!empty($view->action)) && $view->action eq "index"}

		<div class="insidecol publishingtree">
			
			{$view->element('tree')}
		
		</div>

{/if}

{$view->element('user_module_perms')}

</div>