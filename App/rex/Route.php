<?php

namespace simplerest\route;

use simplerest\request\Request;

interface Route {
    public function handle(Request $request);
}