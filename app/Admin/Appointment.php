<?php

use App\Models\Appointment;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Polyclinic;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Appointment::class, function (ModelConfiguration $model) {
    $model->setTitle('Запись');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('patient.name')->setLabel('Пациент'),
            AdminColumn::text('name')->setLabel('Описание'),
            AdminColumn::text('doctor.name')->setLabel('Доктор'),
            AdminColumn::text('hospital.name')->setLabel('Больница'),
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

            /*
            AdminFormElement::dependentselect('hospital_id', 'Больница', ['patient_id'])
                ->setModelForOptions( Hospital::class, 'name' )
                ->setDisplay('name')
                ->setHtmlAttribute('placeholder', 'Укажите пациента')
                ->setDataDepends(['patient_id'])
                ->setLoadOptionsQueryPreparer(function($element, $query) {
                    $patient = Patient::find($element->getDependValue('patient_id'));
                    $patient = Patient::find(1);
                    return $query->where('id', $patient->hospital_id ?? 0);
                })
                ->required(),
            */
            AdminFormElement::dependentselect('hospital_id', 'Больница', ['patient_id'])
                ->setModelForOptions( Hospital::class, 'name' )
                ->setDisplay('name')
                ->setHtmlAttribute('placeholder', 'Укажите пациента')
                ->setDataDepends(['patient_id'])
                ->setLoadOptionsQueryPreparer(function($element, $query) {
                    /*
                    $patient = Patient::find($element->getDependValue('patient_id'));
                    return $query->where('polyclinic_id', $patient->polyclinic_id ?? 0);
                    */
                    $patient = Patient::find($element->getDependValue('patient_id'));
                    $polyclinic = Polyclinic::find($patient->polyclinic_id ?? 0);
                    $countHospital = Hospital::where('id', $polyclinic->hospital_id ?? 0)->count();
                    if($countHospital)
                        return $query->where('id', $polyclinic->hospital_id ?? 0);
                    else
                        return $query;
                    // return $query->where;
                })
                ->required(),

            /*
            AdminFormElement::dependentselect('doctor_id', 'Доктор', ['hospital_id'])
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
            */
            AdminFormElement::text('name', 'Запись')->required(),
        ]);
        return $form;
    });
})
    ->addMenuPage(Appointment::class, 1)
    ->setIcon('fa fa-user');
