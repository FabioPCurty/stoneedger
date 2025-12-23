import os
import streamlit as st
from supabase import create_client, Client
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

SUPABASE_URL = os.getenv("SUPABASE_URL")
SUPABASE_KEY = os.getenv("SUPABASE_KEY")

if not SUPABASE_URL or not SUPABASE_KEY:
    st.error("Supabase credentials not found in .env file.")
    st.stop()

# Initialize Supabase client
# We use st.cache_resource to avoid recreating the client on every rerun
@st.cache_resource
def get_supabase_client() -> Client:
    return create_client(SUPABASE_URL, SUPABASE_KEY)

supabase = get_supabase_client()

def login_user(email, password):
    """Signs in a user with email and password."""
    try:
        response = supabase.auth.sign_in_with_password({
            "email": email,
            "password": password
        })
        return response
    except Exception as e:
        return {"error": str(e)}

def signup_user(email, password):
    """Signs up a new user."""
    try:
        response = supabase.auth.sign_up({
            "email": email,
            "password": password
        })
        return response
    except Exception as e:
        return {"error": str(e)}

def logout_user():
    """Signs out the current user."""
    try:
        supabase.auth.sign_out()
    except Exception as e:
        pass

def get_current_user():
    """Gets the currently authenticated user."""
    try:
        user = supabase.auth.get_user()
        return user
    except Exception:
        return None

def reset_password(email):
    """Sends a password reset email."""
    try:
        response = supabase.auth.reset_password_email(email)
        return response
    except Exception as e:
        return {"error": str(e)}
