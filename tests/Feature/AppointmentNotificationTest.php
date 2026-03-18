<?php

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\AppointmentScheduledNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

test('it notifies the patient when an appointment is scheduled', function () {
    Notification::fake();

    $user = User::factory()->create();
    $doctor = Doctor::factory()->create();
    $patient = Patient::factory()->create();

    $appointmentData = [
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'scheduled_at' => now()->addDay()->format('Y-m-d H:i:s'),
        'status' => 'pending',
        'notes' => 'Test appointment notes',
    ];

    $response = $this->actingAs($user)
        ->post(route('appointments.store'), $appointmentData);

    $response->assertRedirect(route('appointments.index'));

    $appointment = Appointment::first();

    Notification::assertSentTo(
        $patient,
        AppointmentScheduledNotification::class,
        function ($notification) use ($appointment) {
            return $notification->appointment->id === $appointment->id;
        }
    );
});

test('notification implements ShouldQueue to use Redis', function () {
    $doctor = Doctor::factory()->create();
    $patient = Patient::factory()->create();
    $appointment = Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
    ]);

    $notification = new AppointmentScheduledNotification($appointment);

    expect($notification)->toBeInstanceOf(ShouldQueue::class);
});
