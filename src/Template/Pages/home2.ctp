	<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

$this->layout = false;

$cakeDescription = 'dark-party.net';

// cool looking maps
// https://github.com/manifestinteractive/jqvmap

/*$events = TableRegistry::get('Events')
			->find('all')
			//->where(['event_start >'=>(new DateTime())->format('Y-m-d')])
			->where([
				'event_start >=' => new DateTime('now'),
				'event_end <=' => new DateTime('now + 14 day')
			])
			->order(['event_start'=>'asc']);*/
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('frontend.css') ?>
</head>
<body class="home">
    <header>
        <div class="header-image">
            <?= $this->Html->image('//cakephp.org/img/logo-cake.png') ?>
            <h1>the dark party network</h1>
        </div>
    </header>
    <div id="content">
        <div class="row">
            <div class="columns large-12 ctp-warning checks">
                <?= (new DateTime())->format('Y-m-d') ?>
            </div>

            <div class="columns large-12 data">
                <h4>14 days Agenda</h4>

				<div class="paginator">
					<ul class="pagination">
						<?= $this->Paginator->prev('< ' . __('previous')) ?>
						<?= $this->Paginator->numbers() ?>
						<?= $this->Paginator->next(__('next') . ' >') ?>
					</ul>
				</div>
				
                <?php foreach ($events as $event): ?>
                <p>
                	<?= $event->event_start->format('D, d/m/Y') ?> '<?= $event->place_name ?>' in <?= $event->loc_city ?> (<?= $event->loc_country ?>)<br/>
                	<a href='https://facebook.com/<?= $event->id ?>' target='fb'><?= $event->event_name ?></a>
                </p>
                <?php endforeach; ?>
                
				<div class="paginator">
					<ul class="pagination">
						<?= $this->Paginator->prev('< ' . __('previous')) ?>
						<?= $this->Paginator->numbers() ?>
						<?= $this->Paginator->next(__('next') . ' >') ?>
					</ul>
					<p><?= $this->Paginator->counter() ?></p>
				</div>

                <hr>
                <h4>Map</h4>
                <iframe class='mapiframe' width="100%" height="280" frameborder="0" scrolling="no" src="//www.bing.com/maps/embed/viewer.aspx?v=3&cp=50.029679~28.816805&lvl=6&w=3000&h=300&sty=r&typ=d&pp=&ps=&dir=0t=en-us&src=SHELL&form=BMEMJS"></iframe>
            </div>
        </div>

        <div class="row footer">
            <div class="columns large-9">
                <h3>dark party net</h3>
                <ul>
                    <li>To change the content of this page, edit: src/Template/Pages/home.ctp.</li>
                    <li>You can also add some CSS styles for your pages at: webroot/css/.</li>
                </ul>
            </div>
            <div class="columns large-3">
                <h3>Getting Started</h3>
                <ul>
                    <li><a target="_blank" href="//book.cakephp.org/3.0/en/">CakePHP 3.0 Docs</a></li>
                    <li><a target="_blank" href="//book.cakephp.org/3.0/en/tutorials-and-examples/bookmarks/intro.html">The 15 min Bookmarker Tutorial</a></li>
                    <li><a target="_blank" href="//book.cakephp.org/3.0/en/tutorials-and-examples/blog/blog.html">The 15 min Blog Tutorial</a></li>
                </ul>
                <p>
            </div>
        </div>

    </div>
</body>
</html>
