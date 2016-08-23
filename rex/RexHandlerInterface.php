<?php

namespace rex;

interface RexHandlerInterface {

	/**
	 * @param RexRequest $request
	 * @return mixed
	 */
	public function handle(RexRequest $request);
}