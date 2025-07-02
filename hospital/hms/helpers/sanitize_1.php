<?php
// update query security
function sanitize_1($d) { return htmlspecialchars($d, ENT_QUOTES, "UTF-8"); }
