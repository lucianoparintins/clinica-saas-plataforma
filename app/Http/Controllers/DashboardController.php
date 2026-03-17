<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $now = Carbon::now();
        $endOfWeek = $now->copy()->addDays(7);

        $appointmentsOfWeek = Appointment::with(['doctor', 'patient'])
            ->whereBetween('scheduled_at', [$now, $endOfWeek])
            ->where('status', '!=', 'cancelled')
            ->orderBy('scheduled_at', 'asc')
            ->get()
            ->map(fn ($appointment) => [
                'id' => $appointment->id,
                'doctor_name' => $appointment->doctor->name,
                'patient_name' => $appointment->patient->name,
                'scheduled_at' => $appointment->scheduled_at->format('d/m H:i'),
                'status' => $appointment->status,
            ]);

        $recentActivities = Appointment::with(['doctor', 'patient', 'user'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn ($activity) => [
                'id' => $activity->id,
                'doctor_name' => $activity->doctor->name,
                'patient_name' => $activity->patient->name,
                'user_name' => $activity->user->name,
                'status' => $activity->status,
                'updated_at' => $activity->updated_at->diffForHumans(),
                'scheduled_at' => $activity->scheduled_at->format('d/m/Y'),
            ]);

        $stats = Cache::remember('dashboard_stats', 1800, function () {
            return [
                'total_appointments' => Appointment::count(),
                'pending_appointments' => Appointment::where('status', 'pending')->count(),
                'confirmed_appointments' => Appointment::where('status', 'confirmed')->count(),
                'cancelled_appointments' => Appointment::where('status', 'cancelled')->count(),
            ];
        });

        return Inertia::render('Dashboard', [
            'appointmentsOfWeek' => $appointmentsOfWeek,
            'recentActivities' => $recentActivities,
            'stats' => $stats,
        ]);
    }
}
