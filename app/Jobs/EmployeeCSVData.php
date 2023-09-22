<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Employee;

class EmployeeCSVData implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {

        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = now()->toDateString();
        $records = [];
        foreach ($this->data as $row) {
            $records[] = [
                "employee_id" => $row[0],
                "name_prefix" => $row[1],
                "first_name" => $row[2],
                "middle_initial" => $row[3],
                "last_name" => $row[4],
                "gender" => $row[5],
                "email" => $row[6],
                "date_of_birth" => $row[7],
                "time_of_birth" => $row[8],
                "age_in_years" => $row[9],
                "date_joined" => $row[10],
                "age_in_company" => $row[11],
                "phone" => $row[12],
                "place_name" => $row[13],
                "county" => $row[14],
                "city" => $row[15],
                "zip" => $row[16],
                "region" => $row[17],
                "username" => $row[18],
                "created_at" => $now,
                "updated_at" => $now,
            ];
        }
        Employee::insert($records);
    }
}
