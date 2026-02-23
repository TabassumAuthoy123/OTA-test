@extends('master')

@section('header_css')
    <style>
        .kanban-wrapper {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            min-height: calc(100vh - 200px);
        }

        .kanban-column {
            flex: 1;
            min-width: 0;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 16px;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .kanban-column.todo {
            border-top: 4px solid #6c757d;
        }

        .kanban-column.in-progress {
            border-top: 4px solid #0d6efd;
        }

        .kanban-column.done {
            border-top: 4px solid #198754;
        }

        .kanban-column-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #dee2e6;
        }

        .kanban-column-header h6 {
            font-weight: 700;
            margin: 0;
            font-size: 14px;
        }

        .kanban-count {
            background: #dee2e6;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }

        .task-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            transition: all 0.2s ease;
            cursor: default;
        }

        .task-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }

        .task-card .task-title {
            font-weight: 600;
            font-size: 13.5px;
            margin-bottom: 6px;
            color: #212529;
            line-height: 1.4;
        }

        .task-card .task-desc {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 8px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .task-card .task-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .task-card .task-meta .badge {
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 4px;
        }

        .task-card .task-actions {
            display: flex;
            gap: 4px;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #f0f0f0;
        }

        .task-card .task-actions .btn {
            padding: 2px 8px;
            font-size: 11px;
            border-radius: 5px;
        }

        .add-task-btn {
            width: 100%;
            border: 2px dashed #ced4da;
            background: transparent;
            color: #6c757d;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .add-task-btn:hover {
            background: #e9ecef;
            border-color: #adb5bd;
            color: #495057;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-header h4 {
            margin: 0;
            font-weight: 700;
            color: #212529;
        }

        .stats-bar {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-item {
            background: #fff;
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            text-align: center;
            flex: 1;
        }

        .stat-item .stat-num {
            font-size: 24px;
            font-weight: 700;
        }

        .stat-item .stat-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 2px;
        }

        .stat-item.todo-stat .stat-num {
            color: #6c757d;
        }

        .stat-item.progress-stat .stat-num {
            color: #0d6efd;
        }

        .stat-item.done-stat .stat-num {
            color: #198754;
        }

        .stat-item.total-stat .stat-num {
            color: #212529;
        }

        /* Filter buttons */
        .filter-bar {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-bar .btn {
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .filter-bar .btn.active {
            font-weight: 600;
        }

        /* Fix modal form labels — override global search.css floating label styles */
        #addTaskModal .form-label,
        #editTaskModal .form-label {
            position: static !important;
            transform: none !important;
            pointer-events: auto !important;
            padding: 0 0 4px 0 !important;
            height: auto !important;
            font-size: 13px !important;
            color: #212529 !important;
            white-space: normal !important;
            z-index: auto !important;
            border: none !important;
            opacity: 1 !important;
        }

        #addTaskModal .form-control,
        #editTaskModal .form-control,
        #addTaskModal .form-select,
        #editTaskModal .form-select {
            padding: 8px 12px !important;
            height: auto !important;
        }
    </style>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h4>📋 Task Board</h4>
            <small class="text-muted">Track improvements, bugs, and feature requests</small>
        </div>
        <div class="d-flex gap-2">
            @if($todoTasks->isEmpty() && $inProgressTasks->isEmpty() && $doneTasks->isEmpty())
                <form action="{{ url('tasks/seed-audit') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-magic me-1"></i> Load Audit Tasks
                    </button>
                </form>
            @endif
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="fas fa-plus me-1"></i> New Task
            </button>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="stats-bar">
        <div class="stat-item todo-stat">
            <div class="stat-num">{{ $todoTasks->count() }}</div>
            <div class="stat-label">Todo</div>
        </div>
        <div class="stat-item progress-stat">
            <div class="stat-num">{{ $inProgressTasks->count() }}</div>
            <div class="stat-label">In Progress</div>
        </div>
        <div class="stat-item done-stat">
            <div class="stat-num">{{ $doneTasks->count() }}</div>
            <div class="stat-label">Done</div>
        </div>
        <div class="stat-item total-stat">
            <div class="stat-num">{{ $todoTasks->count() + $inProgressTasks->count() + $doneTasks->count() }}</div>
            <div class="stat-label">Total</div>
        </div>
    </div>

    <!-- Kanban Columns -->
    <div class="kanban-wrapper">

        <!-- Todo Column -->
        <div class="kanban-column todo">
            <div class="kanban-column-header">
                <h6>📋 Todo</h6>
                <span class="kanban-count">{{ $todoTasks->count() }}</span>
            </div>
            @foreach($todoTasks as $task)
                @include('tasks._card', ['task' => $task, 'prevStatus' => null, 'nextStatus' => 1, 'prevLabel' => null, 'nextLabel' => 'Start →'])
            @endforeach
            <button class="add-task-btn" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                + Add Task
            </button>
        </div>

        <!-- In Progress Column -->
        <div class="kanban-column in-progress">
            <div class="kanban-column-header">
                <h6>🔄 In Progress</h6>
                <span class="kanban-count" style="background: #cfe2ff;">{{ $inProgressTasks->count() }}</span>
            </div>
            @foreach($inProgressTasks as $task)
                @include('tasks._card', ['task' => $task, 'prevStatus' => 0, 'nextStatus' => 2, 'prevLabel' => '← Back', 'nextLabel' => 'Done ✓'])
            @endforeach
        </div>

        <!-- Done Column -->
        <div class="kanban-column done">
            <div class="kanban-column-header">
                <h6>✅ Done</h6>
                <span class="kanban-count" style="background: #d1e7dd;">{{ $doneTasks->count() }}</span>
            </div>
            @foreach($doneTasks as $task)
                @include('tasks._card', ['task' => $task, 'prevStatus' => 1, 'nextStatus' => null, 'prevLabel' => '← Reopen', 'nextLabel' => null])
            @endforeach
        </div>

    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('tasks') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Title *</label>
                            <input type="text" name="title" class="form-control" required placeholder="Enter task title...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Describe the task..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <select name="category" class="form-select">
                                    <option value="bug">🐛 Bug</option>
                                    <option value="improvement" selected>⚡ Improvement</option>
                                    <option value="feature">🌟 Feature</option>
                                    <option value="idea">💡 Idea</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <select name="priority" class="form-select">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editTaskForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Title *</label>
                            <input type="text" name="title" id="editTitle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <select name="category" id="editCategory" class="form-select">
                                    <option value="bug">🐛 Bug</option>
                                    <option value="improvement">⚡ Improvement</option>
                                    <option value="feature">🌟 Feature</option>
                                    <option value="idea">💡 Idea</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <select name="priority" id="editPriority" class="form-select">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('footer_js')
    <script>
        // Update task status via AJAX
        function updateTaskStatus(taskId, newStatus) {
            $.ajax({
                url: '{{ url("tasks") }}/' + taskId + '/status/' + newStatus,
                type: 'PATCH',
                data: { _token: '{{ csrf_token() }}' },
                success: function () { location.reload(); },
                error: function () { toastr.error('Failed to update status'); }
            });
        }

        // Delete task via AJAX
        function deleteTask(taskId) {
            if (!confirm('Are you sure you want to delete this task?')) return;
            $.ajax({
                url: '{{ url("tasks") }}/' + taskId,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function () { location.reload(); },
                error: function () { toastr.error('Failed to delete task'); }
            });
        }

        // Open edit modal with task data
        function editTask(id, title, description, category, priority) {
            $('#editTaskForm').attr('action', '{{ url("tasks") }}/' + id);
            $('#editTitle').val(title);
            $('#editDescription').val(description);
            $('#editCategory').val(category);
            $('#editPriority').val(priority);
            $('#editTaskModal').modal('show');
        }
    </script>
@endsection