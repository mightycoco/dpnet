<widget>
	<h3>Datasources</h3>

	<table>
	<?= $this->Html->tableCells([
	    ['Sum', "<b>$sum</b>", $this->Html->link('view', ['controller'=>'datasource', 'action'=>'index'])]
	])?>
	<?=$this->cell('Datasources::uriForm')?>
	</table>
</widget>