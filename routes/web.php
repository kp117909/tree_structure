<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [CategoryController::class, 'categoryIndex']);

Route::get('categoryIndex/{sort_type}', [CategoryController::class, 'categoryIndex']);

Route::get('tree.addCategory', [CategoryController::class, 'addCategory'])->name('tree.addCategory');

Route::post('tree.addCategoryForm', [CategoryController::class, 'addCategoryForm'])->name('tree.addCategoryForm');

Route::get('tree.editCategory', [CategoryController::class, 'editCategory'])->name('tree.editCategory');

Route::get('tree.deleteCategory', [CategoryController::class, 'deleteCategory'])->name('tree.deleteCategory');

Route::get('tree.editPlace', [CategoryController::class, 'editCategoryParent'])->name('tree.editPlace');

