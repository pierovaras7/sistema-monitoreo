<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Asistencias</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 5px;
            color: #1a202c;
        }
        .info-sesion {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #999;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .asistio {
            color: green;
            font-weight: bold;
        }
        .falto {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Lista de Asistencias</h1>
    
    <div class="info-sesion">
        <p><strong>Título:</strong> {{ $sesion->titulo }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($sesion->fecha_inicio)->format('d/m/Y H:i') }} - {{ \Carbon\Carbon::parse($sesion->fecha_fin)->format('H:i') }}</p>
        <p><strong>Enlace:</strong> {{ $sesion->link_reunion }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Alumno</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asistencias as $index => $asistencia)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $asistencia->alumno->nombre }}</td>
                    <td>{{ $asistencia->alumno->dni }}</td>
                    <td>{{ $asistencia->alumno->telefono }}</td>
                    <td class="{{ $asistencia->asistio ? 'asistio' : 'falto' }}">
                        {{ $asistencia->asistio ? 'Asistió' : 'Faltó' }}
                    </td>
                    <td>{{ $asistencia->observacion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
