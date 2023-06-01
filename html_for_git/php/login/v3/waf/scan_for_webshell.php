<?php
function scan_for_webshells($filename) {
  // Define an array of regular expressions to search for web shell signatures
  $regexes = [
    '/\\bbase64_decode\\(\\$_/i',
    '/\\beval\\(\\$_/i',
    '/\\bfile_put_contents\\(\\$_/i',
    '/\\bexec\\(\\$_/i',
    '/\\bshell_exec\\(\\$_/i',
    '/\\bsystem\\(\\$_/i',
    '/\\bpassthru\\(\\$_/i',
    '/\\bphpinfo\\(/i',
    '/\\bassert\\(/i',
    '/\\bpreg_replace\\(/i'
  ];

  // Open the file for reading
  $handle = fopen($filename, "r");
  if ($handle) {
    // Read the file line by line
    while (($line = fgets($handle)) !== false) {
      // Check if the line matches any of the web shell signatures
      foreach ($regexes as $regex) {
        if (preg_match($regex, $line)) {
          // Web shell found, return true
          fclose($handle);
          return true;
        }
      }
    }

    // File scanned, no web shells found, return false
    fclose($handle);
    return false;
  } else {
    // Error opening file, return null
    return null;
  }
}
?>
