<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\EmployeeService;


class EmployeeController extends Controller
{

    protected $employeeService;

    /**
     * EmployeeController constructor.
     * @param EmployeeService $employeeService
     */
    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    /**
     * Get all employees.
     * @return Response
     */
    public function allEmployees()
    {

        $data = $this->employeeService->getAll();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => "All employees returned",
            'data' => $data
        ]);
    }

    /**
     * Import employees from a CSV file.
     * @param Request $request
     * @return Response
     */
    public function importEmployees(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Validation failed',
                'error' => $validator->errors()
            ]);
        }


        try {
            // Call the CsvImportService to handle the import
            $result = $this->employeeService->import($request->file('file'));

            // Get count of duplicates
            $numberOfUniqueData = $result['unique_count'];
            $numberOfDuplicateData = $result['duplicate_count'];

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => "$numberOfUniqueData rows imported successfully. $numberOfDuplicateData duplicate(s) found",
                'data' => null
            ]);
        } catch (\Exception $e) {
            // Handle exceptions here, such as validation errors or database errors
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific employee by Employee ID.
     * @param int $id
     * @return Response
     */
    public function getEmployee($id)
    {

        $data = $this->employeeService->get($id);

        // If Employee is not  found, return
        if (!$data) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => "Employee not found",
                'data' => null
            ]);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => "Employee returned successfully",
            'data' => $data
        ]);
    }

    /**
     * Delete a specific employee by ID.
     * @param int $id
     * @return Response
     */
    public function deleteEmployee($id)
    {

        $data = $this->employeeService->delete($id);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => "Employee deleted successfully",
        ]);
    }
}
