<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Returning;

class ReturningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Returning::create(["assignment_id" => 1, "accepted_by" => 20, "requested_by" => 6, "returned_date" => "2022-06-17", "state" => 0]);
        Returning::create(["assignment_id" => 2, "accepted_by" => 38, "requested_by" => 13, "returned_date" => "2020-01-19", "state" => 1]);
        Returning::create(["assignment_id" => 3, "accepted_by" => 38, "requested_by" => 6, "returned_date" => "2020-04-21", "state" => 0]);
        Returning::create(["assignment_id" => 4, "accepted_by" => 20, "requested_by" => 23, "returned_date" => "2020-11-30", "state" => 1]);
        Returning::create(["assignment_id" => 5, "accepted_by" => 51, "requested_by" => 42, "returned_date" => "2022-04-02", "state" => 0]);
        Returning::create(["assignment_id" => 6, "accepted_by" => 2, "requested_by" => 46, "returned_date" => "2021-11-29", "state" => 1]);
        Returning::create(["assignment_id" => 7, "accepted_by" => 29, "requested_by" => 4, "returned_date" => "2020-07-03", "state" => 0]);
        Returning::create(["assignment_id" => 8, "accepted_by" => 44, "requested_by" => 33, "returned_date" => "2019-12-19", "state" => 1]);
        Returning::create(["assignment_id" => 9, "accepted_by" => 10, "requested_by" => 7, "returned_date" => "2020-06-05", "state" => 0]);
        Returning::create(["assignment_id" => 10, "accepted_by" => 25, "requested_by" => 34, "returned_date" => "2021-09-10", "state" => 1]);
        Returning::create(["assignment_id" => 11, "accepted_by" => 20, "requested_by" => 6, "returned_date" => "2019-12-16", "state" => 0]);
        Returning::create(["assignment_id" => 12, "accepted_by" => 38, "requested_by" => 13, "returned_date" => "2022-01-02", "state" => 1]);
        Returning::create(["assignment_id" => 13, "accepted_by" => 38, "requested_by" => 6, "returned_date" => "2020-02-18", "state" => 0]);
        Returning::create(["assignment_id" => 14, "accepted_by" => 20, "requested_by" => 23, "returned_date" => "2021-02-25", "state" => 1]);
        Returning::create(["assignment_id" => 15, "accepted_by" => 51, "requested_by" => 42, "returned_date" => "2020-02-16", "state" => 0]);
        Returning::create(["assignment_id" => 16, "accepted_by" => 2, "requested_by" => 46, "returned_date" => "2019-11-06", "state" => 1]);
        Returning::create(["assignment_id" => 17, "accepted_by" => 29, "requested_by" => 4, "returned_date" => "2020-08-12", "state" => 0]);
        Returning::create(["assignment_id" => 18, "accepted_by" => 44, "requested_by" => 33, "returned_date" => "2021-10-24", "state" => 1]);
        Returning::create(["assignment_id" => 19, "accepted_by" => 10, "requested_by" => 7, "returned_date" => "2020-04-18", "state" => 0]);
        Returning::create(["assignment_id" => 20, "accepted_by" => 25, "requested_by" => 34, "returned_date" => "2019-09-05", "state" => 1]);
        Returning::create(["assignment_id" => 21, "accepted_by" => 20, "requested_by" => 6, "returned_date" => "2022-02-22", "state" => 0]);
        Returning::create(["assignment_id" => 22, "accepted_by" => 38, "requested_by" => 13, "returned_date" => "2022-05-08", "state" => 1]);
        Returning::create(["assignment_id" => 23, "accepted_by" => 38, "requested_by" => 6, "returned_date" => "2019-10-01", "state" => 0]);
        Returning::create(["assignment_id" => 24, "accepted_by" => 20, "requested_by" => 23, "returned_date" => "2021-02-06", "state" => 1]);
        Returning::create(["assignment_id" => 25, "accepted_by" => 51, "requested_by" => 42, "returned_date" => "2021-11-16", "state" => 0]);
        Returning::create(["assignment_id" => 26, "accepted_by" => 2, "requested_by" => 46, "returned_date" => "2021-10-05", "state" => 1]);
        Returning::create(["assignment_id" => 27, "accepted_by" => 29, "requested_by" => 4, "returned_date" => "2022-07-14", "state" => 0]);
        Returning::create(["assignment_id" => 28, "accepted_by" => 44, "requested_by" => 33, "returned_date" => "2020-03-28", "state" => 1]);
        Returning::create(["assignment_id" => 29, "accepted_by" => 10, "requested_by" => 7, "returned_date" => "2021-01-27", "state" => 0]);
        Returning::create(["assignment_id" => 30, "accepted_by" => 25, "requested_by" => 34, "returned_date" => "2020-02-03", "state" => 1]);
        Returning::create(["assignment_id" => 31, "accepted_by" => 20, "requested_by" => 6, "returned_date" => "2020-11-07", "state" => 0]);
        Returning::create(["assignment_id" => 32, "accepted_by" => 38, "requested_by" => 13, "returned_date" => "2022-04-02", "state" => 1]);
        Returning::create(["assignment_id" => 33, "accepted_by" => 38, "requested_by" => 6, "returned_date" => "2020-02-22", "state" => 0]);
        Returning::create(["assignment_id" => 34, "accepted_by" => 20, "requested_by" => 23, "returned_date" => "2021-06-13", "state" => 1]);
        Returning::create(["assignment_id" => 35, "accepted_by" => 51, "requested_by" => 42, "returned_date" => "2020-03-13", "state" => 0]);
        Returning::create(["assignment_id" => 36, "accepted_by" => 2, "requested_by" => 46, "returned_date" => "2020-01-30", "state" => 1]);
        Returning::create(["assignment_id" => 37, "accepted_by" => 29, "requested_by" => 4, "returned_date" => "2020-10-15", "state" => 0]);
        Returning::create(["assignment_id" => 38, "accepted_by" => 44, "requested_by" => 33, "returned_date" => "2019-09-18", "state" => 1]);
        Returning::create(["assignment_id" => 39, "accepted_by" => 10, "requested_by" => 7, "returned_date" => "2022-04-03", "state" => 0]);
        Returning::create(["assignment_id" => 40, "accepted_by" => 25, "requested_by" => 34, "returned_date" => "2020-10-19", "state" => 1]);
    }
}
