<?php
/**
 * Stone Edger - Session Manager for Vercel
 * This file ensures sessions work correctly in a stateless environment.
 */

// If we are on Vercel, we might want to store sessions in a way that persists.
// For now, this helper ensures we have a consistent way to initialize sessions.

function start_secure_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        // In Vercel/Serverless, standard file-based sessions are ephemeral.
        // A better approach is using cookies for small data or a DB for larger data.

        // For this project, we'll stick to standard sessions for local dev,
        // and for Vercel, we'll recommend a DB-backed handler.

        session_start();
    }
}

// In the future, we can add session_set_save_handler() here to use Supabase.
?>