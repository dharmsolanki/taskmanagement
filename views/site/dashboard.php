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
                    ?>" class="btn btn-primary float-right" type="button" data-toggle="modal" data-target="#myModal"><i class="fa-solid fa-plus"></i> Add Task</a>
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
                    <form id="taskForm" action="<?= Url::to(['site/add']) ?>" method="post">
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        <div class="form-group">
                            <label for="taskName">Task Title</label>
                            <input type="text" class="form-control" id="taskName" name="taskName" required>
                        </div>
                        <div class="form-group">
                            <label for="taskName">Start Date</label>
                            <input type="date" class="form-control" id="startDate" name="startDate" required>
                        </div>
                        <div class="form-group">
                            <label for="taskName">End Date</label>
                            <input type="date" class="form-control" id="endDate" name="endDate" required>
                        </div>
                        <div class="form-group">
                            <label for="taskName">Progress</label>
                            <select class="form-select" aria-label="Default select example" name="progress">
                                <option selected>Select</option>
                                <option value="1">Hold</option>
                                <option value="2">In Progress</option>
                                <option value="3">Complete</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="taskDescription">Task Description</label>
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
    <?php if (empty($userTask)) : ?>
        <p>No Task found.</p>
    <?php else : ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Sr No</th>
                    <th>Task Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Progress</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userTask as $key => $model) { ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $model->task_name ?></td>
                        <td><?= date("d/m/Y", strtotime($model->start_date)) ?></td>
                        <td><?= date("d/m/Y", strtotime($model->end_date)) ?></td>
                        <td>
                            <?php
                            $progressLabels = [
                                1 => 'Hold',
                                2 => 'In Progress',
                                3 => 'Complete',
                            ];

                            echo $progressLabels[$model->progress] ?? 'Unknown';
                            ?>
                        </td>

                        <td><?= $model->description ?></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Task Actions">
                                <!-- Edit Button to Open Modal -->
                                <button class="btn btn-primary" data-toggle="modal" data-target="#editModal<?= $model->id ?>">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                            </div>
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
                                            <label for="taskName<?= $model->id ?>">Task Title</label>
                                            <input type="text" class="form-control" id="taskName<?= $model->id ?>" name="taskName" value="<?= $model->task_name ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="taskName<?= $model->id ?>">Start Date</label>
                                            <input type="date" class="form-control" id="startDate<?= $model->id ?>" name="startDate" value="<?= $model->start_date ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="taskName<?= $model->id ?>">End Date</label>
                                            <input type="date" class="form-control" id="endDate<?= $model->id ?>" name="endDate" value="<?= $model->end_date ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="progress<?= $model->id ?>">Progress</label>
                                            <select class="form-select" id="progress<?= $model->id ?>" name="progress" aria-label="Default select example">
                                                <option value="1" <?= $model->progress == 1 ? 'selected' : '' ?>>Hold</option>
                                                <option value="2" <?= $model->progress == 2 ? 'selected' : '' ?>>In Progress</option>
                                                <option value="3" <?= $model->progress == 3 ? 'selected' : '' ?>>Complete</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="taskDescription<?= $model->id ?>">Task Description</label>
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
            </tbody>
        </table>
    <?php endif; ?>
</div>