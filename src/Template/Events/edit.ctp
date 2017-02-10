<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $event->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $event->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Events'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="events form large-9 medium-8 columns content">
    <?= $this->Form->create($event) ?>
    <fieldset>
        <legend><?= __('Edit Event') ?></legend>
        <?php
            if(isset($event->cover)) { echo $this->Html->image($event->cover); }
            echo $this->Form->input('datasource_id', ['type'=>'text']);
            echo $this->Form->input('event_description');
            echo $this->Form->input('event_name');
            echo $this->Form->input('event_approval', ['type'=>'select', 'options'=>['pending'=>'pending','approved'=>'approved','rejected'=>'rejected']]);
            echo $this->Form->input('event_start', ['empty' => true]);
            echo $this->Form->input('event_end', ['empty' => true]);
            echo $this->Form->input('place_id');
            echo $this->Form->input('place_name');
            echo $this->Form->input('cover');
            echo $this->Form->input('loc_city');
            echo $this->Form->input('loc_country');
            echo $this->Form->input('loc_street');
            echo $this->Form->input('loc_zip');
            echo $this->Form->input('loc_latitude');
            echo $this->Form->input('loc_longitude');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
