<?php

namespace rex\route;

use rex\request\Request;

interface Route {
    public function handle(Request $request);
}