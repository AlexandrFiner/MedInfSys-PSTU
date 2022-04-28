<?php

use App\Models\Department;
use App\Models\DepartmentRoom;
use App\Models\HospitalAppointment;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\ProfileDoctors;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(HospitalAppointment::class, function (ModelConfiguration $model) {
    $model->setTitle('Больница');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();
        $display->setNewEntryButtonText('Запись пациента');

        $display->setColumnFilters([
            null,

            AdminColumnFilter::select()
                ->setModelForOptions(Patient::class, 'name')
                ->setColumnName('patient_id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),
            AdminColumnFilter::select([
                'process' => 'Проходит лечение',
                'released' => 'Выписан',
            ], 'Пол')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),
            AdminColumnFilter::select()
                ->setModelForOptions(Doctor::class, 'name')
                ->setColumnName('doctor_id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),
            AdminColumnFilter::select()
                ->setModelForOptions(ProfileDoctors::class, 'name')
                ->setColumnName('doctor.profile_doctors_id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),
            AdminColumnFilter::select()
                ->setModelForOptions(Hospital::class, 'name')
                ->setColumnName('hospital.id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),
            null,

            null,
            /*AdminColumnFilter::select()
                ->setModelForOptions(DepartmentRoom::class, 'name')
                ->setColumnName('profile.id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),*/

            AdminColumnFilter::range()->setFrom(
                AdminColumnFilter::date()
                    ->setPlaceholder('От')
                    ->setFormat('Y-m-d')
            )->setTo(
                AdminColumnFilter::date()
                    ->setPlaceholder('До')
                    ->setFormat('Y-m-d')
            )
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::range()->setFrom(
                AdminColumnFilter::date()
                    ->setPlaceholder('От')
                    ->setFormat('Y-m-d')
            )->setTo(
                AdminColumnFilter::date()
                    ->setPlaceholder('До')
                    ->setFormat('Y-m-d')
            )
                ->setHtmlAttribute('style', 'width: 100%'),
        ]);

        $display->getColumnFilters()->setPlacement('table.header');
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('patient.name', null, 'description')->setLabel('Пациент'),
            AdminColumn::custom('status', function($appointment) {
                return match ($appointment['status']) {
                    'process' => '<small class="badge badge-warning">Проходит лечение</small>',
                    'released' => '<small class="badge badge-success">Выписан</small>',
                };
            })->setLabel('Статус'),
            AdminColumn::text('doctor.name')->setLabel('Лечащий врач'),
            AdminColumn::text('doctor.profile.name')->setLabel('Профиль врача'),
            AdminColumn::text('hospital.name')->setLabel('Больница'),
            AdminColumn::text('department.name')->setLabel('Отделение'),
            AdminColumn::text('department_room.name')->setLabel('Палата'),
            AdminColumn::datetime('date_in')->setFormat('Y-m-d')->setLabel('Дата поступления'),
            AdminColumn::datetime('date_out')->setFormat('Y-m-d')->setLabel('Дата выписки'),
        ]);

        $display->paginate(15);
        return $display;
    });

    $model->onCreate(function () {
        $form = AdminForm::panel();

        $form->addBody([
            AdminFormElement::select('patient_id', 'Пациент', Patient::class)
                ->setDisplay('name')
                ->required(),

            AdminFormElement::dependentselect('hospital_id', 'Больница', ['patient_id'])
                ->setModelForOptions( Hospital::class, 'name' )
                ->setDisplay('name')
                ->setHtmlAttribute('placeholder', 'Укажите пациента')
                ->setDataDepends(['patient_id'])
                ->setLoadOptionsQueryPreparer(function($element, $query) {
                    $patient = Patient::find($element->getDependValue('patient_id'));
                    if($patient) {
                        $polyclinic = Polyclinic::find($patient->polyclinic_id ?? 0);
                        $countHospital = Hospital::where('id', $polyclinic->hospital_id ?? 0)->count();
                        if ($countHospital)
                            return $query->where('id', $polyclinic->hospital_id ?? 0);

                        return $query;
                    }
                    return $query->where('id', 0);
                })
                ->required(),

            AdminFormElement::dependentselect('department_id', 'Отделение', ['hospital_id'])
                ->setModelForOptions( Department::class, 'name' )
                ->setDisplay('name')
                ->setHtmlAttribute('placeholder', 'Укажите больницу')
                ->setDataDepends(['hospital_id'])
                ->setLoadOptionsQueryPreparer(function($element, $query) {
                    return $query->where('hospital_id', $element->getDependValue('hospital_id') ?? 0);
                })
                ->required(),

            AdminFormElement::dependentselect('doctor_id', 'Лечащий врач', ['hospital_id'])
                ->setModelForOptions( Doctor::class, 'name' )
                ->setDisplay('name')
                ->setHtmlAttribute('placeholder', 'Укажите больницу')
                ->setDataDepends(['hospital_id'])
                ->setLoadOptionsQueryPreparer(function($element, $query) {
                    $doctorHospitals = DB::table('doctor_hospitals')->where('hospital_id', $element->getDependValue('hospital_id'))->get();
                    $doctorsList = [];
                    foreach ($doctorHospitals as $doctor) {
                        $doctorsList[] = $doctor->doctor_id;
                    }
                    return $query->whereIn('id', $doctorsList);
                })
                ->required(),

            AdminFormElement::text('description', 'Описание'),
        ]);
        return $form;
    });
});
