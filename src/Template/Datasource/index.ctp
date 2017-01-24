<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New'), ['action' => 'add'], ['class'=>'fa fa-plus-square']) ?></li>
        <li><?= $this->Html->link(__('New from Uri'), ['action' => 'addFromUri'], ['class'=>'fa fa-link']) ?></li>
    </ul>
</nav>
<div class="datasource index large-9 medium-8 columns content">
    <h3><?= __('Datasource') ?></h3>

    <span class='actions right'>
		<button class="dropdown">
			<span class='fa fa-sort-amount-asc'></span>
			<div class="dropdown-content">
                <?= $this->Paginator->sort('uuid') ?>
                <?= $this->Paginator->sort('description') ?>
                <?= $this->Paginator->sort('source') ?>
                <?= $this->Paginator->sort('created') ?>
                <?= $this->Paginator->sort('modified') ?>
                <?= $this->Paginator->sort('accessed') ?>
			</div>
		</button>
	</span>

    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
    </div>
    
	<?php foreach ($datasource as $datasource): ?>
		<?php echo $this->element('datasource', ['item'=>$datasource]); ?>
	<?php endforeach; ?>

    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter('{{page}} / {{pages}} ({{count}} records)') ?></p>
    </div>
</div>
