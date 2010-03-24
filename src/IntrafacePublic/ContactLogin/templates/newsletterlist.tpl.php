<h1><?php e(t('Newsletters')); ?></h1>

<?php if (!empty($msg)): ?>
    <p class="notice"><?php e($msg); ?></p>
<?php endif; ?>

<p><a href="<?php e(url('../')); ?>"><?php e(t('Go to dashboard')); ?></a></p>

<?php if (is_array($newsletterlists) AND count($newsletterlists) > 0): ?>
    <form action="<?php e(url('./')); ?>" method="post">
    <table>
        <caption><?php e($caption); ?></caption>
        <?php foreach ($newsletterlists AS $list): ?>
                    <?php $disable_subscribe = ""; $disable_unsubscribe = 'disabled="true"'; if (is_array($subscriptions) AND in_array($list['id'], $subscriptions)) { $disable_subscribe ='disabled="true"'; $disable_unsubscribe = ""; } ?>
                    <?php
                        $disable_confirm = 'disabled="true"';
                        $code = '';

                        foreach ($optin AS $optins) {
                            if ($optins['list_id'] == $list['id']) { $disable_subscribe = 'disabled="true"'; $disable_confirm = '';  $code = $optins['code']; }
                        }
                     ?>
            <tr>
                <td><?php e($list['title']); ?></td>
                <td>
                    <input type="submit" name="subscribe[<?php e($list['id']); ?>]" value="<?php e(t('Subscribe')); ?>" <?php e($disable_subscribe); ?> />
                </td>
                <td>
                    <input type="submit" name="unsubscribe[<?php e($list['id']); ?>]" value="<?php e(t('Unsubscribe')); ?>" <?php e($disable_unsubscribe); ?> />

                </td>
                <td>
                     <input type="hidden" value="<?php e($code); ?>" name="code[<?php e($list['id']); ?>]" />
                     <input type="submit" value="<?php e(t('Confirm subscription')); ?>" name="confirm[<?php e($list['id']); ?>]" <?php e($disable_confirm); ?> />
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    </form>

<?php else: ?>
    <p><?php e(t('No newsletters to subscribe to')); ?></p>
<?php endif; ?>

