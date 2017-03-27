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

$cakeDescription = 'dark-party.net - administration';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="application-name" content="dark-party.net"/>
	<meta name="msapplication-TileColor" content="#000000"/>
	<title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->script('jquery.min.js') ?>
    <?= $this->Html->script('jquery-ui.min.js') ?>
    <?= $this->Html->script('backend.js') ?>
    <?= $this->Html->script('translate.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <link rel="stylesheet" href="https://opensource.keycdn.com/fontawesome/4.7.0/font-awesome.min.css" integrity="sha384-dNpIIXE8U05kAbPhy3G1cz+yZmTzA6CY8Vg/u2L9xRnHjJiAK76m2BIEaSEV+/aU" crossorigin="anonymous">
</head>
<body>
	<?php if(isset($user['username'])): ?>
	<div class="main-mobile-bar"><a href="javascript:" class="fa fa-bars main-hamburger">dpnet</a></div>
	<nav class="top-bar expanded" data-topbar role="navigation">
		<div class="top-bar-section">
			<ul>
				<li><?= $this->Html->link(__("Dashboard"), ['controller'=>'dashboard', 'action' => 'index'], ['class'=>'fa fa-tachometer']) ?></li>
				<li><?= $this->Html->link(__("Datasource"), ['controller'=>'datasource', 'action' => 'index'], ['class'=>'fa fa-list']) ?></li>
				<li><?= $this->Html->link(__("Users"), ['controller'=>'users', 'action' => 'index'], ['class'=>'fa fa-user']) ?></li>
				<li><?= $this->Html->link(__("Subsystem"), ['controller'=>'subsystem', 'action' => 'index'], ['class'=>'fa fa-edit']) ?></li>
				<li><?= $this->Html->link(__("Event Model"), ['controller'=>'events', 'action' => 'index'], ['class'=>'fa fa-calendar-check-o']) ?></li>
			</ul>
			<ul class="right">
			</ul>
		</div>
	</nav>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-3 medium-4 columns">
            <li class="name">
                <h1><?= $this->Html->link(__($this->fetch('title')), ['action' => 'index']) ?></h1>
            </li>
        </ul>
        <div class="top-bar-section">
            <ul class="right text-right">
				<li><?= $this->Html->link(__("Logout ".$user['username']), ['controller'=>'users', 'action'=>'logout']) ?></li>
				<li><?= $this->Html->link(__("Frontend "), "/") ?></li>
            </ul>
        </div>
    </nav>
    <?php endif; ?>
    <?= $this->Flash->render() ?>
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>
