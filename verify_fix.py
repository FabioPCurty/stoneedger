import sys
import os

# Add current directory to path so we can import local modules
sys.path.append(os.getcwd())

try:
    from atualizar_supabase import get_stock_data, map_to_supabase
    
    ticker = 'PETR4'
    print(f"Fetching data for {ticker} using updated crawler...")
    raw_data = get_stock_data(ticker)
    
    if raw_data:
        record = map_to_supabase(raw_data)
        
        keys_to_check = ['roe', 'roic', 'marg_bruta', 'valor_mercado', 'valor_firma']
        print("\n--- Verification Results ---")
        all_ok = True
        for key in keys_to_check:
            val = record.get(key)
            print(f"{key}: {val}")
            if val is None:
                all_ok = False
        
        if all_ok:
            print("\nSUCCESS: All critical keys found!")
        else:
            print("\nFAILURE: Some keys are still None.")
    else:
        print("FAILED to get raw data.")

except ImportError as e:
    print(f"Import Error: {e}")
except Exception as e:
    print(f"Execution Error: {e}")
