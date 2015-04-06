
<!-- Module BlockArrival -->

<link type="text/css" rel="stylesheet" href="{$arrival_path}blockarrival.css" />



<div id="arrival_block_center" class="arrival_block">
	<h4>{$arrival_title}</h4>

	<div id="arrival_txt" class="arrival_content">

  {if $result}
   	<div class="blinking_content arrival_block_content"><p>{$result}</p></div>
  {/if}

  {if $xmlarr->body->$paragraph}
   	<div class="arrival_block_content">{$xmlarr->body->$paragraph|stripslashes}</div>
  {/if}
	</div>
	<div class="clear"></div>
</div>

<!-- /Module BlockArrival -->
