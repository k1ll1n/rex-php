<?php

namespace rex\utils;

class RexError {
	public static function showError($message) {
	    echo json_encode($message);
    }
}