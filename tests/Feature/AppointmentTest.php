<?php

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    actingAs(User::factory()->create());
});

test('can list appointments', function () {
    Appointment::factory()->count(3)->create();

    get(route('appointments.index'))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('appointments/Index')
            ->has('appointments.data', 3)
        );
});

test('can show create appointment page', function () {
    Doctor::factory()->count(2)->create();
    Patient::factory()->count(2)->create();

    get(route('appointments.create'))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('appointments/Edit')
            ->has('doctors')
            ->has('patients')
        );
});

test('can store appointment', function () {
    $doctor = Doctor::factory()->create();
    $patient = Patient::factory()->create();
    $scheduledAt = now()->addDay()->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s');

    $data = [
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'scheduled_at' => $scheduledAt,
        'status' => 'pending',
        'notes' => 'Test notes',
    ];

    post(route('appointments.store'), $data)
        ->assertRedirect(route('appointments.index'));

    assertDatabaseHas('appointments', [
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'scheduled_at' => $scheduledAt,
    ]);
});

test('cannot store duplicate appointment for same doctor at same time', function () {
    $doctor = Doctor::factory()->create();
    $patient1 = Patient::factory()->create();
    $patient2 = Patient::factory()->create();
    $scheduledAt = now()->addDays(2)->setMinute(0)->setSecond(0)->format('Y-m-d H:i:s');

    Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'scheduled_at' => $scheduledAt,
        'status' => 'confirmed',
    ]);

    $data = [
        'doctor_id' => $doctor->id,
        'patient_id' => $patient2->id,
        'scheduled_at' => $scheduledAt,
        'status' => 'pending',
    ];

    post(route('appointments.store'), $data)
        ->assertSessionHasErrors(['scheduled_at']);
});

test('can show appointment', function () {
    $appointment = Appointment::factory()->create();

    get(route('appointments.show', $appointment))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('appointments/Show')
            ->has('appointment.id')
        );
});

test('can show edit appointment page', function () {
    $appointment = Appointment::factory()->create();

    get(route('appointments.edit', $appointment))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('appointments/Edit')
            ->has('appointment')
            ->has('doctors')
            ->has('patients')
        );
});

test('can update appointment', function () {
    $appointment = Appointment::factory()->create();
    $newScheduledAt = now()->addDays(3)->setMinute(30)->setSecond(0)->format('Y-m-d H:i:s');

    $newData = [
        'doctor_id' => $appointment->doctor_id,
        'patient_id' => $appointment->patient_id,
        'scheduled_at' => $newScheduledAt,
        'status' => 'confirmed',
        'notes' => 'Updated notes',
    ];

    put(route('appointments.update', $appointment), $newData)
        ->assertRedirect(route('appointments.index'));

    assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'scheduled_at' => $newScheduledAt,
        'status' => 'confirmed',
        'notes' => 'Updated notes',
    ]);
});

test('can delete appointment', function () {
    $appointment = Appointment::factory()->create();

    delete(route('appointments.destroy', $appointment))
        ->assertRedirect(route('appointments.index'));

    assertDatabaseMissing('appointments', ['id' => $appointment->id]);
});
