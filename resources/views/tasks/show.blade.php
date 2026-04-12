@extends('master')

@section('header_css')
    <style>
        .task-detail-wrapper {
            max-width: 900px;
            margin: 0 auto;
        }

        .task-detail-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            gap: 16px;
        }

        .task-detail-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6c757d;
            text-decoration: none;
            font-size: 13px;
            margin-bottom: 16px;
            transition: color 0.2s;
        }

        .task-detail-back:hover {
            color: #0d6efd;
        }

        .task-detail-title {
            font-size: 24px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .task-detail-badges {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .task-detail-badges .badge {
            font-size: 12px;
            padding: 5px 12px;
            border-radius: 6px;
        }

        .task-detail-meta {
            display: flex;
            gap: 24px;
            align-items: center;
            flex-wrap: wrap;
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 24px;
        }

        .task-detail-meta i {
            margin-right: 4px;
        }

        .task-detail-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .task-detail-card h5 {
            font-weight: 700;
            font-size: 16px;
            color: #212529;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        .task-detail-description {
            font-size: 15px;
            line-height: 1.8;
            color: #374151;
        }

        .task-detail-description p {
            margin-bottom: 12px;
        }

        .task-detail-description ul,
        .task-detail-description ol {
            padding-left: 24px;
            margin-bottom: 12px;
        }

        .task-detail-description li {
            margin-bottom: 4px;
        }

        .task-detail-description a {
            color: #0d6efd;
        }

        .task-detail-description table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .task-detail-description table th,
        .task-detail-description table td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            font-size: 14px;
        }

        .task-detail-description table th {
            background: #f8f9fa;
        }

        .task-detail-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .task-detail-actions .btn {
            padding: 8px 20px;
            font-size: 13px;
            border-radius: 8px;
        }

        .task-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
        }

        .task-status-pill.status-todo {
            background: #f1f3f5;
            color: #6c757d;
        }

        .task-status-pill.status-in-progress {
            background: #cfe2ff;
            color: #0d6efd;
        }

        .task-status-pill.status-done {
            background: #d1e7dd;
            color: #198754;
        }

        .task-detail-empty-desc {
            color: #adb5bd;
            font-style: italic;
            padding: 24px;
            text-align: center;
            background: #f8f9fa;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="task-detail-wrapper">

        <a href="{{ url('tasks') }}" class="task-detail-back">
            <i class="fas fa-arrow-left"></i> Back to Task Board
        </a>

        <div class="task-detail-header">
            <div style="flex: 1;">
                <div class="task-detail-badges">
                    {!! $task->category_badge !!}
                    {!! $task->priority_badge !!}
                    @php
                        $statusClass = match ($task->status) {
                            0 => 'status-todo',
                            1 => 'status-in-progress',
                            2 => 'status-done',
                            default => 'status-todo',
                        };
                        $statusLabel = match ($task->status) {
                            0 => '📋 Todo',
                            1 => '🔄 In Progress',
                            2 => '✅ Done',
                            default => 'Unknown',
                        };
                    @endphp
                    <span class="task-status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>
                <h1 class="task-detail-title">{{ $task->title }}</h1>
                <div class="task-detail-meta">
                    <span><i class="fas fa-calendar"></i> Created {{ $task->created_at->format('M d, Y h:i A') }}</span>
                    <span><i class="fas fa-sync-alt"></i> Updated {{ $task->updated_at->diffForHumans() }}</span>
                    @if($task->creator)
                        <span><i class="fas fa-user"></i> {{ $task->creator->name }}</span>
                    @endif
                </div>
            </div>
            <div class="task-detail-actions">
                @if($task->status == 0)
                    <form action="{{ url('tasks/' . $task->id . '/status/1') }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-outline-primary"><i class="fas fa-play me-1"></i> Start</button>
                    </form>
                @elseif($task->status == 1)
                    <form action="{{ url('tasks/' . $task->id . '/status/2') }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-outline-success"><i class="fas fa-check me-1"></i> Done</button>
                    </form>
                    <form action="{{ url('tasks/' . $task->id . '/status/0') }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-outline-secondary"><i class="fas fa-undo me-1"></i> Back</button>
                    </form>
                @elseif($task->status == 2)
                    <form action="{{ url('tasks/' . $task->id . '/status/1') }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-outline-warning"><i class="fas fa-redo me-1"></i> Reopen</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="task-detail-card">
            <h5><i class="fas fa-align-left me-2"></i> Description</h5>
            @if($task->description)
                <div class="task-detail-description">
                    {!! $task->description !!}
                </div>
            @else
                <div class="task-detail-empty-desc">
                    No description provided. Click edit to add one.
                </div>
            @endif
        </div>

        @if(!empty($task->images) && is_array($task->images) && count($task->images) > 0)
            <div class="task-detail-card">
                <h5><i class="fas fa-images me-2"></i> Attachments ({{ count($task->images) }})</h5>
                <div class="mt-3" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-start;">
                    @foreach($task->images as $img)
                        <a href="{{ asset($img) }}" target="_blank" style="display: inline-block;">
                            <img src="{{ asset($img) }}" class="img-fluid rounded shadow-sm" style="max-height: 250px; object-fit: contain; max-width: 100%;">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection