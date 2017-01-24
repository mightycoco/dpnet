<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index'], ['class'=>'fa fa-arrow-left']) ?></li>
    </ul>
</nav>
<div class="datasource form large-9 medium-8 columns content">
    <?= $this->Form->create($datasource) ?>
    <fieldset>
        <legend><?= __('Add Datasource') ?></legend>
        <?php
            echo $this->Form->input('type');
            echo $this->Form->input('source');
            echo $this->Form->input('trusted');
            echo $this->Form->input('accessed', ['empty' => true]);
            echo $this->Form->input('description');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
