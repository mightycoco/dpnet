<div class='datasource-item view-item'>
	<span class='actions'>
		<button class="dropdown">
			<span class='fa fa-list'></span>
			<div class="dropdown-content">
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $item->uuid], ['class'=>'fa fa-edit']) ?>
				<?= $this->Form->postLink(__('Delete'), 
										['action' => 'delete', $item->uuid], 
										['class'=>'fa fa-trash', 'confirm' => __('Are you sure you want to delete # {0}?', $item->description)]) ?>
			</div>
		</button>
	</span>
	<span class='id'><?= $item->uuid ?></span>
	<span class='description'><?= $this->Html->link(__($item->description), ['action' => 'edit', $item->uuid]) ?></span>
	<span class='type'><?= $item->type ?></span>
	<span class='source'><?= $item->source ?></span>
	<span class='created'>
		<span title="created" class="label"><?= $item->created->format("d/m/Y H:i:s") ?></span>
		<span title="modified" class="label"><?= $item->modified->format("d/m/Y H:i:s") ?></span>
		<span title="accessed" class="label"><?= isset($item->accessed) ? $item->accessed->format("d/m/Y H:i:s") : "not yet" ?></span>
	</span>
</div>