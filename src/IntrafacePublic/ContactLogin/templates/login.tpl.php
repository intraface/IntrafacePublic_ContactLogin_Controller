<h1><?php e(t('Login')); ?></h1>

<p><strong><?php e($msg); ?></strong></p>

<p><?php e(t('You are ready to log into the')); ?> "<?php e($name); ?>" <?php e(t('contactlogin'))?>.</p>

<?php echo $form; ?>

<p><a href="<?php e('retrievepassword'); ?>"><?php e(t('Forgot password?')); ?></a></p>