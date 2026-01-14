import pandas as pd
from supabase import create_client, Client
import os
from dotenv import load_dotenv
import time

load_dotenv()
SUPABASE_URL = os.getenv('SUPABASE_URL')
SUPABASE_KEY = os.getenv('SUPABASE_KEY')

if not SUPABASE_URL or not SUPABASE_KEY:
    print("Error: Missing Supabase credentials in .env")
    exit(1)

supabase = create_client(SUPABASE_URL, SUPABASE_KEY)
excel_path = 'ri_links.xlsx'

if not os.path.exists(excel_path):
    print(f"Error: {excel_path} not found")
    exit(1)

print(f"Reading {excel_path}...")
df = pd.read_excel(excel_path)
print(f"Total rows in Excel: {len(df)}")

updates_count = 0
errors_count = 0

# Test update on a known ticker first
test_ticker = 'PETR4'
print(f"Testing update for {test_ticker}...")
try:
    test_res = supabase.table('stock_fundamentals').update({'url_ri': 'https://www.investidorpetrobras.com.br'}).eq('papel', test_ticker).execute()
    print(f"Test result: {test_res.data}")
except Exception as e:
    print(f"Test failed: {e}")

print("Proceeding with full import...")
for index, row in df.iterrows():
    ticker = str(row['Ticker']).strip()
    url = str(row['web_RI']).strip()
    
    if not ticker or not url:
        continue
        
    try:
        # Using a small batch approach or single updates for better error tracking
        res = supabase.table('stock_fundamentals').update({'url_ri': url}).eq('papel', ticker).execute()
        if res.data:
            updates_count += 1
            if updates_count % 50 == 0:
                print(f"Progress: {updates_count} updates...")
        else:
            # Maybe ticker not in DB
            pass
    except Exception as e:
        print(f"Error updating {ticker}: {e}")
        errors_count += 1

print(f"Import finished.")
print(f"Successfully updated: {updates_count}")
print(f"Errors encountered: {errors_count}")
