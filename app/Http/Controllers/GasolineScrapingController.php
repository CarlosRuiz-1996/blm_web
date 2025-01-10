<?php

namespace App\Http\Controllers;

use App\Models\FuelPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class GasolineScrapingController extends Controller
{
    public function scrapePrices()
{
    $url = 'https://www.gasolinamx.com';

    // Realizar solicitud con un User-Agent personalizado
try {
    $response = Http::withHeaders([
        'User-Agent' => 'MiAppScrapingBot/1.0'
    ])->get($url);

    // Validar si la respuesta fue exitosa (código 200)
    if ($response->failed()) {
        // Puedes registrar el error para mayor trazabilidad
        return 'No se pudo acceder a la página.';
    }

    $html = $response->body();
    $crawler = new Crawler($html);

    // Extraer los precios y validarlos
    $prices = $crawler->filter('.gprice')->each(function (Crawler $node) {
        $infoText = $node->filter('.gpinfo')->text();
        
        // Verificar si la información contiene un precio válido
        preg_match('/\b\d+(\.\d{1,2})?\b/', $infoText, $matches);

        // Si no se encuentra un precio, omitir este elemento
        if (empty($matches[0])) {
            return null;
        }

        $fuelType = explode(' ', trim($infoText))[0];
        $price = (float) $matches[0];  // Convertir a número flotante para mayor precisión
        
        // Verificar que el precio es un número positivo válido
        if ($price <= 0) {
            return null;  // Si el precio no es válido, omitir el registro
        }

        // Extraer la descripción del tipo de combustible
        $description = $node->filter('.gpinfo span')->text();
        
        // Validar que la descripción no esté vacía
        if (empty($description)) {
            return null;
        }

        // Validación de fecha
        $fecha = now()->toDateString();  // La fecha actual

        // Validar que todos los datos requeridos están presentes y correctos
        if (empty($fuelType) || empty($price) || empty($description) || empty($fecha)) {
            return null;  // Omite si algún campo está vacío o inválido
        }

        return [
            'type' => $fuelType,
            'price' => $price,
            'description' => $description,
            'fecha' => $fecha, // Fecha actual
        ];
    });

    // Filtrar los precios nulos si alguna extracción falló
    $prices = array_filter($prices);

    // Validación para asegurarse de que no está vacío el array de precios
    if (empty($prices)) {
        return 'No se encontraron datos válidos para almacenar.';
    }

    // Guardar o actualizar los precios en la base de datos
    foreach ($prices as $priceData) {
        try {
            FuelPrice::updateOrCreate(
                ['type' => $priceData['type'], 'fecha' => $priceData['fecha']], // Identificar por tipo y fecha
                ['price' => $priceData['price'], 'description' => $priceData['description']]
            );
        } catch (\Exception $e) {
            return 'Datos de precios almacenados incorrectamente.';
        }
    }

    return 'Datos de precios almacenados correctamente.';
} catch (\Exception $e) {
    // Captura cualquier otra excepción
    return 'Hubo un problema al acceder a la página o procesar los datos.';
}
}

}
