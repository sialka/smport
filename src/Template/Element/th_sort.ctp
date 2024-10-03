<?php
/**
 * TH WITH ICON SORT
 * 0 = With % 1 = Table.Field 2 = Title
 */

    $this->Paginator->templates([
        'sort' => '<a class="text-dark no-decoration" href="{{url}}">{{text}}</a>',
        'sortAsc' => '<a class="text-dark no-decoration" href="{{url}}">{{text}}</a>',
        'sortDesc' => '<a class="text-dark no-decoration" href="{{url}}">{{text}}</a>',
    ]);
?>

<th class="text-left px-3" width="<?= $th[0] ?>">
    <div class="d-flex justify-content-between">
        <div>
            <?= $this->Paginator->sort($th[1], $th[2]); ?>
        </div>
        <div>
            <i class="fa fa-sort"></i>
        </div>
    </div>
</th>