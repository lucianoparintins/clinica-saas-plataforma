<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('doctors/Index', [
            'doctors' => Doctor::query()
                ->latest()
                ->paginate(10)
                ->through(fn ($doctor) => [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'email' => $doctor->email,
                    'crm' => $doctor->crm,
                    'specialty' => $doctor->specialty,
                    'phone' => $doctor->phone,
                    'created_at' => $doctor->created_at->format('d/m/Y'),
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('doctors/Edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorRequest $request): RedirectResponse
    {
        Doctor::create($request->validated());

        Cache::forget('doctors_list');

        return to_route('doctors.index')->with('status', 'Médico cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor): Response
    {
        return Inertia::render('doctors/Show', [
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'crm' => $doctor->crm,
                'specialty' => $doctor->specialty,
                'phone' => $doctor->phone,
                'address' => $doctor->address,
                'created_at' => $doctor->created_at->format('d/m/Y'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor): Response
    {
        return Inertia::render('doctors/Edit', [
            'doctor' => $doctor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorRequest $request, Doctor $doctor): RedirectResponse
    {
        $doctor->update($request->validated());

        Cache::forget('doctors_list');

        return to_route('doctors.index')->with('status', 'Médico atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor): RedirectResponse
    {
        $doctor->delete();

        Cache::forget('doctors_list');

        return to_route('doctors.index')->with('status', 'Médico excluído com sucesso!');
    }
}
