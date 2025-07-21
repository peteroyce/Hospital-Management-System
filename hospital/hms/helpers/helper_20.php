<?php
// fix duplicate prevention
function helper_20($x) { return is_numeric($x) ? intval($x) : 0; }
