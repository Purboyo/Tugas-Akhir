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
        $role = auth::user()->role;

        $query = Form::with('lab', 'questions');

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where('title', 'like', '%' . $search . '%');
        }

        $forms = $query->get();

        return view($role . '.forms.index', compact('forms', 'role'));
    }


    public function create()
    {
        $labs = Lab::all();
        return view($this->role. '.forms.create', compact('labs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'lab_id' => 'nullable|exists:laboratories,id',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|in:text,number,checkbox,radio,textarea',
        ]);

        DB::beginTransaction();

        try {
            $form = Form::create([
                'title' => $request->title,
                'lab_id' => $request->lab_id,
            ]);

            foreach ($request->questions as $question) {
                Form_question::create([
                    'form_id' => $form->id,
                    'question_text' => $question['question_text'],
                    'type' => $question['type'],
                    'options' => in_array($question['type'], ['radio', 'checkbox']) 
                        ? json_encode($question['options'] ?? [])
                        : null,
                ]);
            }

            DB::commit();
            return redirect()->route($this->role. '.form.index')->with('success', 'Form add successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to add form.')->withInput();
        }
    }

    public function edit($id)
    {
        $form = Form::with('questions')->findOrFail($id);
        $labs = Lab::all();
        return view($this->role. '.forms.edit', compact('form', 'labs'));
    }

    public function update(Request $request, $id)
    {
        $form = Form::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'lab_id' => 'required|exists:laboratories,id',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|in:text,number,checkbox,radio,textarea',
        ]);

        DB::beginTransaction();

        try {
            $form->update([
                'title' => $request->title,
                'lab_id' => $request->lab_id,
            ]);

            // Delete old questions
            $form->questions()->delete();

            // Add updated/new questions
            foreach ($request->questions as $question) {
                Form_question::create([
                    'form_id' => $form->id,
                    'question_text' => $question['question_text'],
                    'type' => $question['type'],
                    'options' => in_array($question['type'], ['radio', 'checkbox']) 
                        ? json_encode($question['options'] ?? [])
                        : null,
                ]);
            }

            DB::commit();
            return redirect()->route($this->role. '.form.index')->with('success', 'Form updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update form.')->withInput();
        }
    }

    public function destroy($id)
    {
        $form = Form::findOrFail($id);
        $form->questions()->delete();
        $form->delete();

        return redirect()->route($this->role. '.form.index')->with('success', 'Form deleted successfully.');
    }
}
