<?php

namespace rex;

interface RexHandlerInterface {
    public function handle(RexRequest $request);
}