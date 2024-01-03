<?php

namespace App\Http\Livewire\Admin;

use App\Models\Material;
use App\Models\MaterialTechnique;
use App\Models\PricesTechnique;
use App\Models\Size;
use App\Models\SizeMaterialTechnique;
use App\Models\Technique;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Clase ImportTechniques
 *
 * Esta clase es responsable de importar técnicas desde un archivo y guardar la información en la base de datos.
 */
class ImportTechniques extends Component
{
    use WithFileUploads;

    // Datos iniciales
    public $fileLayout, $tipo, $updateProducts = false;

    // Dastos despues del paso 1
    public $rutaArchivo;
    public $archivo;

    /**
     * Renderiza la vista de importación de técnicas.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('admin.personalizacion.import-techniques.import-techniques');
    }

    /**
     * Guarda la información importada en la base de datos.
     *
     * @return void
     */
    public function save()
    {
        $this->validate([
            'fileLayout' => 'required', // 1MB Max
        ]);

        // Mostrar columnas
        $path = time() . $this->fileLayout->getClientOriginalName();
        $this->fileLayout->storeAs('public/imports', $path);
        $this->rutaArchivo = public_path('storage/imports/' . $path);
        $this->archivo = $path;

        // Leer el archivo y obtener la información
        $documento = IOFactory::load($this->rutaArchivo);
        $hojaActual = $documento->getSheet(0);
        $letraMayorDeColumna = $hojaActual->getHighestColumn(); // Numérico
        $numeroMayorDeColumna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($letraMayorDeColumna);
        $numeroMayorDeFila = $hojaActual->getHighestRow();
        $columns = [];
        for ($indiceCol = 1; $indiceCol <= $numeroMayorDeColumna; $indiceCol++) {
            array_push($columns, $hojaActual->getCellByColumnAndRow($indiceCol, 1)->getValue());
        }

        // Verificar si todas las columnas están presentes
        if ($numeroMayorDeColumna < 8) {
            session()->flash('error', "Al parecer no están todas las columnas");
            return;
        }

        // Procesar la información de cada fila
        $information = [];
        $errores = [];
        for ($indiceFila = 2; $indiceFila <= $numeroMayorDeFila; $indiceFila++) {
            $data = [
                "material" => $hojaActual->getCellByColumnAndRow(1, $indiceFila)->getValue(),
                "extras" => $hojaActual->getCellByColumnAndRow(2, $indiceFila)->getValue(),
                "technique" => $hojaActual->getCellByColumnAndRow(5, $indiceFila)->getValue(),
                "size" => $hojaActual->getCellByColumnAndRow(6, $indiceFila)->getValue(),
                "start" => $hojaActual->getCellByColumnAndRow(3, $indiceFila)->getValue(),
                "end" => $hojaActual->getCellByColumnAndRow(4, $indiceFila)->getValue(),
                "price" => $hojaActual->getCellByColumnAndRow(7, $indiceFila)->getValue(),
                "type" => $hojaActual->getCellByColumnAndRow(8, $indiceFila)->getValue()
            ];

            // Verificar si hay valores incorrectos o vacíos en la fila
            foreach ($data as $key => $value) {
                if (trim($value) == "" || $value == null) {
                    array_push($errores, [$indiceFila, $key, $value]);
                }
            }
            array_push($information, $data);
        }

        // Mostrar errores si los hay
        if (count($errores) > 0) {
            $message = '';
            foreach ($errores as $value) {
                $message = $message . 'En la fila ' . $value[0] . ', columna: ' . $value[1] . ' tienes un valor incorrecto o vacío<br>';
            }
            session()->flash('error', $message);
            return;
        }

        // Eliminar los datos anteriores
        try {
            DB::table('materials')->where('active', 1)->update(['active' => 0]);
        } catch (Exception $th) {
            session()->flash('error', $th->getMessage());
        }

        // Comenzar a cargar las técnicas
        foreach ($information as $dataInfo) {
            try {
                // Registrar el material si no existe
                $slugMaterial = mb_strtolower(str_replace(' ', '-', $dataInfo['material']));
                $material = Material::where("slug", $slugMaterial)->where('active', 1)->first();
                if (!$material) {
                    $material = Material::create([
                        'nombre' => $dataInfo['material'],
                        'extras' => $dataInfo['extras'],
                        'slug' => $slugMaterial,
                    ]);
                }

                // Registrar la técnica si no existe
                $slugTecnique = mb_strtolower(str_replace(' ', '-', $dataInfo['technique']));
                $technique = Technique::where("slug", $slugTecnique)->first();
                if (!$technique) {
                    $technique = Technique::create([
                        'nombre' => $dataInfo['technique'],
                        'slug' => $slugTecnique,
                    ]);
                }

                // Registrar la relación entre el material y la técnica
                $materialTechnique = MaterialTechnique::where('technique_id', $technique->id)->where('material_id', $material->id)->first();
                if (!$materialTechnique) {
                    $materialTechnique = MaterialTechnique::create([
                        'technique_id' => $technique->id,
                        'material_id' => $material->id
                    ]);
                }

                // Registrar el tamaño si no existe
                $slugSize = mb_strtolower(str_replace(' ', '-', $dataInfo['size']));
                $size = Size::where("slug", $slugSize)->first();
                if (!$size) {
                    $size = Size::create([
                        'nombre' => $dataInfo['size'],
                        'slug' => $slugSize,
                    ]);
                }

                // Registrar la relación entre el tamaño, el material y la técnica
                $sizeMaterialTechnique = SizeMaterialTechnique::where('size_id', $size->id)->where('material_technique_id', $materialTechnique->id)->first();
                if (!$sizeMaterialTechnique) {
                    $sizeMaterialTechnique = SizeMaterialTechnique::create([
                        'size_id' => $size->id,
                        'material_technique_id' => $materialTechnique->id
                    ]);
                }

                // Registrar el precio de la técnica
                $priceTechnique = PricesTechnique::create([
                    'size_material_technique_id' => $sizeMaterialTechnique->id,
                    'escala_inicial' => floatval($dataInfo['start']),
                    'escala_final' => $dataInfo['end'] == '-' ? null : floatval($dataInfo['end']),
                    'precio' => floatval($dataInfo['price']),
                    'tipo_precio' => $dataInfo['type']
                ]);
            } catch (Exception $th) {
                session()->flash('error', "Error al insertar la información, revise el archivo e inténtelo de nuevo");
            }
        }

        session()->flash('message', "Actualización realizada correctamente");
    }
}
