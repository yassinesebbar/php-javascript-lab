
<div>
    <?php if(isset($_SESSION['is_logged_in'])) : ?>
    <a class="btn btn-success btn-share" href="<?php echo ROOT_PATH; ?>shares/add">Share something</a>
    <?php endif; ?>
    <?php foreach($viewmodel as $item): ?>
    <div class="jumbotron">
        <h3><?php echo $item['title']; ?></h3>
        <small><?php echo $item['create_time']; ?></small>
        <hr>
        <p><?php echo $item['body'] ?></p>
        <a class="btn btn-secondary" href="<?php echo $item['link'] ?>" target="_blank">go to website</a>
        <br>
    </div>
    <?php endforeach; ?>
</div>