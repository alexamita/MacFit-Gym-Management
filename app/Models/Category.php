<?php
// Category model representing a fitness category that bundles can belong to and can be used for organizing and filtering bundles
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    protected $fillable = [
        "name",
        "description",
    ];
}
