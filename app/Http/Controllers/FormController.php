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

    $labs = $user->role === 'teknisi'
        ? Lab::where('technician_id', $user->id)->get()
        : Lab::all();

    return view('admin.forms.create', compact('labs'));
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
        ]);
        $form->laboratories()->sync($request->lab_id);

    
            if (!$isAdmin) {
                // Cari form default
                $defaultForm = Form::where('is_default', true)->first();
    
                if ($defaultForm) {
                    // Salin semua pertanyaan dari form default (tidak bisa diubah)
                    foreach ($defaultForm->questions as $q) {
                        Form_question::create([
                            'form_id' => $form->id,
                            'question_text' => $q->question_text,
                            'type' => $q->type,
                            'options' => $q->options,
                            'is_editable' => false, // Buat kolom baru jika perlu
                        ]);
                    }
                }
            }
    
            // Tambah pertanyaan tambahan dari user (admin atau teknisi)
            if ($request->has('questions')) {
                foreach ($request->questions as $question) {
                    Form_question::create([
                        'form_id' => $form->id,
                        'question_text' => $question['question_text'],
                        'type' => $question['type'],
                        'options' => in_array($question['type'], ['radio', 'checkbox']) 
                            ? json_encode($question['options'] ?? [])
                            : null,
                        'is_editable' => true,
                    ]);
                }
            }
    
            DB::commit();
            return redirect()->route($this->role. '.form.index')->with('success', 'Form saved successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to save form.')->withInput();
        }
    }

public function edit($id)
{
    $form = Form::with(['questions', 'laboratories'])->findOrFail($id);
    $user = auth::user();

    $labs = $user->role === 'teknisi'
        ? Lab::where('technician_id', $user->id)->get()
        : Lab::all();

    return view('admin.forms.edit', compact('form', 'labs'));
}


public function update(Request $request, $id)
{
    $form = Form::findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'lab_id' => 'required|array|exists:laboratories,id',
        'questions' => 'required|array|min:1',
        'questions.*.question_text' => 'required|string',
        'questions.*.type' => 'required|in:text,number,checkbox,radio,textarea',
    ]);

    DB::beginTransaction();

    try {
        // Update form data
        $form->update([
            'title' => $request->title,
        ]);

        // Sync lab relasi
        $form->laboratories()->sync($request->lab_id);

        // Hapus pertanyaan lama
        $form->questions()->delete();

        // Tambahkan pertanyaan baru
        foreach ($request->questions as $question) {
            Form_question::create([
                'form_id' => $form->id,
                'question_text' => $question['question_text'],
                'type' => $question['type'],
                'options' => in_array($question['type'], ['radio', 'checkbox']) 
                    ? json_encode($question['options'] ?? [])
                    : null,
                'is_editable' => true,
            ]);
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
