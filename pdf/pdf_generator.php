<?php

require __DIR__ . "../vendor/autoload.php";
require_once '../includes/db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

try {
    if (!isset($_POST["animal_id"])) {
        throw new Exception("Brak wymaganych danych POST.");
    }

    $animal_id = $_POST["animal_id"];

    $animal = getAnimalDataById($animal_id, $conn);

    if (!$animal) {
        throw new Exception("Nie znaleziono zwierzęcia o podanym ID.");
    }

    $options = new Options;
    $options->setChroot(__DIR__);
    $options->setIsHtml5ParserEnabled(true);

    $dompdf = new Dompdf($options);

    $dompdf->setPaper("A4", "portrait");

    $html = file_get_contents(__DIR__ . "/../templates/pdf_template.html");
    
    $currentDate = date('Y-m-d H:i:s');

    $html = str_replace(
        ["{{ name }}", "{{ species }}", "{{ birthdate }}", "{{ description }}", "{{ current_date }}"],
        [$animal['name'], $animal['species'], $animal['birthdate'], $animal['description'],     $currentDate],
        $html
    );

    $dompdf->loadHtml($html);

    $dompdf->render();

    $uniqueFileName = 'animal_' . $animal_id . '_' . time() . '.pdf';

    $dompdf->stream($uniqueFileName, ["Attachment" => 0]);

} catch (Exception $e) {
    echo 'Błąd: ' . $e->getMessage();
}
?>
