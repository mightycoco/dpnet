<widget>
	<h3>Events</h3>

	<table>
	<?= $this->Html->tableCells([
	    ['Pending', "<b>$pending</b>", $this->Html->link('view', ['controller'=>'events', 'action'=>'index', 'event_approval'=>'pending'], ['class'=>'button', 'class'=>'button'])],
	    ['Approved', "<b>$approved</b>", $this->Html->link('view', ['controller'=>'events', 'action'=>'index', 'event_approval'=>'approved'], ['class'=>'button'])],
	    ['Rejected', "<b>$rejected</b>", $this->Html->link('view', ['controller'=>'events', 'action'=>'index', 'event_approval'=>'rejected'], ['class'=>'button'])],
	    ['Sum', "<b>$sum</b>", $this->Html->link('view', ['controller'=>'events', 'action'=>'index'], ['class'=>'button'])]
	])?>
	<?=$this->cell('Events::uriForm')?>
	</table>
	
	<h5>Events created per day</h5>
	<?= $usage_created[0]['date'] ?> &mdash; <?= $usage_created[sizeof($usage_created)-1]['date'] ?>
	<div class='bars'>
		<?php foreach ($usage_created as $use): ?>
			<bar title='<?=$use['date']?>'>
				<text>
					<div class='sum'><?=$use['sum']?></div>
					<div class='date'><?=(new DateTime($use['date']))->format('D')?></div>
				</text>
				<thumb style='height:<?=100/$usage_max*$use['sum']?>px'></thumb>
			</bar>
			<br/>
		<?php endforeach; ?>
	</div>
</widget>