<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TimesheetEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TimesheetController extends Controller
{
    public function lastStatus()
    {
        $lastEntry = TimesheetEntry::where('user_id', Auth::id())
            ->latest('date')
            ->latest('time')
            ->first();

        $isCheckedIn = false;
        $combinedDateTime = null;

        if ($lastEntry) {
            $combinedDateTime = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $lastEntry->date->format('Y-m-d') . ' ' . $lastEntry->time
            );

            $isToday = $combinedDateTime->isToday();
            $isCheckedIn = $isToday && $lastEntry->type === 'check_in';
            $lastEntry->combined_datetime = $combinedDateTime;
        }

        return response()->json([
            'isCheckedIn' => $isCheckedIn,
            'lastEntry' => $lastEntry
        ]);
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $entry = TimesheetEntry::create([
            'user_id' => Auth::id(),
            'date' => Carbon::now()->toDateString(),
            'time' => Carbon::now()->toTimeString(),
            'type' => 'check_in',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'notes' => $request->notes ?? null,
        ]);

        return response()->json([
            'message' => 'Check-in successful',
            'entry' => $entry,
        ]);
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $lastCheckIn = TimesheetEntry::where('user_id', Auth::id())
            ->where('type', 'check_in')
            ->whereDate('date', Carbon::today())
            ->latest('time')
            ->first();

        if (!$lastCheckIn) {
            return response()->json([
                'message' => 'No active check-in found for today',
            ], 400);
        }

        $entry = TimesheetEntry::create([
            'user_id' => Auth::id(),
            'date' => Carbon::now()->toDateString(),
            'time' => Carbon::now()->toTimeString(),
            'type' => 'check_out',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'notes' => $request->notes ?? null,
        ]);

        return response()->json([
            'message' => 'Check-out successful',
            'entry' => $entry,
            'duration' => $entry->duration,
        ]);
    }

    public function getEntries(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $entries = TimesheetEntry::where('user_id', Auth::id())
            ->whereDate('date', $request->date)
            ->orderBy('time')
            ->get();

        return response()->json([
            'entries' => $entries,
        ]);
    }

    public function getEmployeeDayEntries(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $employeeId = $request->employee_id;
        $date = $request->date;

        $entries = TimesheetEntry::where('user_id', $employeeId)
            ->whereDate('date', $date)
            ->orderBy('time')
            ->get();

        $formattedEntries = $entries->map(function ($entry) {
            return [
                'id' => $entry->id,
                'type' => $entry->type,
                'time' => $entry->time,
                'notes' => $entry->notes,
                'seconds_worked' => $entry->seconds_worked,
            ];
        });

        return response()->json([
            'entries' => $formattedEntries,
        ]);
    }

    public function getMonthEntries(Request $request, $year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $entries = TimesheetEntry::select('date')
            ->selectRaw('SUM(
                CASE
                    WHEN type = "check_out" THEN
                        TIME_TO_SEC(TIMEDIFF(time, (
                            SELECT time
                            FROM timesheet_entries AS t2
                            WHERE t2.user_id = timesheet_entries.user_id
                                AND t2.date = timesheet_entries.date
                                AND t2.type = "check_in"
                                AND t2.time < timesheet_entries.time
                            ORDER BY t2.time DESC
                            LIMIT 1
                        )))
                    ELSE 0
                END
            ) as total_seconds')
            ->where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $transformedEntries = $entries->map(function ($entry) {
            $hours = floor($entry->total_seconds / 3600);
            $minutes = floor(($entry->total_seconds % 3600) / 60);

            return [
                'date' => $entry->date->format('Y-m-d'),
                'total_hours' => sprintf('%02d:%02d', $hours, $minutes),
                'has_entries' => $entry->total_seconds > 0,
            ];
        });

        return response()->json([
            'entries' => $transformedEntries,
        ]);
    }

    public function getEmployeesMonthEntries(Request $request, $year, $month)
    {
        $request->validate([
            'employee_id' => 'required|string',
        ]);

        $employeeId = $request->employee_id;

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $entries = TimesheetEntry::select('user_id', 'date')
            ->selectRaw('SUM(
                    CASE
                        WHEN type = "check_out" THEN
                            TIME_TO_SEC(TIMEDIFF(time, (
                                SELECT time
                                FROM timesheet_entries AS t2
                                WHERE t2.user_id = timesheet_entries.user_id
                                    AND t2.date = timesheet_entries.date
                                    AND t2.type = "check_in"
                                    AND t2.time < timesheet_entries.time
                                ORDER BY t2.time DESC
                                LIMIT 1
                            )))
                        ELSE 0
                    END
                ) as total_seconds')
            ->where('user_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('user_id', 'date')
            ->orderBy('date')
            ->get();

        $transformedEntries = $entries->map(function ($entry) {
            $hours = floor($entry->total_seconds / 3600);
            $minutes = floor(($entry->total_seconds % 3600) / 60);

            return [
                'date' => $entry->date,
                'total_hours' => sprintf('%02d:%02d', $hours, $minutes),
                'has_entries' => $entry->total_seconds > 0,
            ];
        });

        return response()->json([
            'entries' => $transformedEntries,
        ]);
    }
}
