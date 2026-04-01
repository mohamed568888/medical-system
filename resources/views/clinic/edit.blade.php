@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 py-4">
                    <h4 class="text-success fw-bold mb-0">
                        <i class="fa-solid fa-pen-to-square me-2"></i> Edit Clinic
                    </h4>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('clinic.update', $clinic->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- مهم جداً لإرسال طلب التحديث --}}

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Clinic Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $clinic->name) }}" required 
                                   style="border-radius: 10px; border-color: #e0e0e0;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Address</label>
                            <input type="text" name="address" class="form-control" 
                                   value="{{ old('address', $clinic->address) }}"
                                   style="border-radius: 10px; border-color: #e0e0e0;">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success fw-bold py-2 shadow-sm" style="border-radius: 10px;">
                                <i class="fa-solid fa-save me-1"></i> Save Changes
                            </button>
                            <a href="{{ route('clinic.index') }}" class="btn btn-light py-2 text-muted" style="border-radius: 10px;">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection