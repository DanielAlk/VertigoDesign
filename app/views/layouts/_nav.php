<header class="container">
  <nav class="navbar row-margin">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo $path->home() ?>">
        <img alt="" src="<?php $asset->path('logo-main.png'); ?>">
      </a>
    </div>
    <?php render('layouts/_nav_bar.php', array( 'id' => 'navbar-main' )); ?>
  </nav>
</header>