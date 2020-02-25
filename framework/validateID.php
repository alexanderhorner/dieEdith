<?php
function validateID($type, $ID)
{
    // Check type parameter 
    if ($type !== 'U' && $type !== 'A' && $type !== 'P') {
        return false;
    } else {

        // Check length
        if (strlen($ID) != 11) {
            return false;
        } else {
            
            // type 
            $typeofID = substr($ID, 0, 1);
            if ($typeofID !== $type) {
                return false;
            } else {

                // Check ID 
                if (preg_match('/[^A-Za-z0-9-_]/', substr($ID, 1, 10))) {
                    return false;
                } else {
                    return true;
                }
            }
        }
    }
}



?>