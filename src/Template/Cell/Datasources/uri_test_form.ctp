<?=$this->Form->create('events', ['uri'=>'uriTestForm'])?>
<?= $this->Html->tableCells([
    [
    	[$this->Form->input('test_uri', ['type'=>'text']), ['colspan'=>'2']], 
    	$this->Form->button(__('Test'))
    ]
])?>
<?= $this->Form->end() ?>