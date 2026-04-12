<div class="task-card">
    <div class="task-title">
        <a href="{{ url('tasks/' . $task->id) }}">{{ $task->title }}</a>
    </div>
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
        <button class="btn btn-outline-warning btn-sm ms-auto" data-task-id="{{ $task->id }}"
            data-task-title="{{ $task->title }}" data-task-description="{{ e($task->description) }}"
            data-task-category="{{ $task->category }}" data-task-priority="{{ $task->priority }}"
            data-task-images="{{ json_encode($task->images ?? []) }}"
            onclick="editTaskFromData(this)">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-outline-danger btn-sm" onclick="deleteTask({{ $task->id }})">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</div>