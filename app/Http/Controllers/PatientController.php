<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientStoreRequest;
use App\Http\Requests\PatientUpdateRequest;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('patients/Index', [
            'patients' => Patient::query()
                ->latest()
                ->paginate(10)
                ->through(fn ($patient) => [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'email' => $patient->email,
                    'cpf' => $patient->cpf,
                    'phone' => $patient->phone,
                    'created_at' => $patient->created_at->format('d/m/Y'),
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('patients/Edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientStoreRequest $request): RedirectResponse
    {
        Patient::create($request->validated());

        Cache::forget('patients_list');

        return to_route('patients.index')->with('status', 'Paciente cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient): Response
    {
        return Inertia::render('patients/Show', [
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'cpf' => $patient->cpf,
                'birth_date' => $patient->birth_date->format('d/m/Y'),
                'phone' => $patient->phone,
                'address' => $patient->address,
                'created_at' => $patient->created_at->format('d/m/Y'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient): Response
    {
        return Inertia::render('patients/Edit', [
            'patient' => $patient,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientUpdateRequest $request, Patient $patient): RedirectResponse
    {
        $patient->update($request->validated());

        Cache::forget('patients_list');

        return to_route('patients.index')->with('status', 'Paciente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient): RedirectResponse
    {
        $patient->delete();

        Cache::forget('patients_list');

        return to_route('patients.index')->with('status', 'Paciente excluído com sucesso!');
    }
}
