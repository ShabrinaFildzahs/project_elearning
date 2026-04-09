<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        // Students need to be linked to a class — for now, show all schedules or link via a student_class table
        // For simplicity: show schedules — you can extend to per-class later
        $schedules = Schedule::with(['academicMap.class', 'academicMap.subject', 'academicMap.teacher'])
            ->orderByRaw("FIELD(day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');
        return view('siswa.schedules', compact('schedules', 'days'));
    }
}
