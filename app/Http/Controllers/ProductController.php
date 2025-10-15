<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Cloudinary\Cloudinary;
use Exception;
use GuzzleHttp\Client;

class ProductController extends Controller
{
    private $cloudinary;

    public function __construct()
    {
        // Configuración simple de Cloudinary
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key'    => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ]
        ]);
    }

    /**
     * Probar conectividad con Cloudinary
     */
    public function testCloudinary()
    {
        try {
            // Intentar obtener información básica de la cuenta
            $result = $this->cloudinary->adminApi()->ping();

            return response()->json([
                'success' => true,
                'message' => 'Conexión exitosa con Cloudinary',
                'data' => $result
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error conectando con Cloudinary: ' . $e->getMessage(),
                'error' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Subir imagen con fallback local si falla Cloudinary
     */
    private function uploadImage($file)
    {
        Log::info('Iniciando subida a Cloudinary...');

        try {
            // Método directo usando cURL para evitar problemas SSL
            $uploadResult = $this->uploadToCloudinaryDirect($file);

            Log::info('Imagen subida exitosamente a Cloudinary: ' . $uploadResult['secure_url']);

            return [
                'success' => true,
                'url' => $uploadResult['secure_url'],
                'public_id' => $uploadResult['public_id']
            ];
        } catch (Exception $e) {
            Log::error('Fallo Cloudinary, usando almacenamiento local: ' . $e->getMessage());

            // Fallback a almacenamiento local
            $path = $file->store('products', 'public');

            return [
                'success' => true,
                'url' => asset('storage/' . $path),
                'public_id' => null,
                'local' => true
            ];
        }
    }

    /**
     * Subir imagen directamente usando cURL nativo
     */
    private function uploadToCloudinaryDirect($file)
    {
        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload";

        // Crear timestamp y signature
        $timestamp = time();
        $params = [
            'timestamp' => $timestamp,
            'folder' => 'products',
            'transformation' => 'w_800,h_600,c_limit,q_auto'
        ];

        // Crear signature
        $signature = $this->createCloudinarySignature($params, $apiSecret);

        // Preparar datos para POST
        $postFields = array_merge($params, [
            'file' => new \CURLFile($file->getRealPath(), $file->getMimeType(), $file->getClientOriginalName()),
            'api_key' => $apiKey,
            'signature' => $signature
        ]);

        // Configurar cURL
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_error($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL Error: " . $error);
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("HTTP Error: " . $httpCode . " Response: " . $response);
        }

        $result = json_decode($response, true);

        if (!$result || !isset($result['secure_url'])) {
            throw new Exception("Invalid response from Cloudinary: " . $response);
        }

        return $result;
    }

    /**
     * Crear signature para Cloudinary
     */
    private function createCloudinarySignature($params, $apiSecret)
    {
        ksort($params);
        $query = [];
        foreach ($params as $key => $value) {
            $query[] = $key . '=' . $value;
        }
        $queryString = implode('&', $query) . $apiSecret;
        return sha1($queryString);
    }    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::latest()->get();
        } catch (Exception $e) {
            // Si las tablas no existen, ejecutar migraciones
            try {
                Artisan::call('migrate', ['--force' => true]);
                $products = Product::latest()->get();
            } catch (Exception $e2) {
                // Si aún falla, mostrar productos vacíos
                $products = collect([]);
            }
        }

        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imageUrl = null;
        $imagePublicId = null;

        // Subir imagen si se proporciona
        if ($request->hasFile('image')) {
            $uploadResult = $this->uploadImage($request->file('image'));

            if ($uploadResult['success']) {
                $imageUrl = $uploadResult['url'];
                $imagePublicId = $uploadResult['public_id'];
            } else {
                return back()->withErrors(['image' => 'Error al subir la imagen. Por favor, inténtalo de nuevo.']);
            }
        }        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image_url' => $imageUrl,
            'image_public_id' => $imagePublicId,
        ]);

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imageUrl = $product->image_url;
        $imagePublicId = $product->image_public_id;

        // Subir nueva imagen si se proporciona
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior (Cloudinary o local)
            $this->deleteImage($product);

            // Subir nueva imagen
            $uploadResult = $this->uploadImage($request->file('image'));

            if ($uploadResult['success']) {
                $imageUrl = $uploadResult['url'];
                $imagePublicId = $uploadResult['public_id'];
            }
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image_url' => $imageUrl,
            'image_public_id' => $imagePublicId,
        ]);

        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Eliminar imagen (Cloudinary o local)
        $this->deleteImage($product);

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }

    /**
     * Eliminar imagen (Cloudinary o local)
     */
    private function deleteImage(Product $product)
    {
        try {
            if ($product->image_public_id) {
                // Es imagen de Cloudinary - eliminar usando cURL directo
                $this->deleteFromCloudinaryDirect($product->image_public_id);
                Log::info('Imagen eliminada de Cloudinary: ' . $product->image_public_id);
            } elseif ($product->image_url && str_contains($product->image_url, '/storage/')) {
                // Es imagen local - eliminar del storage
                $path = str_replace(asset('storage/'), '', $product->image_url);
                $fullPath = storage_path('app/public/' . $path);

                if (file_exists($fullPath)) {
                    unlink($fullPath);
                    Log::info('Imagen local eliminada: ' . $fullPath);
                }
            }
        } catch (Exception $e) {
            Log::error('Error eliminando imagen: ' . $e->getMessage());
            // No lanzar excepción para no interrumpir la eliminación del producto
        }
    }

    /**
     * Eliminar imagen de Cloudinary usando cURL directo
     */
    private function deleteFromCloudinaryDirect($publicId)
    {
        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy";

        // Crear timestamp y signature
        $timestamp = time();
        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp
        ];

        // Crear signature
        $signature = $this->createCloudinarySignature($params, $apiSecret);

        // Preparar datos para POST
        $postFields = array_merge($params, [
            'api_key' => $apiKey,
            'signature' => $signature
        ]);

        // Configurar cURL
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postFields),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_error($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL Error eliminando imagen: " . $error);
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("HTTP Error eliminando imagen: " . $httpCode . " Response: " . $response);
        }

        $result = json_decode($response, true);

        if (!$result || $result['result'] !== 'ok') {
            throw new Exception("Error en respuesta de Cloudinary: " . $response);
        }

        return $result;
    }
}
