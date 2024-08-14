<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserImported;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class CsvImportController
{
    public function csv_import()
    {
        return view('admin.csv');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);
        $file = fopen($request->file('csv_file'), 'r');
        $header = fgetcsv($file);
        $importedCount = 0;
        $failedCount = 0;
        $errors = [];
        while ($row = fgetcsv($file)) {
            try {
                $this->validateRowData($row);
                $password = Str::random(10);
                $formattedDate = Carbon::createFromFormat('d-M-y', $row[4])->format('Y-m-d');
                $user = User::create([
                    'first_name' => $row[0],
                    'last_name' => $row[1],
                    'email' => $row[2],
                    'phone' => $row[3],
                    'doj' =>  $formattedDate,
                    'designation' => $row[5],
                    'password' => Hash::make($password),
                ]);
                // Send email to the user using event
                event(new UserImported($user));
                $importedCount++;
            } catch (Exception $e) {
                // Log the error
                // Log::error("Failed to import user: {$row[2]}, Error: " . $e->getMessage());

                $failedCount++;
                $errors[] = "Failed to import user with email {$row[2]}: " . $e->getMessage();
            }
        }

        fclose($file);
        $message = "Imported {$importedCount} users successfully.";
        if ($failedCount > 0) {
            $message .= " Failed to import {$failedCount} users.";
            return redirect()->back()->withErrors($errors)->with('success', $message);
        }
        return redirect()->back()->with('success', $message);
    }

    private function validateRowData(array $row)
    {
        if (!filter_var($row[2], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format for {$row[2]}.");
        }
        if (User::where('email', $row[2])->exists()) {
            throw new Exception("User with email {$row[2]} already exists.");
        }
    }
}
