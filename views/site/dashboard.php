<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

$this->title = 'Dashboard';
?>
<div class="wrapper">
    <div class="sidebar">
        <ul>
            <li>Dashboard</li>
            <li>Username:- <?= $username ?></li>
            <li>Email:- <?= $email ?></li>
        </ul>
    </div>

    <div class="main-content">
        <h4>Welcome to the Dashboard.</h4>
        <!-- Add Task Button -->
        <a href="<?php //echo Yii::$app->urlManager->createUrl(['site/add']) 
                    use yii\helpers\Html;
                    use yii\helpers\Url;
                    ?>" class="btn btn-primary float-right" type="button" data-toggle="modal" data-target="#myModal">Add Task</a>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Your Task</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Task Form -->
                    <form id="taskForm" action="add" method="post">
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        <div class="form-group">
                            <label for="taskName">Task Name:</label>
                            <input type="text" class="form-control" id="taskName" name="taskName" required>
                        </div>
                        <div class="form-group">
                            <label for="taskDescription">Task Description:</label>
                            <textarea class="form-control" id="taskDescription" name="taskDescription" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Save Task</button>
                    </form>
                    <!-- End Task Form -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Display Table of User Created Tasks -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr.No.</th>
                <th>Task Name</th>
                <th>Description</th>
                <th>Action</th>
                <!-- You can add more columns as needed -->
            </tr>
        </thead>
        <tbody>
            <!-- Example rows (replace this with your dynamic data) -->
            <?php foreach ($userTask as $key => $model) { ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $model->task_name ?></td>
                    <td><?= $model->description ?></td>
                    <td>
                        <!-- Edit Button to Open Modal -->
                        
                        <button class="btn btn-primary" data-toggle="modal" data-target="#editModal<?= $model->id ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                    </td>
                </tr>

                <!-- Edit Task Modal -->
                <div class="modal fade" id="editModal<?= $model->id ?>" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Task</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <!-- Task Edit Form -->
                                <form id="taskEditForm<?= $model->id ?>" action="<?= Url::to(['site/update', 'id' => $model->id]) ?>" method="post">
                                    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                                    <div class="form-group">
                                        <label for="taskName<?= $model->id ?>">Task Name:</label>
                                        <input type="text" class="form-control" id="taskName<?= $model->id ?>" name="taskName" value="<?= $model->task_name ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="taskDescription<?= $model->id ?>">Task Description:</label>
                                        <textarea class="form-control" id="taskDescription<?= $model->id ?>" name="taskDescription" rows="4" required><?= $model->description ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">Update Task</button>
                                </form>
                                <!-- End Task Edit Form -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Edit Task Modal -->

            <?php } ?>
            <!-- Add more rows as needed -->
        </tbody>
    </table>
</div>