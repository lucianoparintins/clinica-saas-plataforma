<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Notifications\AppointmentCanceledNotification;
use App\Notifications\AppointmentScheduledNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('appointments/Index', [
            'appointments' => Appointment::with(['doctor', 'patient'])
                ->latest('scheduled_at')
                ->paginate(10)
                ->through(fn ($appointment) => [
                    'id' => $appointment->id,
                    'doctor_name' => $appointment->doctor->name,
                    'patient_name' => $appointment->patient->name,
                    'scheduled_at' => $appointment->scheduled_at->format('d/m/Y H:i'),
                    'status' => $appointment->status,
                    'notes' => $appointment->notes,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $doctors = Cache::remember('doctors_list', 86400, function () {
            return Doctor::all(['id', 'name']);
        });

        $patients = Cache::remember('patients_list', 86400, function () {
            return Patient::all(['id', 'name']);
        });

        return Inertia::render('appointments/Edit', [
            'doctors' => $doctors,
            'patients' => $patients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $appointment = Auth::user()->appointments()->create($request->validated());

        $appointment->patient->notify(new AppointmentScheduledNotification($appointment));

        Cache::forget('dashboard_stats');

        return to_route('appointments.index')->with('status', 'Consulta agendada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment): Response
    {
        $appointment->load(['doctor', 'patient', 'user']);

        return Inertia::render('appointments/Show', [
            'appointment' => [
                'id' => $appointment->id,
                'doctor' => $appointment->doctor,
                'patient' => $appointment->patient,
                'user' => $appointment->user->name,
                'scheduled_at' => $appointment->scheduled_at->format('d/m/Y H:i'),
                'status' => $appointment->status,
                'notes' => $appointment->notes,
                'created_at' => $appointment->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment): Response
    {
        $doctors = Cache::remember('doctors_list', 86400, function () {
            return Doctor::all(['id', 'name']);
        });

        $patients = Cache::remember('patients_list', 86400, function () {
            return Patient::all(['id', 'name']);
        });

        return Inertia::render('appointments/Edit', [
            'appointment' => $appointment,
            'doctors' => $doctors,
            'patients' => $patients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $appointment->update($request->validated());

        Cache::forget('dashboard_stats');

        return to_route('appointments.index')->with('status', 'Consulta atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->patient->notify(new AppointmentCanceledNotification($appointment));

        $appointment->delete();

        Cache::forget('dashboard_stats');

        return to_route('appointments.index')->with('status', 'Agendamento removido com sucesso!');
    }
}
