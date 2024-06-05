<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(){
        return view('importcsv');
    }

    public function store(Request $request){
        set_time_limit(0);
        $request->validate([
            'import_csv' => 'required|mimes:csv',
        ]);
        //read csv file and skip data
        $file = $request->file('import_csv');
        $handle = fopen($file->path(), 'r');

        //skip the header row
        fgetcsv($handle);


        $chunksize = 5000;
        while(!feof($handle))
        {
            $chunkdata = [];

            for($i = 0; $i<$chunksize; $i++)
            {
                $data = fgetcsv($handle);
                if($data === false)
                {
                    break;
                }
                $chunkdata[] = $data; 
            }

            $this->getchunkdata($chunkdata);
        }
        fclose($handle);

        return redirect()->route('employee.create')->with('success', 'Data has been added successfully.');
    }
    public function getchunkdata($chunkdata)
    {
        foreach($chunkdata as $column){
            $customer_id = $column[1];
            $firstname = $column[2];
            $lastname = $column[3];
            $company = $column[4];
            $city = $column[5];
            $country = $column[6];
            $phone_first = $column[7];
            $phone_second = $column[8];
            $email = $column[9];
            $subscription_date = $column[10];
            $website = $column[11];

            //create new employee
            $employee = new Customer();
            $employee->customer_id = $customer_id;
            $employee->f_name = $firstname;
            $employee->l_name = $lastname;
            $employee->company = $company;
            $employee->city = $city;
            $employee->country = $country;
            $employee->phone_first = $phone_first;
            $employee->phone_second = $phone_second;
            $employee->email = $email;
            $employee->subscription_date = $subscription_date;
            $employee->website = $website;

            $employee->save();
        }
    }
}
