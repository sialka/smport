<nav aria-label="breadcrumb">
<ol class="breadcrumb p-2 m-0">
    <li class="breadcrumb-item">
        <i class="fa fa-home"></i>
        <a class="text-dark no-decoration" href="/Admin/index">
            Admin
        </a>
    </li>
    <?php if(!empty($nav)) { ?>

        <?php foreach($nav as $text => $link ) { ?>

                <?php if(!empty($link)) { ?>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="text-dark no-decoration" href="<?= $link ?>"><?= $text ?></a>
                    </li>
                <?php } else { ?>
                    <li class="breadcrumb-item text-secondary" aria-current="page">
                        <?= $text ?>
                    </li>
                <?php } ?>

        <?php } ?>

    <?php } ?>
</ol>
</nav>
