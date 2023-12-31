<?php

namespace App\Http\Controllers;

use App\Models\DestinationCategory;
use App\Models\DestinationPreference;
use App\Models\Place;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

use function PHPUnit\Framework\isEmpty;

class HomeController extends Controller
{
    public function index(): View
    {
        $destinationCategories = DestinationCategory::all();

        $places = Place::with('placeImages')->limit(4)->get();

        return view('home.index', compact('destinationCategories', 'places'));
    }

    public function getPreference(): JsonResponse
    {
        $id = request()->id;
        $destinationPreferences = DestinationPreference::where('destination_category_id', $id)->get();

        return response()->json(['data' => $destinationPreferences]);
    }

    public function filter(): JsonResponse
    {
        $finalScores = [];
        $id = request()->id;
        $places = Place::with(['destinationPreference', 'destinationPreference.destinationCategory', 'placeImages'])
            ->where('destination_preference_id', $id)
            ->get();

        if (count($places)) {
            // Ambil semua tempat wisata dan preferensi destinasi
            // $places = Place::all();
            $destinationPreferences = DestinationPreference::with('destinationCategory')->get();

            // Inisialisasi matrix perbandingan berpasangan
            $matrix = [];

            // Isi matrix berdasarkan data dari database
            foreach ($destinationPreferences as $preference) {
                $subMatrix = [];
                foreach ($places as $place) {
                    $subMatrix[$place->slug] = $place->destinationPreference->score; // Sesuaikan dengan nilai sesuai preferensi Anda
                }
                $matrix[$preference->slug] = $subMatrix;
            }

            // Hitung bobot prioritas kriteria
            $weights = [];
            foreach ($matrix as $key => $row) {
                $weights[$key] = count($row) > 0 ? array_sum($row) / count($row) : 0;
            }

            // Normalisasi matrix perbandingan berpasangan
            foreach ($matrix as $key => &$row) {
                foreach ($row as &$value) {
                    $value /= $weights[$key];
                }
            }

            // Hitung bobot prioritas subkriteria
            $subCriteriaWeights = [];
            foreach ($matrix as $key => $row) {
                $subCriteriaWeights[$key] = array_sum($row) / count($row);
            }

            // Hasil akhir
            foreach ($places as $place) {
                $finalScore = 0;
                foreach ($matrix as $key => $row) {
                    if (
                        $subCriteriaWeights[$key] != 0
                    ) {
                        $finalScore += $row[$place->slug] * $subCriteriaWeights[$key];
                    }
                }
                $finalScores[$place->name] = $finalScore;
            }

            // Sort tempat wisata berdasarkan nilai akhir
            arsort($finalScores);
        }

        return response()->json(['data' => $places, 'finalScores' => $finalScores]);
    }
}
