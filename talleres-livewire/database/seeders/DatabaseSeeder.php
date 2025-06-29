<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workshop;
use App\Models\Task;
use App\Models\WorkshopEnrollment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create student users
        $students = [];
        for ($i = 1; $i <= 5; $i++) {
            $students[] = User::create([
                'name' => "Estudiante $i",
                'email' => "student$i@test.com",
                'password' => bcrypt('password'),
                'role' => 'student',
                'student_id' => "EST00$i",
            ]);
        }

        // Create main student for testing
        $mainStudent = User::create([
            'name' => 'Juan Pérez',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'student_id' => 'EST001',
        ]);

        // Create workshops
        $workshops = [
            [
                'name' => 'Taller de Programación Web',
                'description' => 'Aprende a desarrollar aplicaciones web modernas con HTML, CSS, JavaScript y frameworks actuales.',
                'instructor' => 'Prof. Ana García',
                'capacity' => 25,
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(67),
                'location' => 'Laboratorio de Cómputo A',
                'status' => 'active',
            ],
            [
                'name' => 'Taller de Diseño Gráfico',
                'description' => 'Desarrolla habilidades en diseño visual utilizando herramientas profesionales como Photoshop e Illustrator.',
                'instructor' => 'Prof. Carlos Mendoza',
                'capacity' => 20,
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(74),
                'location' => 'Aula de Diseño',
                'status' => 'active',
            ],
            [
                'name' => 'Taller de Fotografía Digital',
                'description' => 'Domina las técnicas de fotografía digital y edición de imágenes para crear contenido visual impactante.',
                'instructor' => 'Prof. María López',
                'capacity' => 15,
                'start_date' => now()->addDays(21),
                'end_date' => now()->addDays(81),
                'location' => 'Estudio Fotográfico',
                'status' => 'active',
            ],
            [
                'name' => 'Taller de Robótica',
                'description' => 'Construye y programa robots utilizando Arduino y sensores para automatización.',
                'instructor' => 'Prof. Roberto Silva',
                'capacity' => 12,
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(90),
                'location' => 'Laboratorio de Robótica',
                'status' => 'active',
            ],
        ];

        $createdWorkshops = [];
        foreach ($workshops as $workshopData) {
            $createdWorkshops[] = Workshop::create($workshopData);
        }

        // Enroll students in workshops
        foreach ($createdWorkshops as $workshop) {
            // Enroll main student in all workshops
            WorkshopEnrollment::create([
                'user_id' => $mainStudent->id,
                'workshop_id' => $workshop->id,
                'enrollment_date' => now(),
                'status' => 'active',
            ]);

            // Enroll some random students
            $randomStudents = collect($students)->random(rand(2, 4));
            foreach ($randomStudents as $student) {
                WorkshopEnrollment::create([
                    'user_id' => $student->id,
                    'workshop_id' => $workshop->id,
                    'enrollment_date' => now(),
                    'status' => 'active',
                ]);
            }
        }

        // Create tasks for the main student
        $tasks = [
            [
                'title' => 'Crear página web personal',
                'description' => 'Desarrolla una página web personal utilizando HTML5 y CSS3. Debe incluir secciones de información personal, proyectos y contacto.',
                'workshop_id' => $createdWorkshops[0]->id,
                'assigned_to' => $mainStudent->id,
                'assigned_by' => $admin->id,
                'due_date' => now()->addDays(10),
                'priority' => 'high',
                'status' => 'pending',
            ],
            [
                'title' => 'Diseñar logo corporativo',
                'description' => 'Crear un logo profesional para una empresa ficticia. Incluir versiones en color y monocromático.',
                'workshop_id' => $createdWorkshops[1]->id,
                'assigned_to' => $mainStudent->id,
                'assigned_by' => $admin->id,
                'due_date' => now()->addDays(15),
                'priority' => 'medium',
                'status' => 'in_progress',
            ],
            [
                'title' => 'Portafolio fotográfico',
                'description' => 'Crear un portafolio con 20 fotografías editadas que demuestren diferentes técnicas aprendidas.',
                'workshop_id' => $createdWorkshops[2]->id,
                'assigned_to' => $mainStudent->id,
                'assigned_by' => $admin->id,
                'due_date' => now()->addDays(20),
                'priority' => 'medium',
                'status' => 'pending',
            ],
            [
                'title' => 'Programar robot seguidor de línea',
                'description' => 'Construir y programar un robot que pueda seguir una línea negra utilizando sensores infrarrojos.',
                'workshop_id' => $createdWorkshops[3]->id,
                'assigned_to' => $mainStudent->id,
                'assigned_by' => $admin->id,
                'due_date' => now()->addDays(35),
                'priority' => 'high',
                'status' => 'pending',
            ],
            [
                'title' => 'Tutorial JavaScript básico',
                'description' => 'Completar el tutorial interactivo de JavaScript y entregar los ejercicios resueltos.',
                'workshop_id' => $createdWorkshops[0]->id,
                'assigned_to' => $mainStudent->id,
                'assigned_by' => $admin->id,
                'due_date' => now()->addDays(5),
                'priority' => 'low',
                'status' => 'completed',
                'completed_at' => now()->subDays(1),
                'completion_notes' => 'Excelente trabajo en los ejercicios de loops y funciones.',
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::create($taskData);
        }

        // Create additional tasks for other students
        foreach ($students as $student) {
            Task::create([
                'title' => 'Ejercicios de práctica',
                'description' => 'Completar los ejercicios asignados para reforzar los conceptos aprendidos.',
                'workshop_id' => $createdWorkshops[0]->id,
                'assigned_to' => $student->id,
                'assigned_by' => $admin->id,
                'due_date' => now()->addDays(rand(5, 25)),
                'priority' => ['low', 'medium', 'high'][rand(0, 2)],
                'status' => ['pending', 'in_progress', 'completed'][rand(0, 2)],
            ]);
        }
    }
}