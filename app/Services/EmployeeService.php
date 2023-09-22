<?php

namespace App\Services;

use App\Repositories\EmployeeRepository; // Import the repository
use Illuminate\Support\Facades\Bus;
use App\Jobs\EmployeeCSVData;


class EmployeeService
{
    protected $employeeRepository;

    /**
     * EmployeeService constructor.
     *
     * @param EmployeeRepository $employeeRepository
     */
    public function __construct(EmployeeRepository $employeeRepository)
    {

        $this->employeeRepository = $employeeRepository;
    }

    /**
     * Pluck the given column's value from the employee repository.
     *
     * @param string $column
     * @return mixed
     */
    public function pluck($column)
    {
        return $this->employeeRepository->pluck($column);
    }

    /**
     * Import the given file.
     *
     * @param mixed $file
     * @return array
     * @throws \Throwable
     */
    public function import($file)
    {
        // Upload the file
        $path = $file->store('uploads');

        //Extract arrays from the file
        $data = array_map('str_getcsv', file(storage_path('app/' . $path)));

        // Remove the header row
        array_shift($data);

        // compare the db records and array to remove duplicates
        $data = $this->removeDuplicates($data);

        $uniqueData = $data['unique'];
        $uniqueDataCount = count($data['unique']);
        $duplicateDataCount = count($data['duplicates']);

        // If there are now unique rows, we return early
        if ($uniqueDataCount < 1) {
            return [
                'unique_count' => $uniqueDataCount,
                'duplicate_count' => $duplicateDataCount,
            ];
        }

        try {

            $this->CSVImportWithBatchJob($uniqueData);
        } catch (\Throwable $th) {
            throw $th;
        }

        return [
            'unique_count' => $uniqueDataCount,
            'duplicate_count' => $duplicateDataCount,
        ];
    }

    /**
     * Remove duplicates from the given array and from db.
     *
     * @param array $array
     * @return array
     */
    public function removeDuplicates($array)
    {
        // Extract employee_id using array_column
        $employeeId = array_column($array, 0); // 0 is the index where employee_id are located

        // Remove duplicates
        $uniqueEmployeeIds = array_unique($employeeId);

        // Retrieve the employee ID from DB
        $dbEmployees = $this->pluck('employee_id')->toArray();

        $duplicateArray = [];
        $filteredArray = [];


        foreach ($array as $subarray) {
            $key = $subarray[0];

            if (in_array($key, $uniqueEmployeeIds) and !in_array($key, $dbEmployees)) {

                $filteredArray[] = $subarray;
                // Remove the employee_id from the $uniqueEmployeeIds array to prevent duplicates in the result
                $key = array_search($key, $uniqueEmployeeIds);

                if ($key !== false) {
                    unset($uniqueEmployeeIds[$key]);
                }
            } else {
                $duplicateArray[] = $subarray;
            }
        }


        return ['unique' => $filteredArray, 'duplicates' => $duplicateArray];
    }


    /**
     * Import CSV with batch job.
     *
     * @param array $uniqueData
     */
    public function CSVImportWithBatchJob($uniqueData)
    {
        $batch  = Bus::batch([])->dispatch();
        // Break the array into chunk and pass to the batch job to do the heavy lifting
        foreach (array_chunk($uniqueData, 1000) as $chunk) {

            $batch->add(new EmployeeCSVData($chunk));
        }
    }

    /**
     * Get all employees.
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->employeeRepository->getAll();
    }

    /**
     * Get the employee with the given ID.
     *
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->employeeRepository->get($id);
    }

    /**
     * Delete the employee with the given ID.
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->employeeRepository->delete($id);
    }
}
