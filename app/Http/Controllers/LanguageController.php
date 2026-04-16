<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Switch application language
     *
     * @param Request $request
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request, string $locale)
    {
        if (in_array($locale, ['ar', 'en', 'ku'], true)) {
            session(['locale' => $locale]);
            app()->setLocale($locale);
        }

        // Honor explicit next param (must be a relative path to prevent open redirect)
        $next = $request->query('next', '');
        if ($next && str_starts_with($next, '/')) {
            return redirect($next);
        }

        return redirect()->back()->with('locale_changed', true);
    }
}

