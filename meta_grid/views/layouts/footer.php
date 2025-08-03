<?php
use vendor\meta_grid\helper\ApplicationVersion;
?>
<footer class="main-footer">
    <strong>2012-2025 <a href="https://meta-grid.com">Meta#Grid</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> <?= (new ApplicationVersion())->getVersion() ?>
    </div>
</footer>