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
</widget>