<widget>
	<h3>Git</h3>

	<h5>Status</h5>
	<div>
		<pre>
		<?= $git_status ?>
		</pre>
		<?= $this->Html->link('pull', ['git'=>'pull'], ['class'=>'button', 'class'=>'button']) ?>
	</div>
</widget>