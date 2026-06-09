<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        return view('public.certificates.index', [
            'recent' => Certificate::with(['athlete', 'event'])->latest('issued_at')->take(5)->get(),
        ]);
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'number' => ['required', 'string'],
        ]);

        $certificate = Certificate::with(['athlete', 'event.sport'])
            ->where('number', trim($data['number']))
            ->first();

        if (! $certificate) {
            return redirect()->route('certificates.index')
                ->withInput()
                ->with('verify_error', "No certificate found for “{$data['number']}”. Check the code and try again.");
        }

        return redirect()->route('certificates.show', $certificate);
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['athlete', 'event.sport']);

        return view('public.certificates.show', [
            'certificate' => $certificate,
        ]);
    }
}
