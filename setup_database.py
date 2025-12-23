import os
import psycopg2
from supabase import create_client, Client
from dotenv import load_dotenv

# Load environment variables if you have a .env file
load_dotenv()

# --- CONFIGURATION ---
# Replace these with your actual Supabase credentials
# For DDL (creating tables), we need the direct PostgreSQL connection string (URI).
# You can find this in Supabase Dashboard -> Project Settings -> Database -> Connection string -> URI
# It looks like: postgresql://postgres:[YOUR-PASSWORD]@db.[YOUR-PROJECT-REF].supabase.co:5432/postgres
DB_CONNECTION_STRING = os.getenv("DB_CONNECTION_STRING", "postgresql://postgres:YOUR_PASSWORD@db.YOUR_PROJECT_REF.supabase.co:5432/postgres")

# For standard Supabase client usage (data manipulation, auth, etc.)
SUPABASE_URL = os.getenv("SUPABASE_URL", "https://your-project-ref.supabase.co")
SUPABASE_KEY = os.getenv("SUPABASE_KEY", "your-anon-or-service-role-key")

# --- SQL DEFINITIONS ---

SQL_CREATE_PORTFOLIOS = """
CREATE TABLE IF NOT EXISTS portfolios (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES auth.users(id) ON DELETE CASCADE,
    name TEXT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT now()
);
"""

SQL_CREATE_USER_ASSETS = """
CREATE TABLE IF NOT EXISTS user_assets (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    portfolio_id UUID REFERENCES portfolios(id) ON DELETE CASCADE,
    ticker TEXT NOT NULL,
    quantity NUMERIC NOT NULL,
    avg_price NUMERIC NOT NULL,
    purchase_date DATE,
    CONSTRAINT fk_portfolio
        FOREIGN KEY(portfolio_id) 
        REFERENCES portfolios(id)
);
"""

SQL_CREATE_MARKET_ASSETS = """
CREATE TABLE IF NOT EXISTS market_assets (
    ticker TEXT PRIMARY KEY,
    company_name TEXT,
    sector TEXT,
    price_to_earnings NUMERIC,
    vpa NUMERIC,
    dividend_yield NUMERIC,
    updated_at TIMESTAMPTZ DEFAULT now()
);
"""

def setup_database():
    print("Starting database setup...")

    # 1. Initialize Supabase Client (Demonstration)
    # This client is used for normal app interactions (SELECT, INSERT, UPDATE, DELETE)
    # but NOT for DDL (CREATE TABLE) usually.
    try:
        supabase: Client = create_client(SUPABASE_URL, SUPABASE_KEY)
        print("Supabase client initialized successfully.")
    except Exception as e:
        print(f"Warning: Could not initialize Supabase client (check URL/Key): {e}")

    # 2. Connect to Database using psycopg2 for DDL execution
    conn = None
    try:
        print("Connecting to PostgreSQL database...")
        conn = psycopg2.connect(DB_CONNECTION_STRING)
        cur = conn.cursor()

        # 3. Execute SQL DDL Statements
        print("Creating table 'portfolios'...")
        cur.execute(SQL_CREATE_PORTFOLIOS)
        
        print("Creating table 'user_assets'...")
        cur.execute(SQL_CREATE_USER_ASSETS)
        
        print("Creating table 'market_assets'...")
        cur.execute(SQL_CREATE_MARKET_ASSETS)

        # Commit the changes
        conn.commit()
        
        cur.close()
        print("\nSUCCESS: All tables created successfully!")

    except (Exception, psycopg2.DatabaseError) as error:
        print(f"\nERROR: Error while connecting to PostgreSQL or executing SQL: {error}")
        if conn:
            conn.rollback()
    finally:
        if conn is not None:
            conn.close()
            print("Database connection closed.")

    # --- RLS REMINDER ---
    print("\n" + "="*50)
    print("IMPORTANT REMINDER: ROW LEVEL SECURITY (RLS)")
    print("="*50)
    print("The tables have been created, but RLS is likely enabled by default or should be enabled.")
    print("You must configure RLS policies in the Supabase Dashboard to ensure users can only access their own data.")
    print("Example Policy for 'portfolios':")
    print("  - Enable RLS on 'portfolios' table.")
    print("  - Create policy 'Users can view own portfolios':")
    print("    USING (auth.uid() = user_id)")
    print("="*50)

if __name__ == "__main__":
    setup_database()
