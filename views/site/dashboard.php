<div class="wrapper">
    <div class="sidebar">
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li>Username:- <?= $username ?></li>
            <li>Email:- <?= $email ?></li>
        </ul>
    </div>

    <div class="main-content">
        <h4>Welcome to the Dashboard.</h4>
        <!-- Add Task Button -->
        <a href="<?= Yii::$app->urlManager->createUrl(['site/add']) ?>" class="btn btn-primary float-right">Add Task</a>
    </div>
</div>