<?php

interface Route {
    public function handle(Request $request);
}