<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Student;
use App\Models\StudentTrip;
use App\Models\Trip;
use App\Models\TripDeparture;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DBController extends Controller
{
    public function bus19()
    {
//        $this->bc19();
//         $this->bch2();
         $this->bch3();
    }
    public function all_function()
    {
//        $this->bus43();
//        $this->bus29();
//        $this->bus2();
//        $this->bus39();
//        $this->bus54();
    }
    public function bch2()
    {
        \DB::beginTransaction();
        try
        {
            $student = Student::where('code', 'OS001718-19')->first();
            $id_student = $student->id; //1223
            $id_bus = Bus::where('name', 41)->first()->id; //46
            $id_depar_p = $student->departure_id; //921
            $id_depar_d = $student->departure_id2; //921
            $trips = Trip::where('bus_id', $id_bus)->get();
            foreach ($trips as $trip)
            {
                if ($trip->type == 1)
                {
                    $st = new StudentTrip();
                    $st->student_id = $id_student;
                    $st->trip_id = $trip->id;
                    $st->pick_up_id = 918;
                    $st->drop_off_id = $id_depar_p;
                    $st->save();

                    $trip->departures()->attach($id_depar_d, [
                        'arrive_time' => '17:00:00'
                    ]);
                }
                elseif ($trip->type == 2)
                {
                    $st = new StudentTrip();
                    $st->student_id = $id_student;
                    $st->trip_id = $trip->id;
                    $st->pick_up_id = $id_depar_d;
                    $st->drop_off_id = 918;
                    $st->save();

                    $trip->departures()->attach($id_depar_p, ['arrive_time' => '06:40:00']);
                }
            }

            \DB::commit();
echo 'bch2 success';
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            dd($e);
        }
    }

    public function bc19()
    {
    	\DB::beginTransaction();
        try
        {

            $fridays = [];
            $startDate = Carbon::parse('23-08-2017')->next(Carbon::WEDNESDAY); // Get the first friday. wednesday WEDNESDAY
            $endDate = Carbon::parse('31-05-2018');
            for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
                $fridays[] = $date->format('Y-m-d');
            }
            $mons = [];
            $start = Carbon::parse('23-08-2017')->next(Carbon::MONDAY); // Get the first friday.
            $end = Carbon::parse('31-05-2018');
            for ($date = $start; $date->lte($end); $date->addWeek()) {
                $mons[] = $date->format('Y-m-d');
            }
            $arr_day = array_merge($fridays, $mons);
            $bus_id = Bus::where('name', 19)->first()->id;
            $student = Student::where('code', 'OS061516-07')->first()->id;
            $trip = Trip::where('bus_id', $bus_id)->where('type', 1)->whereNotIn('arrive_date', $arr_day)->pluck('id')->toArray();
            $all_trip = Trip::where('bus_id', $bus_id)->where('type', 1)->get();
            foreach ($all_trip as $at)
            {
                $student_trip = StudentTrip::where('trip_id', $at->id)->where('student_id', '!=', $student)->get();
                foreach ($student_trip as $str)
                {
                    $check = TripDeparture::where('trip_id', $str->trip_id)->where('departure_id', 593)->count();
                        if ($check > 1)
                        {
                        }
                        else
                        {
                           $trip_id = TripDeparture::where('trip_id', $str->trip_id)->where('departure_id', 593)->get();
                            foreach ($trip_id as $t)
                            {
                                if (in_array($t->trip_id, $trip))
                                {
                                    TripDeparture::where('departure_id', 593)->where('trip_id', $t->trip_id)->delete();
                                }
                            }
                        }
                }
            }
            foreach ($trip as $ti)
            {
                StudentTrip::where('trip_id', $ti)->where('student_id', $student)->delete();
            }
            \DB::commit();
            echo 'bc19 success';

        } catch (\Exception $e) {
            \DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function bch3()
    {
        \DB::beginTransaction();
        try
        {
            $student = Student::where('code', 'OS011516-27')->first();
            $id_student = $student->id; //1223
            $id_bus = Bus::where('name', 55)->pluck('id')->first(); //46
            $id_depar_p = $student->departure_id; //921
            $id_depar_d = $student->departure_id2; //921
            $trips = Trip::where('bus_id', $id_bus)->get();

            foreach ($trips as $trip)
            {
                if ($trip->type == 1)
                {
                    $st = new StudentTrip();
                    $st->student_id = $id_student;
                    $st->trip_id = $trip->id;
                    $st->pick_up_id = 918;
                    $st->drop_off_id = $id_depar_p;
                    $st->save();
                    $trip->departures()->attach($id_depar_d, [
                        'arrive_time' => '16:45:00'
                    ]);
                }
                elseif ($trip->type == 2)
                {
                    $st = new StudentTrip();
                    $st->student_id = $id_student;
                    $st->trip_id = $trip->id;
                    $st->pick_up_id = $id_depar_d;
                    $st->drop_off_id = 918;
                    $st->save();

                    $trip->departures()->attach($id_depar_p, ['arrive_time' => '06:50:00']);
                }
            }

            \DB::commit();
            echo 'bch3 success';

        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            dd($e);
        }
    }

    public function bus43()
    {
        DB::beginTransaction();
        try
        {
            $trip_id = [];
            $student = [];
            $tdd = [];
            $bus_id = Bus::where('name', 43)->first()->id;
            $student[] = Student::where('code', 'OS051718-14')->first()->id;
            $student[] = Student::where('code', 'OS031718-09')->first()->id;
            $student[] = Student::where('code', 'OS001718-08')->first()->id;
            $trip = Trip::where('bus_id', $bus_id)->where('type', 2)->whereDate('arrive_date', '<', '2017-09-15')->get();
            foreach ($trip as $t)
            {
                $trip_id[] = $t->id;
                $student_trip = StudentTrip::where('trip_id', $t->id)->get();
                foreach ($student_trip as $s_trip)
                {
                    if (in_array($s_trip->student_id, $student))
                    {
                        $trip_departure = TripDeparture::where('trip_id', $s_trip->trip_id)->get();
                        foreach ($trip_departure as $td)
                        {
                            $tdd[] = $td->departure_id;
                        }
                    }
                }
            }
            $all_trip = Trip::where('bus_id', $bus_id)->where('type', 2)->get();
            foreach ($all_trip as $at)
            {
                $departure = [];
                $student_trip = StudentTrip::where('trip_id', $at->id)->get();
                foreach ($student_trip as $str)
                {
                    if (!in_array($str->student_id, $student))
                    {
                        $true_student_departure = TripDeparture::where('trip_id', $str->trip_id)->get();
                        foreach ($true_student_departure as $tsd)
                        {
                            $departure[] = $tsd->departure_id;
                        }
                        foreach ($tdd as $delete_departure)
                        {
                            if (!in_array($delete_departure, $departure))
                            {
                                TripDeparture::where('departure_id', $delete_departure)->delete();
                            }
                        }
                    }
                }
            }

            foreach ($trip_id as $ti)
            {
                $strtrip = StudentTrip::where('trip_id', $ti)->get();
                foreach ($strtrip as $s_trips)
                {
                    if (in_array($s_trips->student_id, $student))
                    {
                        StudentTrip::where('trip_id', $ti)->where('student_id', $s_trips->student_id)->delete();
                    }
                }
            }
            DB::commit();
            echo 'b43 success';

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function bus29()
    {
        DB::beginTransaction();
        try
        {
            $student = [];
            $bus_id = Bus::where('name', 29)->first()->id;
            $student[] = Student::where('code', 'OS081718-22')->first()->id;
            $student[] = Student::where('code', 'OS061718-49')->first()->id;
            $trip = Trip::where('bus_id', $bus_id)->where('type', 1)->pluck('id')->toArray();
            $all_trip = Trip::where('bus_id', $bus_id)->where('type', 1)->get();
            foreach ($all_trip as $at)
            {
                $student_trip = StudentTrip::where('trip_id', $at->id)->whereNotIn('student_id', $student)->get();
                foreach ($student_trip as $str)
                {
                    $check = TripDeparture::where('trip_id', $str->trip_id)->where('departure_id', 685)->count();
                    $check > 1 ?: TripDeparture::where('departure_id', 685)->delete();
                }
            }
            foreach ($trip as $ti)
            {
                $strtrip = StudentTrip::where('trip_id', $ti)->get();
                foreach ($strtrip as $s_trips)
                {
                    if (in_array($s_trips->student_id, $student))
                    {
                        StudentTrip::where('trip_id', $ti)->where('student_id', $s_trips->student_id)->delete();
                    }
                }
            }
            DB::commit();
            echo 'b29 success';

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function bus2()
    {
        DB::beginTransaction();
        try
        {
            $bus_id = Bus::where('name', 2)->first()->id;
            $student1 = Student::where('code', 'OS081718-22')->first();
            $student2 = Student::where('code', 'OS061718-49')->first();
            $trip = Trip::where('bus_id', $bus_id)->get();
            foreach ($trip as $t)
            {
                if ($t->type == 1)
                {
                    $stt = new StudentTrip();
                    $stt->student_id = $student1->id;
                    $stt->trip_id = $t->id;
                    $stt->pick_up_id = 918;
                    $stt->drop_off_id = $student1->departure_id2;
                    $stt->save();
                    if (TripDeparture::where('departure_id', $student2->departure_id2)->where('trip_id', $t->id)->count() < 1)
                    {
                        $t->departures()->attach($student1->departure_id2, ['arrive_time' => '16:40:00']);
                    }
                }
                elseif ($t->type == 2)
                {
                    $stt = new StudentTrip();
                    $stt->student_id = $student1->id;
                    $stt->trip_id = $t->id;
                    $stt->pick_up_id = $student1->departure_id;
                    $stt->drop_off_id = 918;
                    $stt->save();
                    if (TripDeparture::where('departure_id', $student2->departure_id)->where('trip_id', $t->id)->count() < 1)
                    {
                        $t->departures()->attach($student1->departure_id, ['arrive_time' => '07:05:00']);
                    }
                }
            }
            foreach ($trip as $t)
            {
                if ($t->type == 1)
                {
                    $stt = new StudentTrip();
                    $stt->student_id = $student2->id;
                    $stt->trip_id = $t->id;
                    $stt->pick_up_id = 918;
                    $stt->drop_off_id = $student2->departure_id2;
                    $stt->save();
                    if (TripDeparture::where('departure_id', $student2->departure_id2)->where('trip_id', $t->id)->count() < 1)
                    {
                        $t->departures()->attach($student2->departure_id2, ['arrive_time' => '16:40:00']);
                    }
                }
                elseif ($t->type == 2)
                {
                    $stt = new StudentTrip();
                    $stt->student_id = $student2->id;
                    $stt->trip_id = $t->id;
                    $stt->pick_up_id = $student2->departure_id;
                    $stt->drop_off_id = 918;
                    $stt->save();
                    if (TripDeparture::where('departure_id', $student2->departure_id)->where('trip_id', $t->id)->count() < 1)
                    {
                        $t->departures()->attach($student2->departure_id, ['arrive_time' => '07:05:00']);
                    }
                }
            }
            DB::commit();
            echo 'b2 success';

        }
        catch (\Exception $e)
        {
             DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function bus39()
    {
        DB::beginTransaction();
        try
        {
            $bus_id = Bus::where('name', 39)->first()->id;
            $student = Student::where('code', 'OS011718-46')->first()->id;
            $trip = Trip::where('bus_id', $bus_id)->where('type', 2)->pluck('id')->toArray();
            $all_trip = Trip::where('bus_id', $bus_id)->where('type', 2)->get();
            foreach ($all_trip as $at)
            {
                $student_trip = StudentTrip::where('trip_id', $at->id)->where('student_id', '!=', $student)->get();
                foreach ($student_trip as $str)
                {
                    $check = TripDeparture::where('trip_id', $str->trip_id)->where('departure_id', 767)->count();
                    $check > 1 ?: TripDeparture::where('trip_id', $str->trip_id)->where('departure_id', 767)->delete();
                }
            }
            foreach ($trip as $ti)
            {
                StudentTrip::where('trip_id', $ti)->where('student_id', $student)->delete();
            }
            DB::commit();
            echo 'b39 success';

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function bus54()
    {
        \DB::beginTransaction();
        try
        {
            $student = Student::where('code', 'OS011718-46')->first();
            $id_student = $student->id; //1223
            $id_bus = Bus::where('name', 55)->first()->id; //46
            $id_depar_p = $student->departure_id; //921
            $trips = Trip::where('bus_id', $id_bus)->where('type', 2)->get();
            foreach ($trips as $trip)
            {
                $st = new StudentTrip();
                $st->student_id = $id_student;
                $st->trip_id = $trip->id;
                $st->pick_up_id = $id_depar_p;
                $st->drop_off_id = 918;
                $st->save();
                $trip->departures()->attach($id_depar_p, ['arrive_time' => '06:53:00 ']);
            }

            \DB::commit();
            echo 'b55 success';

        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            dd($e);
        }
    }

}
