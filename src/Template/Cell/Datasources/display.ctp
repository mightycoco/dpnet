<widget>
	<h3>Datasources</h3>
	<table>
	<?= $this->Html->tableCells([
	    ['Sum', "<b>$sum</b>", $this->Html->link('view', ['controller'=>'datasource', 'action'=>'index'], ['class'=>'button'])]
	])?>
	<?=$this->cell('Datasources::uriForm')?>
	<?=$this->cell('Datasources::uriTestForm')?>
	</table>
	
	<?php if(isset($testResult['error'])): ?>
	<?=$testResult['error']?>
	<?php endif; ?>
	
	<?php if(isset($testResult['name'])): ?>
		<?= $testResult['name'] ?><br/>
		<table>
		<?= $this->Html->tableHeaders(
		    ["Name", "Start Time", "Weight"]
		)?>
		<?php foreach($testResult['events'] as $event): ?>
			<?= $this->Html->tableCells([
			    [[$event->event_name, ['width'=>'60%']], (new \DateTime($event->event_start))->format('d/m/Y\<\b\r/>H:i'), $event->getWeight()]
			])?>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	<vscroll300>
	<table class='dense dashboard-datasources'>
		<?= $this->Html->tableHeaders(
		    ["Datasource", "<span class='fa fa-clock-o'></span>", "<span class='fa fa-check-circle-o'></span>", "<span class='fa fa-ban'></span>", "∑"]
		)?>
		<?php foreach($ds_usage as $apd): ?>
			<?= $this->Html->tableCells([
				    [$this->Html->link($apd['description'], ['controller'=>'datasource', 'action'=>'edit', $apd['id']]), 
				    $apd['pending'],
				    $apd['approved'],
				    $apd['rejected'],
				    $apd['sum']
			    ]
			])?>
		<?php endforeach; ?>
	</table>
	</vscroll300>
</widget>