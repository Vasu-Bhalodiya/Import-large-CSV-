<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        return view('importcsv');
    }

    public function store(Request $request)
    {
        set_time_limit(0);

        $request->validate([
            'import_csv' => 'required|mimes:csv',
        ]);

        $file = $request->file('import_csv');
        $handle = fopen($file->path(), 'r');

        // Skip the header row
        fgetcsv($handle);

        $chunkSize = 5000;
        $chunkData = [];
        $totalRows = 0;
        $processedRows = 0;

        while (!feof($handle)) {
            for ($i = 0; $i < $chunkSize; $i++) {
                $data = fgetcsv($handle);
                if ($data === false) {
                    break;
                }
                $totalRows++;

                if (count($data) < 12) {
                    Log::warning('Malformed row detected and skipped', ['data' => $data]);
                    continue;
                }

                $chunkData[] = [
                    'customer_id' => $data[1] ?? null,
                    'f_name' => $data[2] ?? null,
                    'l_name' => $data[3] ?? null,
                    'company' => $data[4] ?? null,
                    'city' => $data[5] ?? null,
                    'country' => $data[6] ?? null,
                    'phone_first' => $data[7] ?? null,
                    'phone_second' => $data[8] ?? null,
                    'email' => $data[9] ?? null,
                    'subscription_date' => $data[10] ?? null,
                    'website' => $data[11] ?? null,
                ];
            }
            if(!empty($chunkData)){
            $this->insertChunkData($chunkData);
            $processedRows += count($chunkData);
            $chunkData = [];
            unset($chunkData);
            }else{
                fclose($handle);
                Log::info('CSV import completed', ['totalRows' => $totalRows, 'processedRows' => $processedRows]);
                return redirect()->route('dashboard')->with('success', 'Data has been added successfully.');
            }
        }
    }

    private function insertChunkData(array $chunkData)
    {
        if (!empty($chunkData)) {
            try {
                // Use a transaction for bulk insert
                DB::transaction(function () use ($chunkData) {
                    DB::table('customers')->insert($chunkData);
                });
            } catch (\Exception $e) {
                
                Log::error('Failed to insert chunk data', ['error' => $e->getMessage(), 'chunkData' => $chunkData]);
            }
        }
    }
}
