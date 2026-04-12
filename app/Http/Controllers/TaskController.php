<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;

class TaskController extends Controller
{
    public function index()
    {
        $todoTasks = Task::where('status', Task::STATUS_TODO)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $inProgressTasks = Task::where('status', Task::STATUS_IN_PROGRESS)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $doneTasks = Task::where('status', Task::STATUS_DONE)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('tasks.board', compact('todoTasks', 'inProgressTasks', 'doneTasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:bug,improvement,feature,idea',
            'priority' => 'required|in:low,medium,high,critical',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $fileName = time() . '-' . uniqid() . '.' . $file->extension();
                $file->move(public_path('uploads/tasks'), $fileName);
                $imagesPaths[] = 'uploads/tasks/' . $fileName;
            }
        }

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => Task::STATUS_TODO,
            'created_by' => Auth::id(),
            'images' => empty($imagesPaths) ? null : $imagesPaths,
        ]);

        Toastr::success('Task created successfully');
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:bug,improvement,feature,idea',
            'priority' => 'required|in:low,medium,high,critical',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $task = Task::findOrFail($id);

        $imagesPaths = $task->images ?? [];
        if ($request->hasFile('images')) {
            // Delete old images
            if (!empty($imagesPaths) && is_array($imagesPaths)) {
                foreach ($imagesPaths as $oldPath) {
                    if (file_exists(public_path($oldPath))) {
                        @unlink(public_path($oldPath));
                    }
                }
            }
            
            $imagesPaths = [];
            foreach ($request->file('images') as $file) {
                $fileName = time() . '-' . uniqid() . '.' . $file->extension();
                $file->move(public_path('uploads/tasks'), $fileName);
                $imagesPaths[] = 'uploads/tasks/' . $fileName;
            }
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'images' => empty($imagesPaths) ? null : $imagesPaths,
        ]);

        Toastr::success('Task updated successfully');
        return back();
    }

    public function show($id)
    {
        $task = Task::with('creator')->findOrFail($id);
        return view('tasks.show', compact('task'));
    }

    public function updateStatus($id, $status)
    {
        $task = Task::findOrFail($id);

        if (!in_array((int) $status, [Task::STATUS_TODO, Task::STATUS_IN_PROGRESS, Task::STATUS_DONE])) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Invalid status'], 400);
            }
            Toastr::error('Invalid status');
            return back();
        }

        $task->update(['status' => (int) $status]);

        if (request()->expectsJson()) {
            return response()->json(['success' => 'Status updated']);
        }

        Toastr::success('Status updated');
        return back();
    }

    public function destroy($id)
    {
        Task::findOrFail($id)->delete();
        return response()->json(['success' => 'Task deleted']);
    }

    public function seedAuditTasks()
    {
        $auditTasks = [
            // Phase 1: Critical Bug Fixes
            ['title' => 'Fix ->sve() typo in PaymentController line 476', 'description' => 'SSLCommerz success handler has $rechargeRequestInfo->sve() instead of ->save(). This crashes when payment succeeds — money gets added but status never updates.', 'category' => 'bug', 'priority' => 'critical'],
            ['title' => 'Fix swapped columns in CheckUserStatus middleware', 'description' => 'In CheckUserStatus.php, the device column stores OS value and os column stores device type. Lines 48-50 need to swap $os and $device variables.', 'category' => 'bug', 'priority' => 'critical'],
            ['title' => 'Add database indexes on frequently queried columns', 'description' => 'flight_bookings: booking_no, booked_by, status, pnr_id. flight_segments: flight_booking_id. flight_passangers: flight_booking_id. recharge_requests: user_id, slug, transaction_id. activity_logs: user_id, created_at.', 'category' => 'improvement', 'priority' => 'high'],

            // Phase 2: Security / Validation
            ['title' => 'Add input validation to all web controllers', 'description' => 'Currently zero validation in FlightBookingController, PaymentController, UserController, AuthController. All form data goes directly to DB.', 'category' => 'improvement', 'priority' => 'critical'],
            ['title' => 'Create Form Request classes for all forms', 'description' => 'Replace inline validation with dedicated FormRequest classes: BookFlightRequest, SaveBankAccountRequest, SaveUserRequest, etc.', 'category' => 'improvement', 'priority' => 'high'],
            ['title' => 'Add file upload validation (type, size, MIME)', 'description' => 'submitRechargeRequest() accepts file uploads without any type or size validation. Add validation for allowed types and max file size.', 'category' => 'bug', 'priority' => 'high'],
            ['title' => 'Add rate limiting to login and API routes', 'description' => 'No rate limiting exists on login or API endpoints, making them vulnerable to brute-force attacks.', 'category' => 'improvement', 'priority' => 'high'],
            ['title' => 'Wrap financial operations in DB transactions', 'description' => 'Booking creation (booking + segments + passengers) and payment operations should use DB::transaction() to prevent partial data.', 'category' => 'improvement', 'priority' => 'high'],

            // Phase 3: Architecture
            ['title' => 'Extract GDS logic into Service classes', 'description' => 'Move Sabre/Flyhub API calls from Models (SabreFlightBooking, FlyhubFlightBooking etc.) into proper Service classes with a GdsService interface.', 'category' => 'improvement', 'priority' => 'medium'],
            ['title' => 'Create BookingService to slim down FlightBookingController', 'description' => 'FlightBookingController is 957 lines. Extract booking logic, passenger saving, segment creation into a BookingService.', 'category' => 'improvement', 'priority' => 'medium'],
            ['title' => 'Use constants/enums for status codes', 'description' => 'Status values like 0,1,2,3,4 are hardcoded everywhere. Create enums or class constants for booking status, payment status, user type etc.', 'category' => 'improvement', 'priority' => 'medium'],
            ['title' => 'Add Eloquent relationships to Models', 'description' => 'FlightBooking model has no relationships defined. Add hasMany(FlightSegment), hasMany(FlightPassanger), belongsTo(User) etc.', 'category' => 'improvement', 'priority' => 'medium'],

            // Phase 4: Code Quality
            ['title' => 'Fix "passanger" typo everywhere', 'description' => 'The word "passenger" is misspelled as "passanger" in table names, model names, variable names throughout the codebase.', 'category' => 'improvement', 'priority' => 'low'],
            ['title' => 'Remove all commented-out debug code', 'description' => '20+ instances of echo/print_r/exit() debug code left in controllers. Remove all commented debug blocks.', 'category' => 'improvement', 'priority' => 'low'],
            ['title' => 'Add proper error handling with try-catch and logging', 'description' => 'No try-catch blocks in controllers. GDS API calls can fail silently. Add error logging throughout.', 'category' => 'improvement', 'priority' => 'medium'],
            ['title' => 'Remove dd() from production PaymentController', 'description' => 'PaymentController refund/check methods use dd() which will dump data in production. Replace with proper responses.', 'category' => 'bug', 'priority' => 'medium'],

            // Phase 5: Features
            ['title' => 'Dashboard analytics (booking stats, revenue reports)', 'description' => 'Create a proper admin dashboard with booking count, revenue charts, active agents, recent activity etc.', 'category' => 'feature', 'priority' => 'medium'],
            ['title' => 'Email notifications for booking/ticketing/recharge', 'description' => 'Send email notifications when bookings are made, tickets issued, recharge approved/denied.', 'category' => 'feature', 'priority' => 'medium'],
            ['title' => 'Booking history export (CSV/Excel)', 'description' => 'Allow admins to export booking and financial data to CSV or Excel files.', 'category' => 'feature', 'priority' => 'low'],
        ];

        foreach ($auditTasks as $index => $taskData) {
            Task::firstOrCreate(
                ['title' => $taskData['title']],
                array_merge($taskData, [
                    'status' => Task::STATUS_TODO,
                    'sort_order' => $index,
                    'created_by' => Auth::id(),
                ])
            );
        }

        Toastr::success('Audit tasks populated successfully');
        return back();
    }
}
