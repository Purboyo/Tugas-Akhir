<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Form_question;
use App\Models\Laboratory as Lab;
use App\Models\Report;
use App\Models\Report_answer;
use App\Models\Reporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasUserRole;


class FormController extends Controller
{
    use HasUserRole;

    public function __construct()
    {
        $this->setUserRole();
    }
public function index(Request $request)
{
    $user = Auth::user();
    $role = $user->role;

    $query = Form::with('laboratories', 'questions');

    // Jika role teknisi, filter berdasarkan lab yang dimiliki teknisi
    if ($role === 'teknisi') {
        $query->whereHas('laboratories', function ($q) use ($user) {
            $q->where('technician_id', $user->id);
        });
    }

    // Jika ada pencarian berdasarkan judul
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    $forms = $query->get();

    return view('admin.forms.index', compact('forms', 'role'));
}



public function create()
{
    $user = Auth::user();
    $labs = $user->role === 'admin'
        ? Lab::all()
        : Lab::where('technician_id', $user->id)->get();

    $defaultQuestions = collect();

    if ($user->role !== 'admin') {
        $defaultForm = \App\Models\Form::where('is_default', true)->first();
        if ($defaultForm) {
            $defaultQuestions = $defaultForm->questions;
        }
    }

    return view('admin.forms.create', compact('labs', 'defaultQuestions'));
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'lab_id' => 'required|array|exists:laboratories,id',
        'questions' => 'array',
        'questions.*.question_text' => 'required|string',
        'questions.*.type' => 'required|in:text,number,checkbox,radio,textarea',
    ]);

    DB::beginTransaction();

    try {
        $isAdmin = auth::user()->role === 'admin';

        $form = Form::create([
            'title' => $request->title,
            'is_default' => $isAdmin,
            'created_by' => auth::id(),
        ]);

        $form->laboratories()->sync($request->lab_id);

        // Jika teknisi, salin pertanyaan default dari form admin
        if (!$isAdmin) {
            $defaultForm = Form::where('is_default', true)->first();

            if ($defaultForm) {
                foreach ($defaultForm->questions as $q) {
                    Form_question::create([
                        'form_id' => $form->id,
                        'question_text' => $q->question_text,
                        'type' => $q->type,
                        'options' => $q->options,
                        'is_default' => true, // Penting!
                    ]);
                }
            }
        }

        // Simpan pertanyaan tambahan (buatan admin/teknisi)
        if ($request->has('questions')) {
            foreach ($request->questions as $question) {
                Form_question::create([
                    'form_id' => $form->id,
                    'question_text' => $question['question_text'],
                    'type' => $question['type'],
                    'options' => in_array($question['type'], ['radio', 'checkbox']) 
                        ? json_encode($question['options'] ?? [])
                        : null,
                    'is_default' => false, // Penting juga!
                ]);
            }
        }

        DB::commit();
        return redirect()->route($this->role . '.form.index')->with('success', 'Form saved successfully.');
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Failed to save form.')->withInput();
    }
}



public function edit($id)
{
    $form = Form::with(['laboratories', 'questions'])->findOrFail($id);
    $labs = auth::user()->role === 'admin'
        ? Lab::all()
        : Lab::where('technician_id', auth::id())->get();

    // Ambil pertanyaan default (is_default = true) dan non-default
    $defaultQuestions = $form->questions->where('is_default', true);
    $customQuestions = $form->questions->where('is_default', false);

    return view('admin.forms.edit', [
        'form' => $form,
        'labs' => $labs,
        'defaultQuestions' => $defaultQuestions,
        'customQuestions' => $customQuestions,
    ]);
}
public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'lab_id' => 'required|array|exists:laboratories,id',
        'questions' => 'nullable|array',
        'questions.*.question_text' => 'required|string',
        'questions.*.type' => 'required|in:text,number,checkbox,radio,textarea',
    ]);

    DB::beginTransaction();

    try {
        $form = Form::findOrFail($id);

        // Update judul form
        $form->update([
            'title' => $request->title,
        ]);

        // Sinkronisasi lab
        $form->laboratories()->sync($request->lab_id);

        // Hapus hanya pertanyaan teknisi, bukan default admin
        $form->questions()->where('is_default', false)->delete();

        // Tambahkan pertanyaan dari request (teknisi)
        if ($request->has('questions')) {
            foreach ($request->questions as $question) {
                Form_question::create([
                    'form_id' => $form->id,
                    'question_text' => $question['question_text'],
                    'type' => $question['type'],
                    'options' => in_array($question['type'], ['radio', 'checkbox']) 
                        ? json_encode($question['options'] ?? []) 
                        : null,
                    'is_default' => false, // teknisi = bukan default
                ]);
            }
        }

        DB::commit();
        return redirect()->route($this->role . '.form.index')->with('success', 'Form updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to update form.')->withInput();
    }
}


public function destroy($id)
{
    $form = Form::findOrFail($id);
    DB::transaction(function () use ($form) {
        $form->laboratories()->detach();
        $form->questions()->delete();
        $form->delete();
    });

    return redirect()->route($this->role . '.form.index')->with('success', 'Form deleted successfully.');
}

}
