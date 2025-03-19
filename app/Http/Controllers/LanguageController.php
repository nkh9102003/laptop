<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Change the application language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\Response
     */
    public function switch(Request $request, $locale)
    {
        if (in_array($locale, ['en', 'vi'])) {
            Session::put('locale', $locale);
        }
        
        // Redirect back to the previous page
        return redirect()->back();
    }
} 