@extends('layouts.app')

@section('styles')
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #28a745;
            color: white;
        }

        tr:hover {
            background-color: #f9f9f9;
        }
    </style>
@section('content')


    <div class="card mb-5">
        @if (session('massage'))
            <div id="session-message" class="alert alert-success alert-dismissible fade show text-center fw-bold"
                role="alert">
                {{ session('massage') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Patient ({{ $patient->count() }})</h5>

            <a href="" class="btn btn-custom">
                <i class="fa-solid fa-user-plus"></i> Add new Patient
            </a>
        </div>
        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Diagnosis</th>
                        <th>Birth Date</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>operation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patient as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->Diagnosis }}</td>
                            <td>{{ $item->birth_date }}</td>
                            <td>{{ $item->gender }}</td>
                            <td>{{ $item->address }}</td>
                            <td>
                                <a href={{ route('patient.show', $item->id) }} class="btn btn-success">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href='*' class="btn btn-primary">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <a href={{ Route('patient.delete', $item->id) }} class="btn btn-danger">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection
