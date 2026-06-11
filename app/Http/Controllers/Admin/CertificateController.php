<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\Certificate;
use App\Models\Event;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        return view('admin.certificates.index', [
            'certificates' => Certificate::with(['athlete', 'event.sport'])->latest('issued_at')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.certificates.create', $this->formData(new Certificate(['issued_at' => now()])));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['number'] = $this->nextNumber();
        $cert = Certificate::create($data);

        return redirect()->route('admin.certificates.index')->with('flash', "Certificate {$cert->number} issued.");
    }

    public function edit(Certificate $certificate)
    {
        return view('admin.certificates.edit', $this->formData($certificate));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $certificate->update($this->validateData($request));

        return redirect()->route('admin.certificates.index')->with('flash', "Certificate {$certificate->number} updated.");
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.index')->with('flash', 'Certificate deleted.');
    }

    private function formData(Certificate $certificate): array
    {
        return [
            'certificate' => $certificate,
            'athletes' => Athlete::orderBy('name')->get(),
            'events' => Event::with('sport')->orderBy('name')->get(),
        ];
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'athlete_id' => ['required', 'exists:athletes,id'],
            'event_id' => ['required', 'exists:events,id'],
            'type' => ['required', 'in:participation,winner,runner_up,bronze'],
            'title' => ['required', 'string', 'max:160'],
            'issued_at' => ['required', 'date'],
        ]);
    }

    private function nextNumber(): string
    {
        $last = Certificate::where('number', 'like', 'ISL-CERT-2026-%')
            ->orderByDesc('number')
            ->value('number');
        $n = $last ? ((int) substr($last, -5)) + 1 : 801;

        return 'ISL-CERT-2026-'.str_pad((string) $n, 5, '0', STR_PAD_LEFT);
    }
}
