<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Exception;


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
        // $requiredColumns = ['firstName', 'lastName', 'email', 'phone', 'designation', 'doj'];
        // if ($header !== $requiredColumns) {
        //     return redirect()->back()->withErrors(['csv_file' => 'Invalid CSV format.']);
        // }
        $importedCount = 0;
        $failedCount = 0;
        $errors = [];
        while ($row = fgetcsv($file)) {
            try {
                $this->validateRowData($row);
                $password = Str::random(10);
                $user = User::create([
                    'first_name' => $row[0],
                    'last_name' => $row[1],
                    'email' => $row[2],
                    'phone' => $row[3],
                    'designation' => $row[4],
                    'doj' => $row[5],
                    'password' => Hash::make($password),
                ]);
                // Send email to the user using event

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
