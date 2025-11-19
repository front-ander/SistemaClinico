<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?> - Policlínico San Gabriel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .table thead th { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between mb-3 no-print">
            <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir / Guardar PDF</button>
            <button onclick="window.close()" class="btn btn-secondary">Cerrar</button>
        </div>

        <div class="header">
            <h1>Policlínico San Gabriel</h1>
            <h3><?php echo $title; ?></h3>
            <p>Fecha de Emisión: <?php echo date('d/m/Y H:i'); ?></p>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <?php foreach ($columns as $col): ?>
                        <th><?php echo $col; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php foreach ($row as $cell): ?>
                            <td><?php echo htmlspecialchars($cell ?? ''); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="mt-4 text-center text-muted">
            <p>Fin del Reporte</p>
        </div>
    </div>
</body>
</html>
