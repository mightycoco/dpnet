<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Event'), ['action' => 'add'], ['class'=>'fa fa-plus-square']) ?></li>
        <li><?= $this->Html->link(__('New from Uri'), ['action' => 'addFromUri'], ['class'=>'fa fa-link']) ?></li>
        <li class="heading"><?= __('Filter') ?></li>
        <li><a class="fa fa-filter" href="<?= $this->Paginator->generateUrl(['event_start'=>'all', 'event_approval'=>'all']) ?>">All</a></li>
        <li><a class="fa fa-filter <?=queryActive('event_approval','pending')?>" href="<?= $this->Paginator->generateUrl(['event_approval'=>'pending']) ?>">Pending</a></li>
        <li><a class="fa fa-filter <?=queryActive('event_approval','approved')?>" href="<?= $this->Paginator->generateUrl(['event_approval'=>'approved']) ?>">Approved</a></li>
        <li><a class="fa fa-filter <?=queryActive('event_approval','rejected')?>" href="<?= $this->Paginator->generateUrl(['event_approval'=>'rejected']) ?>">Rejected</a></li>
        <li><a class="fa fa-filter <?=queryActive('event_start','upcoming')?>" href="<?= $this->Paginator->generateUrl(['event_start'=>'upcoming']) ?>">Upcoming</a></li>
        <li><a class="fa fa-filter <?=queryActive('event_start','past')?>" href="<?= $this->Paginator->generateUrl(['event_start'=>'past']) ?>">Past</a></li>
    </ul>
</nav>

<div class="events index large-9 medium-8 columns content">
	<h3><?= __('Events') ?></h3>

    <span class='actions right'>
		<button class="dropdown">
			<span class='fa fa-sort-amount-asc'></span>
			<div class="dropdown-content">
                <?= $this->Paginator->sort('id') ?>
                <?= $this->Paginator->sort('created') ?>
                <?= $this->Paginator->sort('event_name') ?>
                <?= $this->Paginator->sort('event_approval') ?>
                <?= $this->Paginator->sort('event_start') ?>
                <?= $this->Paginator->sort('place_name') ?>
                <?= $this->Paginator->sort('loc_city') ?>
                <?= $this->Paginator->sort('loc_country') ?>
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

	<?php foreach ($events as $event): ?>
		<?php echo $this->element('event', ['item'=>$event]); ?>
	<?php endforeach; ?>
	
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter('{{page}} / {{pages}} ({{count}} records)') ?></p>
    </div>
</div>
