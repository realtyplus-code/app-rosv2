<!DOCTYPE html>
<html>
<head>
    <title>Exportar Propiedades</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
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
    <h1>Lista de Propiedades</h1>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Tipo de Propiedad</th>
                <th>Propietarios</th>
                <th>Inquilinos</th>
                <th>Seguros</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $property)
                <tr>
                    <td>{{ $property->name }}</td>
                    <td>{{ $property->address }}</td>
                    <td>{{ $property->status }}</td>
                    <td>{{ $property->property_type_name }}</td>
                    <td>{{ $property->owners_name }}</td>
                    <td>{{ $property->tenants_name }}</td>
                    <td>{{ $property->insurances }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
