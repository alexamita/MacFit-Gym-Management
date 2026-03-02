<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Category;
class CategoryController extends Controller
{
    // Create categories
    public function createCategory(Request $request){
        // Policy check
        $this->authorize('create', Category::class);

        $validated = $request->validate([
            'name'=>'required|string|unique:categories,name',
            'description'=>'nullable|text|max:1000',
            ]);

            try{
                // Mass assignment and save
                $createdCategory = Category::create($validated);
                return response()->json($createdCategory);
            }
            catch(\Exception $exception){
                return response()->json(
                    ['error'=>'Failed to save category',
                    'message'=> $exception->getMessage()
                ], 500);
            }
    }

    // Read all categories
    public function readAllCategories(){
        // Policy check
        $this->authorize('viewAny', Category::class);

        try{
            return response()->json(Category::all());
            }
            catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch categories',
                    'message'=> $exception->getMessage()
                ], 500);
            }
    }

    // Read single category
    public function readCategory(Category $category ){
        // Route Model Binding handles findOrFail and 404 automatically
        $this->authorize('view', $category);
        return response()->json($category);
    }

    // Update category
    public function updateCategory(Request $request, Category $category){
        $this->authorize('update', $category);

        $validated = $request->validate([
            // unique:table,column,except,idColumn
            'name'=>'required|string|unique:categories,name',
            'description' => 'nullable|text',
        ]);

        try{
            $category->update($validated);
            return response()->json($category);
        }
        catch(\Exception $exception){
            return response()->json([
            'error'=> 'Failed to update the category with ID: '.$category->id,
            'message'=> $exception->getMessage()
            ], 500);
        }
    }

    // Delete category
    public function deleteCategory(Category $category){
        // Policy check (Gate::before bypasses this for Admin)
        $this->authorize('delete', $category);

        try{
            $category->delete();
            return response("Category deleted successfully", 200);
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=> 'Failed to delete the category',
                'message'=> $exception->getMessage()
            ], 500);
        }
    }
}
