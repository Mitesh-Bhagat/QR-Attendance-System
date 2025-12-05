<?php

// START INCLUDE GUARD: Ensures the file is processed only once
if (!defined('CONN_INCLUDED')) {
    define('CONN_INCLUDED', true);

    // Function to parse .env file and return an associative array
    function parseEnv($filePath)
    {
        if (!file_exists($filePath)) {
            return [];
        }

        $contents = file_get_contents($filePath);
        $lines = explode("\n", $contents);
        $env = [];

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            // Explode with a limit of 2 to handle values that might contain '='
            $parts = explode('=', $line, 2);

            // Only process lines that actually have a key and a value
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                // Remove quotes if present
                $value = trim($value, '"\''); 
                $env[$key] = $value;
            }
        }

        return $env;
    }

    // Load .env file
    $envFilePath = __DIR__ . '/.env';
    if (!file_exists($envFilePath)) {
        die('<div style="color: #ff4d4d; background: #000; padding: 20px; font-family: monospace; border: 1px solid #ff4d4d;">
                <strong>CRITICAL ERROR:</strong> .env file not found.
             </div>');
    }

    $env = parseEnv($envFilePath);

    // Database Connection Parameters
    $db_host = $env['DB_HOST'] ?? 'localhost';
    $db_user = $env['DB_USER'] ?? 'root';
    $db_pass = $env['DB_PASS'] ?? '';
    $db_name = $env['DB_NAME'] ?? '';

    // Create Connection
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        // Output a styled error message
        die('<div style="color: #ff4d4d; background: #000; padding: 20px; font-family: monospace; border: 1px solid #ff4d4d;">
                <strong>Database Connection Failed:</strong> ' . $conn->connect_error . '
             </div>');
    }
}
// END INCLUDE GUARD
?>