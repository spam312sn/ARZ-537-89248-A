<?php /** @var array $data */ ?>
<div class="row">
    <div class="col s3">
        <div class="collection">
            <a href="/u/inbox/" class="collection-item">Inbox<?=$data["inbox_badge"]?></a>
            <a href="/u/spam/" class="collection-item">Spam<?=$data["spam_badge"]?></a>
            <a href="/u/trash/" class="collection-item">Trash<?=$data["trash_badge"]?></a>
        </div>
        <div class="collection">
            <a href="/security/logout" class="collection-item">Log out</a>
        </div>
    </div>
    <?=$data["center"]?>
</div>
