<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use Illuminate\Http\Request;

class AthleteController extends Controller
{
    public function index(Request $request)
    {
        $athletes = Athlete::withCount('registrations')
            ->when($request->q, fn ($query, $q) => $query->where(fn ($w) =>
                $w->where('name', 'like', "%$q%")
                  ->orWhere('code', 'like', "%$q%")
                  ->orWhere('state', 'like', "%$q%")
            ))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.athletes.index', [
            'athletes' => $athletes,
            'filters' => $request->only('q'),
        ]);
    }
}
