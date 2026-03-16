<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();
        $user = User::first();

        if ($doctors->isEmpty() || $patients->isEmpty() || ! $user) {
            return;
        }

        Appointment::factory(20)->create([
            'doctor_id' => fn () => $doctors->random()->id,
            'patient_id' => fn () => $patients->random()->id,
            'user_id' => $user->id,
        ]);
    }
}
