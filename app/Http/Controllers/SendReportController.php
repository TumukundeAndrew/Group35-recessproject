<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ReportMail;

class ReportController extends Controller
{
    public function sendUserReport()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Example group/role retrieval (adjust based on your app)
        $email = $user->email;
        $role = $user->role ?? 'No group assigned';

        // Data for PDF
        $data = [
            'name' => $user->name,
            'email' => $email,
            'role' => $role,
            'generated_at' => now()->toDateTimeString()
        ];

        // Generate PDF from Blade view
        $pdf = Pdf::loadView('reports.user_report', $data);

        // Send email with PDF attachment
        Mail::to($email)->send(new ReportMail($pdf->output(), $data));

        return response()->json(['message' => 'Report sent successfully to ' . $email]);
    }
}
