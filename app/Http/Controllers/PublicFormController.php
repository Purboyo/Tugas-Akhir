<?php

namespace App\Http\Controllers;

use App\Models\PC;
use App\Models\Form;
use Illuminate\Http\Request;
use App\Models\Reporter;
use App\Models\Report;
use App\Models\Report_answer;

class PublicFormController extends Controller
{
    public function redirectToForm($pcId)
    {
        $pc = PC::with('lab')->findOrFail($pcId);

        // Cari form berdasarkan lab_id
        $form = Form::where('lab_id', $pc->lab_id)->first();

        if (!$form) {
            return abort(404, 'Form untuk lab ini tidak ditemukan.');
        }

        // Redirect ke form isian (bisa sesuaikan path)
        return redirect()->route('form.fill', ['form' => $form->id, 'pc' => $pc->id]);
    }

    public function fill(Form $form, Request $request)
    {
        // Munculkan halaman isi form dengan data questions
        $form->load('questions'); // pastikan relasi questions ada di model Form

        return view('forms.fill', compact('form'));
    }

    public function submit(Form $form, Request $request)
    {
        $request->validate([
            'reporter.name' => 'required|string|max:255',
            'reporter.npm' => 'required|string|max:255',
            'answers' => 'required|array',
        ]);

        // Buat atau ambil reporter berdasarkan npm
        $reporter = Reporter::firstOrCreate(
            ['npm' => $request->input('reporter.npm')],
            ['name' => $request->input('reporter.name')]
        );

        // Simpan report
        $report = Report::create([
            'reporter_id' => $reporter->id,
            'computer_id' => $request->input('computer_id'), // Bisa kamu kirim hidden input di form
            'form_id' => $form->id,
        ]);

        // Simpan jawaban
        foreach ($request->input('answers') as $questionId => $answerText) {
            Report_answer::create([
                'report_id' => $report->id,
                'question_id' => $questionId,
                'answer_text' => is_array($answerText) ? json_encode($answerText) : $answerText,
            ]);
        }

        return redirect()->route('form.fill', $form)->with('success', 'Form submitted successfully!');
    }
}
