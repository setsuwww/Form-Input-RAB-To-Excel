<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("dashboard");
})->name("dashboard");
Route::get("/documents/new", function () {
    return view("documents.form");
})->name("documents.create");
Route::get("/documents/{id}/edit", function ($id) {
    return view("documents.form", ["id" => $id]);
})->name("documents.edit");
Route::get("/rekap", function () {
    return view("documents.rekap");
})->name("rekap");
Route::get("/master/brands", function () {
    return view("master.brands");
})->name("master.brands");
Route::get("/master/types", function () {
    return view("master.types");
})->name("master.types");
Route::get("/backup", function () {
    return view("backup");
})->name("backup");
