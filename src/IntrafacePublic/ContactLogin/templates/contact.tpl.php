<h1><?php e(t('Dashboard')); ?></h1>

            <?php if (!empty($this->document->navigation)): ?>
            <ul id="navigation">
                <?php foreach ($this->document->navigation as $navigation): ?>
                    <li><a href="<?php e(url($navigation)); ?>"><?php e(t($navigation)); ?></a></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>



<?php if (is_array($contact)): ?>
    <table summary="<?php e(t('Contact information')); ?>">
        <caption><?php e(t('Contact information')); ?></caption>
        <tr>
            <th><?php e(t('Number')); ?></th>
            <td><?php e($contact['number']); ?></td>
        </tr>
        <tr>
            <th><?php e(t('Name')); ?></th>
            <td><?php e($contact['name']); ?></td>
        </tr>
        <tr>
            <th><?php e(t('Address')); ?></th>
            <td>
                <?php if(!empty($contact['address'])): ?>
                    <?php e($contact['address']); ?>
                <?php else: ?>
                    IKKE INDSKREVET
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th><?php e(t('Zip code and city')); ?></th>
            <td>
                <?php if(!empty($contact['postcode'])): ?>
                    <?php e($contact['postcode']); ?>  <?php e($contact['city']); ?>
                <?php else: ?>
                    IKKE INDSKREVET
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th><?php e(t('Phone')); ?></th>
            <td><?php e($contact['phone']); ?></td>
        </tr>
        <tr>
            <th><?php e(t('E-mail')); ?></th>
            <td><?php e($contact['email']); ?></td>
        </tr>
    </table>

    <p><a href="<?php e(url(null, array('edit'))); ?>"><?php e(t('Edit')); ?></a></p>
<?php endif; ?>