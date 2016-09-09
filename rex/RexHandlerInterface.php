<?php

namespace rex;

interface RexHandlerInterface {

    /**
     * @param RexResponse $response
     * @param RexRequest $request
     * @return mixed
     */
    public function handle(RexResponse $response, RexRequest $request);
}