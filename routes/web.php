<?php

Route::post('/upload','UploadController@upload')->name('upload.file');
Route::post('/upload/base64','UploadController@uploadBase64')->name('upload.base64');
