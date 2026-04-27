<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\PemetaanAkademik;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function bulk(Request $request)
    {
        $data_kelas    = Kelas::orderBy('nama')->get();
        $data_pemetaan = PemetaanAkademik::with(['kelas', 'mataPelajaran', 'guru'])->get();

        return view('admin.schedules_bulk', [
            'data_kelas'    => $data_kelas,
            'data_pemetaan' => $data_pemetaan,
        ]);
    }

    public function index(Request $request)
    {
        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $data_kelas = Kelas::orderBy('nama')->get();
        $data_kelas = Kelas::orderBy('nama')->get();

        $selectedKelasId = $request->get('kelas_id');

        $jadwalQuery = Jadwal::with(['pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran', 'pemetaanAkademik.guru'])
            ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_mulai');

        if ($selectedKelasId) {
            $jadwalQuery->whereHas('pemetaanAkademik', fn($q) => $q->where('id_kelas', $selectedKelasId));
        }

        $jadwal = $jadwalQuery->get();

        // Group by hari for weekly table view
        $jadwalByHari = $jadwal->groupBy('hari');

        return view('admin.schedules', [
            'data_jadwal'    => $jadwal,
            'jadwal_by_hari' => $jadwalByHari,
            'hari_list'      => $hari_list,
            'hari_list'      => $hari_list,
            'data_kelas'     => $data_kelas,
            'selected_kelas' => $selectedKelasId,
        ]);
    }



    public function storeBulk(Request $request)
    {
        $request->validate([
            'id_kelas'  => 'required|exists:kelas,id',
            'jadwal'    => 'required|array',
            'jadwal.*'  => 'array',
        ]);

        $hariValid = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $toInsert  = [];
        $errors    = [];

        foreach ($request->jadwal as $hari => $barisList) {
            if (!in_array($hari, $hariValid) || !is_array($barisList)) continue;

            foreach ($barisList as $idx => $baris) {
                $pemetaanId = $baris['id_pemetaan_akademik'] ?? null;
                $jamMulai   = $baris['jam_mulai'] ?? null;
                $jamSelesai = $baris['jam_selesai'] ?? null;

                // Skip baris kosong
                if (empty($pemetaanId) || empty($jamMulai) || empty($jamSelesai)) continue;

                // Validasi jam
                if ($jamSelesai <= $jamMulai) {
                    $errors[] = "Baris {$hari} ke-" . ($idx + 1) . ": Jam selesai harus lebih dari jam mulai.";
                    continue;
                }

                $pemetaan = PemetaanAkademik::find($pemetaanId);
                if (!$pemetaan) {
                    $errors[] = "Baris {$hari} ke-" . ($idx + 1) . ": Pemetaan tidak ditemukan.";
                    continue;
                }

                // Cek bentrok kelas (terhadap jadwal yang sudah ada di DB)
                $bentrokKelas = Jadwal::whereHas('pemetaanAkademik', fn($q) => $q->where('id_kelas', $pemetaan->id_kelas))
                    ->where('hari', $hari)
                    ->where(fn($q) => $q->where('jam_mulai', '<', $jamSelesai)->where('jam_selesai', '>', $jamMulai))
                    ->with('pemetaanAkademik.mataPelajaran')
                    ->first();

                if ($bentrokKelas) {
                    $nm = $bentrokKelas->pemetaanAkademik->mataPelajaran->nama ?? '-';
                    $errors[] = "⚠️ {$hari} baris ke-" . ($idx + 1) . ": Bentrok jadwal kelas dengan \"{$nm}\" ("
                        . substr($bentrokKelas->jam_mulai, 0, 5) . "–" . substr($bentrokKelas->jam_selesai, 0, 5) . ").";
                    continue;
                }

                // Cek bentrok guru (terhadap jadwal yang sudah ada di DB)
                $bentrokGuru = Jadwal::whereHas('pemetaanAkademik', fn($q) => $q->where('id_guru', $pemetaan->id_guru))
                    ->where('hari', $hari)
                    ->where(fn($q) => $q->where('jam_mulai', '<', $jamSelesai)->where('jam_selesai', '>', $jamMulai))
                    ->with(['pemetaanAkademik.mataPelajaran', 'pemetaanAkademik.kelas'])
                    ->first();

                if ($bentrokGuru) {
                    $nm    = $bentrokGuru->pemetaanAkademik->mataPelajaran->nama ?? '-';
                    $kls   = $bentrokGuru->pemetaanAkademik->kelas->nama ?? '-';
                    $guru  = $pemetaan->guru->nama ?? '-';
                    $errors[] = "⚠️ {$hari} baris ke-" . ($idx + 1) . ": Guru \"{$guru}\" bentrok mengajar \"{$nm}\" di kelas {$kls} ("
                        . substr($bentrokGuru->jam_mulai, 0, 5) . "–" . substr($bentrokGuru->jam_selesai, 0, 5) . ").";
                    continue;
                }

                // Cek bentrok antar baris yang baru diinput (dalam sesi submit ini)
                foreach ($toInsert as $existing) {
                    if ($existing['hari'] !== $hari) continue;
                    $sameKelas = ($existing['id_kelas'] == $pemetaan->id_kelas);
                    $sameGuru  = ($existing['id_guru']  == $pemetaan->id_guru);
                    $overlap   = ($jamMulai < $existing['jam_selesai'] && $jamSelesai > $existing['jam_mulai']);
                    if ($overlap && $sameKelas) {
                        $errors[] = "⚠️ {$hari} baris ke-" . ($idx + 1) . ": Bentrok waktu dengan mapel lain di kelas yang sama pada form ini.";
                        continue 2;
                    }
                    if ($overlap && $sameGuru) {
                        $guru = $pemetaan->guru->nama ?? '-';
                        $errors[] = "⚠️ {$hari} baris ke-" . ($idx + 1) . ": Guru \"{$guru}\" dijadwalkan dua kali di waktu yang sama.";
                        continue 2;
                    }
                }

                $toInsert[] = [
                    'id_pemetaan_akademik' => $pemetaanId,
                    'hari'                 => $hari,
                    'jam_mulai'            => $jamMulai,
                    'jam_selesai'          => $jamSelesai,
                    'id_kelas'             => $pemetaan->id_kelas,
                    'id_guru'              => $pemetaan->id_guru,
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ];
            }
        }

        if (!empty($errors)) {
            return back()->withErrors(['bulk' => implode("\n", $errors)])->withInput();
        }

        if (empty($toInsert)) {
            return back()->withErrors(['bulk' => 'Tidak ada jadwal yang valid untuk disimpan. Pastikan semua baris sudah diisi.'])->withInput();
        }

        // Hapus kolom tambahan sebelum insert
        $insertData = array_map(fn($r) => [
            'id_pemetaan_akademik' => $r['id_pemetaan_akademik'],
            'hari'                 => $r['hari'],
            'jam_mulai'            => $r['jam_mulai'],
            'jam_selesai'          => $r['jam_selesai'],
            'created_at'           => $r['created_at'],
            'updated_at'           => $r['updated_at'],
        ], $toInsert);

        Jadwal::insert($insertData);

        $total = count($insertData);
        return redirect()->route('admin.schedules.index', ['kelas_id' => $request->id_kelas])
            ->with('success', "✅ {$total} jadwal berhasil disimpan sekaligus!");
    }



    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $kelasId = $jadwal->pemetaanAkademik?->id_kelas;
        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus!');
    }
}
