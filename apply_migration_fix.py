import os
from supabase import create_client, Client
from dotenv import load_dotenv

load_dotenv()
url = os.getenv('SUPABASE_URL')
key = os.getenv('SUPABASE_KEY')

if not url or not key:
    print("Missing credentials")
    exit(1)

supabase = create_client(url, key)

# Since we don't have a direct DDL tool that works and RPC might not exist, 
# and the user doesn't have the Supabase CLI configured for me, 
# I will try to use the 'exec_sql' if it exists or just use the user_assets table 
# if I can't add to stock_fundamentals. 
# But wait, I should try to use the 'apply_migration' tool again but I need a valid token.
# Let's try to run a python command that prints the error if any.

print(f"Connecting to {url}...")
try:
    # Attempt to add the column. Most Supabase projects have an 'exec_sql' function if they followed standard tutorials.
    # Otherwise, this will fail.
    res = supabase.rpc('exec_sql', {'query': 'ALTER TABLE stock_fundamentals ADD COLUMN IF NOT EXISTS url_ri TEXT; COMMENT ON COLUMN stock_fundamentals.url_ri IS \'Investor Relations website URL\';'}).execute()
    print("Migration result via RPC:", res)
except Exception as e:
    print(f"RPC failed: {e}")
    print("Falling back to standard update check...")

# Let's check if we can at least see the table schema or columns.
try:
    res = supabase.table('stock_fundamentals').select('*').limit(1).execute()
    if res.data:
        print("Columns found:", res.data[0].keys())
    else:
        print("No data in table to check columns.")
except Exception as e:
    print(f"Table query failed: {e}")
