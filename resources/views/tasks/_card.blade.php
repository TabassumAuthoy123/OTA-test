<div class="task-card">
    <div class="task-title">{{ $task->title }}</div>
    @if($task->description)
        <div class="task-desc">{{ $task->description }}</div>
    @endif
    <div class="task-meta">
        {!! $task->category_badge !!}
        {!! $task->priority_badge !!}
    </div>
    <div class="task-actions">
        @if($prevStatus !== null)
            <button class="btn btn-outline-secondary btn-sm" onclick="updateTaskStatus({{ $task->id }}, {{ $prevStatus }})">
                {{ $prevLabel }}
            </button>
        @endif
        @if($nextStatus !== null)
            <button class="btn btn-outline-primary btn-sm" onclick="updateTaskStatus({{ $task->id }}, {{ $nextStatus }})">
                {{ $nextLabel }}
            </button>
        @endif
        <button class="btn btn-outline-warning btn-sm ms-auto"
            onclick="editTask({{ $task->id }}, '{{ addslashes($task->title) }}', '{{ addslashes($task->description) }}', '{{ $task->category }}', '{{ $task->priority }}')">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-outline-danger btn-sm" onclick="deleteTask({{ $task->id }})">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</div>