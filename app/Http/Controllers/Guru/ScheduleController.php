<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $schedules = Schedule::with(['academicMap.class', 'academicMap.subject'])
            ->whereHas('academicMap', fn($q) => $q->where('teacher_id', Auth::id()))
            ->orderByRaw("FIELD(day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');
        return view('guru.schedules', compact('schedules', 'days'));
    }
}
