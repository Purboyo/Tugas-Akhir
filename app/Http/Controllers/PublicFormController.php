<?php

namespace App\Http\Controllers;

use App\Models\PC;
use App\Models\Form;
use App\Models\User;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use App\Models\Reporter;
use App\Models\Report;
use App\Models\Report_answer;
use Illuminate\Validation\ValidationException;

class PublicFormController extends Controller
{
    public function welcome($id)
    {
        // Ambil PC dan relasi lab+technician-nya
        $pc = PC::with('lab.technician')->findOrFail($id);

        // Ambil form berdasarkan laboratory_id PC
$form = Form::whereHas('laboratories', function ($query) use ($pc) {
    $query->where('laboratories.id', $pc->lab_id);
})->where('created_by', $pc->lab->technician->id)->first();



        if (!$form) {
            abort(404, 'Form untuk lab ini tidak ditemukan.');
        }

        // Generate URL untuk route public.form.redirect dengan parameter pcId
        $formUrl = route('public.form.redirect', ['pcId' => $pc->id]);

        // Kirim data ke view
        return view('public.forms.welcome', compact('pc', 'form', 'formUrl'));
    }

    public function redirectToForm($pcId)
    {
        // Cari PC dan relasi lab
        $pc = PC::with('lab')->findOrFail($pcId);

        // Cari form berdasarkan laboratory_id PC
    $form = Form::whereHas('laboratories', function ($query) use ($pc) {
        $query->where('laboratories.id', $pc->lab_id);
    })->where('created_by', $pc->lab->technician->id)->first();


        if (!$form) {
            abort(404, 'Form untuk lab ini tidak ditemukan.');
        }

        // Redirect ke halaman pengisian form
        return redirect()->route('form.fill', ['form' => $form->id, 'pc' => $pc->id]);
    }

    public function fill(Form $form, $pcId)
    {
        $pc = PC::findOrFail($pcId);
        $form->load('questions');
        return view('public.forms.fill', compact('form', 'pc'));
    }
    

// Submit form
public function submit(Request $request, Form $form)
{
    try {
        $validated = $request->validate([
            'reporter.name' => 'required|string|max:255',
            'reporter.npm' => 'required|string|max:255',
            'reporter.telephone' => 'required|string|max:255',
            'pc_id' => 'required|exists:pcs,id',
            'answers' => 'required|array',
        ]);
    } catch (ValidationException $e) {
        dd($e->errors());
    }

    // Simpan data reporter
    $reporter = Reporter::create([
        'name' => $validated['reporter']['name'],
        'npm' => $validated['reporter']['npm'],
        'telephone' => $validated['reporter']['telephone'],
    ]);

    // Cek apakah ada jawaban buruk
    $burukKeywords = ['Buruk','rusak', 'tidak berfungsi', 'tidak menyala', 'Tidak', '1', '2']; // sesuaikan jika perlu
    $isBad = false;

    foreach ($validated['answers'] as $answer) {
        $jawaban = is_array($answer) ? implode(' ', $answer) : $answer;
        foreach ($burukKeywords as $keyword) {
            if (stripos($jawaban, $keyword) !== false) {
                $isBad = true;
                break 2;
            }
        }
    }

    // Status disesuaikan dengan enum: 'Good', 'Bad', 'Repairing', 'Pending'
    $status = $isBad ? 'Bad' : 'Good';

    // Simpan report
    $report = Report::create([
        'reporter_id' => $reporter->id,
        'form_id' => $form->id,
        'pc_id' => $validated['pc_id'],
        'status' => $status,
    ]);

    // Simpan jawaban
    foreach ($validated['answers'] as $questionId => $answer) {
        // Cek apakah jawaban dalam bentuk array (karena skala + keterangan)
        if (is_array($answer)) {
            $formattedAnswer = json_encode([
                'value' => $answer['value'] ?? '',
                'note' => $answer['note'] ?? '',
            ]);
        } else {
            $formattedAnswer = $answer;
        }

        Report_answer::create([
            'report_id' => $report->id,
            'question_id' => $questionId,
            'answer_text' => $formattedAnswer,
        ]);
    }


    return redirect()->route('form.success', ['pc' => $validated['pc_id']]);
}


    public function success($pcId)
    {
        $pc = PC::findOrFail($pcId);
        return view('public.forms.succes', compact('pc'));
    }


}
