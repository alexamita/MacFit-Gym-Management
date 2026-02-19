<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
      // Create categories
    public function createCategory(Request $request){
        $validated = $request->validate([
            'name'=>'required|string|unique:categories,name',
            'description'=>'nullable|string|max:1000',
            ]);
            $category = new Category();
            $category->name = $validated['name'];
            $category->description = $validated['description'];

            try{
                $createdCategory = $category->save();
                return response()->json($category);
            }
            catch(\Exception $exception){
                return response()->json(
                    ['error'=>'Failed to save category',
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Read all categories
    public function readAllCategories(){
        try{
            $categories = Category::all();
            return response()->json($categories);
            }
            catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch categories',
                    'message'=> $exception->getMessage()
                ]);
            }
    }
    // Read single category
    public function readCategory($id){
        try {
            $category = Category::findOrFail($id);
            return response()->json($category);
        }
        catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch the category with ID: ',&$id,
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Update category
    public function updateCategory(Request $request, $id){

            $validated = $request->validate([
                'name'=>'required|string|unique:categorys,name',
                'description'=>'nullable|string|max:1000',
            ]);
            $existingCategory = Category::findOrFail($id);
            $existingCategory->name = $validated['name'];
            $existingCategory->description = $validated['description'];

        try{
            $existingCategory->save();
            return response()->json($existingCategory);
        }
        catch(\Exception $exception){
            return response()->json([
            'error'=> 'Failed to fetch the category with ID: '.$id,
            'message'=> $exception->getMessage()
            ]);
        }
    }

    // Delete category
    public function deleteCategory($id){
        try{
            $category = Category::findOrFail($id);
            $category->delete();
            return response("Category deleted successfully");
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=> 'Failed to delete the category',
                'message'=> $exception->getMessage()
            ]);
        }
    }
}
