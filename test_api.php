<?php
// Mocking environment for CLI test
$_ENV['SUPABASE_URL'] = 'https://puxuilkexmjpjnrkqysq.supabase.co';
$_ENV['SUPABASE_KEY'] = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...'; // This won't work without the actual key, it's better to use the .env

include 'api/get_dashboard_data.php';
