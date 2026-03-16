<?php

use App\Models\Doctor;
use App\Models\User;
use function Pest\Laravel\{actingAs, delete, get, post, put, assertDatabaseHas, assertDatabaseMissing};

beforeEach(function () {
    actingAs(User::factory()->create());
});

test('can list doctors', function () {
    Doctor::factory()->count(3)->create();

    $response = get(route('doctors.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('doctors/Index')
        ->has('doctors.data', 3)
    );
});

test('can show create doctor page', function () {
    $response = get(route('doctors.create'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('doctors/Edit')
    );
});

test('can store doctor', function () {
    $data = [
        'name' => 'Dr. Gregory House',
        'email' => 'house@princeton-plainsboro.com',
        'crm' => 'CRM/NJ 123456',
        'specialty' => 'Diagnóstico',
        'phone' => '555-0100',
        'address' => 'Princeton, NJ',
    ];

    $response = post(route('doctors.store'), $data);

    $response->assertRedirect(route('doctors.index'));
    assertDatabaseHas('doctors', ['email' => 'house@princeton-plainsboro.com']);
});

test('can show doctor', function () {
    $doctor = Doctor::factory()->create();

    $response = get(route('doctors.show', $doctor));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('doctors/Show')
        ->has('doctor.name')
    );
});

test('can show edit doctor page', function () {
    $doctor = Doctor::factory()->create();

    $response = get(route('doctors.edit', $doctor));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('doctors/Edit')
        ->has('doctor')
    );
});

test('can update doctor', function () {
    $doctor = Doctor::factory()->create();
    $newData = [
        'name' => 'Dr. James Wilson',
        'email' => 'wilson@princeton.com',
        'crm' => $doctor->crm,
        'specialty' => 'Oncologia',
        'phone' => '555-0200',
    ];

    $response = put(route('doctors.update', $doctor), $newData);

    $response->assertRedirect(route('doctors.index'));
    assertDatabaseHas('doctors', [
        'id' => $doctor->id,
        'name' => 'Dr. James Wilson',
        'specialty' => 'Oncologia',
    ]);
});

test('can delete doctor', function () {
    $doctor = Doctor::factory()->create();

    $response = delete(route('doctors.destroy', $doctor));

    $response->assertRedirect(route('doctors.index'));
    assertDatabaseMissing('doctors', ['id' => $doctor->id]);
});
