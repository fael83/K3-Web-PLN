<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonitoringForm;
use Illuminate\Http\Request;
use App\Models\MonitoringFormField;

class MonitoringFormController extends Controller
{
    public function index()
    {
        $forms = MonitoringForm::latest()->get();

        return view('admin.monitoring_forms.index', compact('forms'));
    }

    public function create()
    {
        return view('admin.monitoring_forms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'frequency' => 'required',
            'assigned_role' => 'nullable',
        ]);

        MonitoringForm::create([
            'title' => $request->title,
            'description' => $request->description,
            'frequency' => $request->frequency,
            'assigned_role' => $request->assigned_role,
            'status' => 'active',
        ]);

        return redirect()
            ->route('admin.monitoring-forms.index')
            ->with('success', 'Form monitoring berhasil dibuat');
    }

    public function show(MonitoringForm $monitoring_form)
    {
        $fields = $monitoring_form->fields()
            ->orderBy('sort_order')
            ->get();

        return view(
            'admin.monitoring_forms.show',
            [
                'form' => $monitoring_form,
                'fields' => $fields,
            ]
        );
    }
    
    public function storeField(
        Request $request,
        MonitoringForm $monitoring_form
    )
    {
        $request->validate([
            'label' => 'required|max:255',
            'field_type' => 'required',
        ]);

        MonitoringFormField::create([
            'form_id' => $monitoring_form->id,
            'label' => $request->label,
            'field_type' => $request->field_type,
            'options' => $request->options,
            'sort_order' => 0,
        ]);

        return back()->with(
            'success',
            'Field berhasil ditambahkan'
        );
    }
}