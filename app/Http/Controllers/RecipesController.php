<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\CSV;

class RecipesController extends Controller
{
    protected $_recipeParams = [
        'box_type',
        'title',
        'slug',
        'short_title',
        'marketing_description',
        'calories_kcal',
        'protein_grams',
        'fat_grams',
        'carbs_grams',
        'bulletpoint1',
        'bulletpoint2',
        'bulletpoint3',
        'recipe_diet_type_id',
        'season',
        'base',
        'protein_source',
        'preparation_time_minutes',
        'shelf_life_days',
        'equipment_needed',
        'origin_country',
        'recipe_cuisine',
        'in_your_box',
        'gousto_reference'
    ];

    public function index(Request $request) {
        $limit = $request->input('limit', 0);
        $page = $request->input('page', 1);
        $type = $request->input('type', null);

        if (isset($page) && ($page > 0)) {
            $offset = ($page - 1) * $limit;
        } else {
            $offset = 0;
        }

        $cuisineType = ($type) ? ['recipe_cuisine' => $type] : [];
        
        $csv = new CSV($this->_getCSVFile());
        $data = $csv->fetchAll($cuisineType, $offset, $limit);

        return response()->json($this->_generateResponseData($data));
    }

    public function view($recipeId) {
        $csv = new CSV($this->_getCSVFile());
        $data = $csv->fetchBy('id', $recipeId);

        return response()->json($this->_generateResponseData($data));
    }

    public function rate(Request $request, $recipeId) {
        $rating = $request->input('rating', null);

        //TODO Store rating with recipeId

        return response()->json($this->_generateResponseData('Recipe rated successfully'));
    }

    public function add(Request $request) {
        $params = $request->only($this->_recipeParams);

        $csv = new CSV($this->_getCSVFile());
        if ($csv->add($params)) {
            $result = 'Recipe added successfully';
        } else {
            $result = 'Error adding recipe';
        }

        return response()->json($this->_generateResponseData($result));
    }

    public function update(Request $request, $recipeId) {
        $params = $request->only($this->_recipeParams);

        $csv = new CSV($this->_getCSVFile());
        if ($csv->update($recipeId, $params)) {
            $result = 'Recipe updated successfully';
        } else {
            $result = 'Error updating recipe';
        }

        return response()->json($this->_generateResponseData($result));
    }

    protected function _getCSVFile() {
        return storage_path('csv') . '/recipes.csv';
    }
}