<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;

class ShortLinkController extends Controller
{
    public function redirect(string $code)
    {
        $link = ShortLink::where('code', $code)->firstOrFail();

        if ($link->expires_at->isPast()) {
            abort(410, 'Link struk sudah kedaluwarsa.');
        }

        return redirect()->away($link->target_url);
    }
}
