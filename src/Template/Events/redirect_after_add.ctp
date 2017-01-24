<style>
	body {background: #333}
	button {background: #888;}
	article {max-width:600px;margin:auto auto;padding: 15px;}
</style>
<article>
	<?php if(isset($forward)) { ?>
	<button><a href="<?=$forward?>">Return to Event</a></button>
	<?php } ?>
	<?php if(isset($back)) { ?>
	<button><a href="<?=$back?>">Back to backend</a></button>
	<?php } ?>
</article>