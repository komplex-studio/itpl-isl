<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;

class CertificateController extends Controller
{
    public function index()
    {
        return view('admin.certificates.index', [
            'certificates' => Certificate::with(['athlete', 'event.sport'])->latest('issued_at')->paginate(20),
        ]);
    }
}
