<?php
session_start();
include("includes/db.php");
require('fpdf186/fpdf.php'); // asegúrate que esta ruta es correcta

// Verificar que haya productos en el carrito
if (empty($_SESSION['carrito'])) {
    die("El carrito está vacío.");
}

// Verificar que se haya enviado el método de pago
$metodo_pago = $_POST['metodo_pago'] ?? 'No especificado';

// Calcular total
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

// Disminuir stock en base de datos
foreach ($_SESSION['carrito'] as $item) {
    $id = $item['id'];
    $cantidad = $item['cantidad'];
    $sql = "UPDATE productos SET stock = stock - $cantidad WHERE id = $id";
    $conn->query($sql);
}

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,utf8_decode('Recibo de compra'),0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Cliente: ' . ($_POST['nombre'] ?? 'Invitado'),0,1);
$pdf->Cell(0,10,'Metodo de pago: ' . $metodo_pago,0,1); // <-- Método de pago
$pdf->Ln(5);

// Listado de productos
foreach ($_SESSION['carrito'] as $item) {
    $linea = $item['nombre'] . ' x' . $item['cantidad'] . ' - $' . ($item['precio'] * $item['cantidad']);
    $pdf->Cell(0,10, utf8_decode($linea),0,1);
}

$pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'Total: $' . number_format($total,2),0,1);

// Mostrar directamente en el navegador
$pdf->Output('I','recibo.pdf');

// Vaciar carrito después de generar recibo
//VARIABLE DE TIPO SESION
unset($_SESSION['carrito']);
exit;
?>
