<?=$this->Form->create('events', ['url'=>'/datasource/add-from-uri'])?>
<?= $this->Html->tableCells([
    [
    	[$this->Form->input('uri', ['type'=>'text']), ['colspan'=>'2']], 
    	$this->Form->button(__('Submit'))
    ]
])?>
<?= $this->Form->end() ?>