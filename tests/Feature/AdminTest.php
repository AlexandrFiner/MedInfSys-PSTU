<?php

namespace Tests\Feature;

use Tests\TestCase;
use SleepingOwl\Admin\Contracts\ModelConfigurationInterface;
use SleepingOwl\Admin\Contracts\Template\TemplateInterface;

class AdminTest extends TestCase
{
    public function test_dashboard()
    {
        $response = $this->get('/admin');
        $response->assertStatus(200);
    }

    public function test_patients()
    {
        $response = $this->get('/admin/patients');
        $response->assertStatus(200);
    }

    public function test_polyclinics()
    {
        $response = $this->get('/admin/polyclinics');
        $response->assertStatus(200);
    }

    public function test_hospitals()
    {
        $response = $this->get('/admin/hospitals');
        $response->assertStatus(200);
    }

    public function test_departments()
    {
        $response = $this->get('/admin/departments');
        $response->assertStatus(200);
    }

    public function test_laboratories()
    {
        $response = $this->get('/admin/laboratories');
        $response->assertStatus(200);
    }

    public function test_hospital_appointments()
    {
        $response = $this->get('/admin/hospital_appointments');
        $response->assertStatus(200);
    }

    public function test_polyclinic_appointments()
    {
        $response = $this->get('/admin/polyclinic_appointments');
        $response->assertStatus(200);
    }

    public function test_profile_laboratories()
    {
        $response = $this->get('/admin/profile_laboratories');
        $response->assertStatus(200);
    }

    public function test_profile_doctors()
    {
        $response = $this->get('/admin/profile_doctors');
        $response->assertStatus(200);
    }

    public function test_profile_workers()
    {
        $response = $this->get('/admin/profile_workers');
        $response->assertStatus(200);
    }

    public function test_doctors()
    {
        $response = $this->get('/admin/doctors');
        $response->assertStatus(200);
    }

    public function test_workers()
    {
        $response = $this->get('/admin/workers');
        $response->assertStatus(200);
    }
}
