<?php
header("Content-Type: application/json");

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

$producto = $data["producto"] ?? "";
$precio = $data["precio"] ?? 0;
$imagenBase64 = $data["imagen"] ?? "";
$categoria = $data["categoria"] ?? "";
$referencia = $data["referencia"] ?? "";
$imagen = guardarImagen($imagenBase64);

// conecar a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "mulcbd";



$conn = new mysqli($host, $user, $pass, $dbname);

$sql = "INSERT INTO `productos` (`nombre`, `precio`, `referencia`, `imagen`, `categoria`) VALUES ('$producto', '$precio', '$referencia', '$imagen', '$categoria');";
if($conn->query($sql) === TRUE) {
    echo json_encode([
        "mensaje" => "Producto registrado",
    ]);
} else {
    echo json_encode([
        "mensaje" => "Error al registrar el producto: " . $conn->error,
    ]);
}



/*echo json_encode([
    "status" => "success",
    "producto" => $producto,
    "precio" => $precio,
    "imagen" => $imagen,
    "categoria" => $categoria
]);*/

function guardarImagen($imagenBase64) {
    // This function is not used in the current code but can be implemented if needed
    if ($imagenBase64) {
        // Extract base64 data from the "data:image/...;base64," prefix
        if (preg_match('/^data:image\/(\w+);base64,/', $imagenBase64, $type)) {
            $imageType = strtolower($type[1]); // jpg, png, gif, etc.
            $imagenBase64 = substr($imagenBase64, strpos($imagenBase64, ',') + 1);
            $imagenBase64 = str_replace(' ', '+', $imagenBase64);
            $imageData = base64_decode($imagenBase64);

            if ($imageData === false) {
                return [false, "Error al decodificar la imagen"];
            }

            // Save image with unique name
            $fileName = uniqid("img_") . "." . $imageType;
            $filePath = "../../imagenes/" . $fileName;

            if (file_put_contents($filePath, $imageData)) {
                return $fileName;
            } else {
                return "Error al guardar la imagen";
            }
        } else {
            return "Formato de imagen inválido";
        }
    } else {
        return "No se recibió ninguna imagen";
    }
}
?>
