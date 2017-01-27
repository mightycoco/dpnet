<widget>
	<h3>Events</h3>

	<table>
	<?= $this->Html->tableCells([
	    ['Pending', "<b>$pending</b>", $this->Html->link('view', ['controller'=>'events', 'action'=>'index', 'event_approval'=>'pending'])],
	    ['Approved', "<b>$approved</b>", $this->Html->link('view', ['controller'=>'events', 'action'=>'index', 'event_approval'=>'approved'])],
	    ['Rejected', "<b>$rejected</b>", $this->Html->link('view', ['controller'=>'events', 'action'=>'index', 'event_approval'=>'rejected'])],
	    ['Sum', "<b>$sum</b>", $this->Html->link('view', ['controller'=>'events', 'action'=>'index'])]
	])?>
	<?=$this->cell('Events::uriForm')?>
	</table>
	
	<h5>Events created per day</h5>
	<?= $usage_created[0]['date'] ?> to <?= $usage_created[sizeof($usage_created)-1]['date'] ?>
	<div class='bars'>
		<?php foreach ($usage_created as $use): ?>
			<bar title='<?=$use['date']?>'>
				<text>
					<?=$use['sum']?>
				</text>
				<thumb style='height:<?=100/$sum*$use['sum']?>px'></thumb>
			</bar>
			<br/>
		<?php endforeach; ?>
	</div>
</widget>