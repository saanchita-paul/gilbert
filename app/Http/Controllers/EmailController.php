<?php

use App\Http\Controllers\Controller;

class EmailController extends Controller {
    public function index(Request $request) {
        return response('hello world');
    }
}
