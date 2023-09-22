<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository
{

    protected $model;

    /**
     * EmployeeRepository constructor.
     *
     * @param Employee $employee
     */
    public function __construct(Employee $employee)
    {
        $this->model = $employee;
    }

    /**
     * Get all employees.
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Pluck the given column's value from the employee model.
     *
     * @param string $column
     * @return mixed
     */
    public function pluck($column)
    {
        return $this->model->pluck($column);
    }

    /**
     * Get the employee with the given ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Get the employee with the given employee ID.
     *
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->model->where('employee_id', $id)->first();
    }

    /**
     * Create a new employee with the given data.
     *
     * @param array $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * Insert the given data into the employee model.
     *
     * @param array $data
     * @return mixed
     */
    public function insert($data)
    {
        return $this->model->insert($data);
    }

    /**
     * Update the employee with the given ID using the given data.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $employee = $this->getById($id);

        if ($employee) {
            $employee->update($data);
            return $employee;
        }

        return null; // Employee not found
    }

    /**
     * Delete the employee with the given ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $employee = $this->getById($id);

        if ($employee) {
            $employee->delete();
            return true;
        }

        return false; // Employee not found
    }
}
