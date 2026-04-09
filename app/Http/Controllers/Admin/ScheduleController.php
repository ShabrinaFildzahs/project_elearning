<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\AcademicMap;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['academicMap.class', 'academicMap.subject', 'academicMap.teacher'])
            ->orderByRaw("FIELD(day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('start_time')
            ->get();
        $academicMaps = AcademicMap::with(['class', 'subject', 'teacher'])->get();
        return view('admin.schedules', compact('schedules', 'academicMaps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_map_id' => 'required|exists:academic_maps,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        Schedule::create($request->only('academic_map_id', 'day', 'start_time', 'end_time'));
        return back()->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'academic_map_id' => 'required|exists:academic_maps,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $schedule->update($request->only('academic_map_id', 'day', 'start_time', 'end_time'));
        return back()->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Jadwal berhasil dihapus!');
    }
}
