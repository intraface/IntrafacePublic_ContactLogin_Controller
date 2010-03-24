<?php if (is_array($debtors) AND count($debtors) > 0): ?>

<table>
    <caption><?php e($caption); ?></caption>
    <thead>
    <tr>
        <th><?php e(t('Number')); ?></th>
        <th><?php e(t('Date')); ?></th>
        <th><?php e(t('Amount')); ?></th>
        <th><?php e(t('Paid')); ?></th>
        <th><?php e(t('Status')); ?></th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($debtors AS $debtor):  ?>
        <tr>
            <td><?php e($debtor['number']); ?></td>
            <td><?php e($debtor['dk_this_date']); ?></td>
            <td><?php e($debtor['total']); ?></td>
            <td>
                <?php
                if (empty($debtor['where_to_id'])) {
                    echo $debtor['payment_total'];
                } else {
                    echo '&mdash;';
                }
                ?>
            </td>
            <td><?php
                if (empty($debtor['where_to_id'])) {
                    $skyldig = $debtor['total'] - $debtor['payment_total'];
                    if ($skyldig == 0) {
                        echo 'Betalt';
                    } elseif ($skyldig > 0 AND isset($debtor['payment_online']) AND $debtor['payment_online'] > 0) {
                        echo '<strong style="color: green;">Der er afventende betalinger på ' . number_format($debtor['payment_online'], 0, ',', '.') . ' kroner. Vi godkender den så snart vi ser den.</strong>';
                    } elseif ($debtor['type'] == 'invoice') {
                        echo 'Du skylder ' . number_format($skyldig, 0, ',', '.') . ' kroner. <a href="' . url($debtor['id'] . '/payment') . '">Betaling &rarr;</a>';
                    } else {
                        echo 'Oprettet';
                    }
                } else {
                    echo 'Behandlet';
                }
                ?>
            </td>

            <td>
                <?php if (isset($skyldig) AND $skyldig == 0): ?>
                <a href="<?php e(url($debtor['id'])); ?>"><?php e(t('Receipt')); ?></a>
                <?php else: ?>
                <a href="<?php e(url($debtor['id'])); ?>"><?php e(t('See order')); ?></a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>
