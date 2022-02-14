<?php

use App\Models\Doctor;
use App\Models\HospitalAppointment;
use App\Models\PolyclinicAppointment;
use App\Models\ProfileDoctors;
use App\Models\ProfileLaboratories;
use App\Models\Worker;
use SleepingOwl\Admin\Navigation\Page;

return [
    [
        'title' => 'Запись пациента',
        'icon' => 'fa fa-user',
        'priority' =>'1000',
        'pages' => [
            (new Page(HospitalAppointment::class))
                ->setIcon('fa fa-user'),
            (new Page(PolyclinicAppointment::class))
                ->setIcon('fa fa-user'),
        ]
    ],
    [
        'title' => 'Профили',
        'icon' => 'fa fa-user',
        'priority' =>'1000',
        'pages' => [
            (new Page(ProfileLaboratories::class))
                ->setIcon('fa fa-user'),
            (new Page(ProfileDoctors::class))
                ->setIcon('fa fa-user'),
        ]
    ],
    [
        'title' => 'Работники',
        'icon' => 'fa fa-user',
        'priority' =>'1000',
        'pages' => [
            (new Page(Doctor::class))
                ->setIcon('fa fa-user'),
            (new Page(Worker::class))
                ->setIcon('fa fa-user'),
        ]
    ],
];
