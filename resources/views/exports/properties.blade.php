<!DOCTYPE html>
<html>
<head>
    <title>Exportar Propiedades</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div style="display: flex; align-items: center; justify-content: center;">
        <img src="{{ $logoPath }}" alt="Logo" style="height: 50px; margin-right: 10px;">
    </div>
    <h1 style="margin-top: 20px">Lista de Propiedades</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Status</th>
                <th>Country</th>
                <th>State</th>
                <th>City</th>
                <th>Property Type Name</th>
                <th>Created At</th>
                <th>Expected End Date ROS</th>
                <th>Log User Name</th>
                <th>Owners Name</th>
                <th>Tenants Name</th>
                <th>Insurances</th>
                <th>Incidents</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $property)
                <tr>
                    <td>{{ $property->name }}</td>
                    <td>{{ $property->address }}</td>
                    <td>{{ $property->status }}</td>
                    <td>{{ $property->country }}</td>
                    <td>{{ $property->state }}</td>
                    <td>{{ $property->city }}</td>
                    <td>{{ $property->property_type_name }}</td>
                    <td>{{ $property->created_at }}</td>
                    <td>{{ $property->expected_end_date_ros }}</td>
                    <td>{{ $property->log_user_name }}</td>
                    <td>{{ $property->owners_name }}</td>
                    <td>{{ $property->tenants_name }}</td>
                    <td>{{ $property->insurances }}</td>
                    <td>{{ $property->incidents }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>