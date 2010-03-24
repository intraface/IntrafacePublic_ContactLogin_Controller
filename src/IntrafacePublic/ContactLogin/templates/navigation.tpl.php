<form style="float: right; width: 80px; align: center;" method="post" action="<?php e(url('../logout')); ?>">
<a href="http://en.gravatar.com/site/signup" title="<?php e(t('Get your own gravatar')); ?>"><img style="border: none; align: center;" src="http://www.gravatar.com/avatar/<?php e(md5($email)); ?>?s=80&amp;d=wavatar" height="80" width="80" /></a><p><input type='submit' value='Log out' /></p></form>

<h1><?php e(t('Contact login')); ?></h1>

<ul>
	<li><a href="<?php e('./'); ?>"><?php e(t('dashboard')); ?></a></li>
<?php foreach ($navigation as $nav): ?>
	<li><a href="<?php e(url($nav)) ?>"><?php  e($nav); ?></a></li>
<?php endforeach; ?>
</ul>