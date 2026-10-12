<?php
use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Page navigation">
    <ul class="pagination pagination-sm mb-0 align-items-center gap-1">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a class="page-link rounded-2 px-2 py-1" href="<?= $pager->getFirst() ?>" aria-label="First" title="First Page">
                    <i class="fas fa-angle-double-left"></i>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link rounded-2 px-3 py-1 fw-semibold" href="<?= $pager->getPrevious() ?>" aria-label="Previous">
                    <i class="fas fa-chevron-left me-1"></i> Prev
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link rounded-2 px-3 py-1 text-muted"><i class="fas fa-chevron-left me-1"></i> Prev</span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link rounded-2 px-3 py-1 fw-bold" href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a class="page-link rounded-2 px-3 py-1 fw-semibold" href="<?= $pager->getNext() ?>" aria-label="Next">
                    Next <i class="fas fa-chevron-right ms-1"></i>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link rounded-2 px-2 py-1" href="<?= $pager->getLast() ?>" aria-label="Last" title="Last Page">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link rounded-2 px-3 py-1 text-muted">Next <i class="fas fa-chevron-right ms-1"></i></span>
            </li>
        <?php endif ?>
    </ul>
</nav>
