<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    $courses = [
        ['name' => 'web Development',
         'description'=> "Learn web development from scratch."],

        ['name' => 'Mobile Development',
        'description'=> "Learn mobile development from scratch."],

        ['name' => 'Quality Assurance',
        'description'=> "Learn quality assurance from scratch."],

        ['name' => 'Data Science',
        'description'=> "Learn data science from scratch."],
    
        ['name' => 'Project Manangement',
        'description'=> "Learn project manangement from scratch."],
    
        ['name' => 'Cyber Security',
        'description'=> "Learn cyber security from scratch."],
    
    ];

    foreach ($courses as $course) {
        Course::create($course);
    }
   }
}

