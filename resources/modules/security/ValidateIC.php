<?php

/*
 * author: yanying (Tracie)
 */

class ValidateIC {

    private string $nric;

    public function __construct(string $nric) {
        $this->nric = $nric;
    }

    private function nric_calculation(string $nric_upper): int {
        # Loop String To An Array
        $ic_arr = array();

        # Some Calculation
        $ic_arr[1] = (int) $nric_upper[1] * 2;
        $ic_arr[2] = (int) $nric_upper[2] * 7;
        $ic_arr[3] = (int) $nric_upper[3] * 6;
        $ic_arr[4] = (int) $nric_upper[4] * 5;
        $ic_arr[5] = (int) $nric_upper[5] * 4;
        $ic_arr[6] = (int) $nric_upper[6] * 3;
        $ic_arr[7] = (int) $nric_upper[7] * 2;

        $total_sum = 0;
        for ($i = 1; $i < 8; $i++) {
            $total_sum += $ic_arr[$i];
        }

        # Foreign NRIC Need To Add 4 To The $total_sum
        $offset = ($nric_upper[0] === "T" || $nric_upper[0] == "G") ? 4 : 0;
        $checksum_index = ($offset + $total_sum) % 11;

        return $checksum_index;
    }

    // VALIDATE
    public function validate_nric(): bool {

        # Check NRIC IS 9 CHARS
        if (strlen($this->nric) !== 9) {
            return false;
        }

        # Make All The Chars To Upper
        $nric_upper = strtoupper($this->nric);
        $checksum_index = $this->nric_calculation($nric_upper);

        # Checksums For NRIC
        $st = ["J", "Z", "I", "H", "G", "F", "E", "D", "C", "B", "A"];
        $fg = ["X", "W", "U", "T", "R", "Q", "P", "N", "M", "L", "K"];

        # Validate The Checksum
        $checksum = "";
        if ($nric_upper[0] == "S" || $nric_upper[0] == "T") {
            # SINGAPORE CITIZEN
            $checksum = $st[$checksum_index];
        } else if ($nric_upper[0] == "F" || $nric_upper[0] == "G") {
            # FOREIGNER
            $checksum = $fg[$checksum_index];
        }

        return ($nric_upper[8] === $checksum);
    }

}

?>
