<div class="task-card">
    <div class="task-title"><?php echo e($task->title); ?></div>
    <?php if($task->description): ?>
        <div class="task-desc"><?php echo e($task->description); ?></div>
    <?php endif; ?>
    <div class="task-meta">
        <?php echo $task->category_badge; ?>

        <?php echo $task->priority_badge; ?>

    </div>
    <div class="task-actions">
        <?php if($prevStatus !== null): ?>
            <button class="btn btn-outline-secondary btn-sm" onclick="updateTaskStatus(<?php echo e($task->id); ?>, <?php echo e($prevStatus); ?>)">
                <?php echo e($prevLabel); ?>

            </button>
        <?php endif; ?>
        <?php if($nextStatus !== null): ?>
            <button class="btn btn-outline-primary btn-sm" onclick="updateTaskStatus(<?php echo e($task->id); ?>, <?php echo e($nextStatus); ?>)">
                <?php echo e($nextLabel); ?>

            </button>
        <?php endif; ?>
        <button class="btn btn-outline-warning btn-sm ms-auto"
            onclick="editTask(<?php echo e($task->id); ?>, '<?php echo e(addslashes($task->title)); ?>', '<?php echo e(addslashes($task->description)); ?>', '<?php echo e($task->category); ?>', '<?php echo e($task->priority); ?>')">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-outline-danger btn-sm" onclick="deleteTask(<?php echo e($task->id); ?>)">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</div><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/tasks/_card.blade.php ENDPATH**/ ?>