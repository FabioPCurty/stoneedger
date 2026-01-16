import pandas as pd
import requests
import io
import sys

def clean_percentage(value):
    if pd.isna(value) or value == '-' or value == '':
        return None
    if isinstance(value, (int, float)):
        val = float(value)
    else:
        clean_val = str(value).replace('%', '').replace(',', '.').strip()
        try:
            val = float(clean_val)
        except ValueError:
            return None
    return val / 100.0

def clean_integer(value):
    if pd.isna(value) or value == '-' or value == '':
        return None
    if isinstance(value, (int, float)):
        return int(value)
    clean_val = str(value).strip()
    if ',' in clean_val and '.' in clean_val:
        clean_val = clean_val.replace('.', '').replace(',', '.')
    elif ',' in clean_val:
        clean_val = clean_val.replace(',', '.')
    try:
        return int(float(clean_val))
    except ValueError:
        return None

def get_stock_data(stock_code):
    url = f"https://www.fundamentus.com.br/detalhes.php?papel={stock_code}"
    headers = {
        'User-Agent': 'Mozilla/5.0'
    }
    
    try:
        response = requests.get(url, headers=headers, timeout=15)
        df_list = pd.read_html(io.StringIO(response.text), decimal=",", thousands='.')
        
        first_table = df_list[0]
        first_cell = str(first_table.iloc[0, 0]).strip()
        is_fii = "FII" in first_cell
        
        final_dict = {}
        
        if is_fii:
            print(f"{stock_code} identified as FII")
        else:
            print(f"{stock_code} identified as STOCK")
            final_dict['_categoria'] = 'stocks'
            
            print("Debugging Tables:")
            for i, df in enumerate(df_list):
                print(f"Table {i} shape: {df.shape}")
                # print(df.head())

            for i in [0, 1, 2, 3, 4]: # Included table 1 explicitly, and will handle 6 columns
                if i < len(df_list):
                    df = df_list[i]
                    # Try to robustly detect if it's a key-value table
                    if df.shape[1] >= 2:
                        d1 = df[[0, 1]].dropna().set_index(0)[1].to_dict()
                        final_dict.update(d1)
                    if df.shape[1] >= 4:
                        d2 = df[[2, 3]].dropna().set_index(2)[3].to_dict()
                        final_dict.update(d2)
                    if df.shape[1] >= 6:
                        d3 = df[[4, 5]].dropna().set_index(4)[5].to_dict()
                        final_dict.update(d3)
                        
        final_dict = {str(k).replace('?', '').strip(): v for k, v in final_dict.items()}
        
        print("\n--- All Found Keys ---")
        for k in sorted(final_dict.keys()):
            print(f"'{k}': {final_dict[k]}")
                        
        final_dict = {str(k).replace('?', '').strip(): v for k, v in final_dict.items()}
        
        # Check specific keys
        keys_to_check = ['ROE', 'ROIC', 'Marg. Bruta', 'Marg. Líquida', 'Marg. EBIT', 'Giro Ativos', 'Valor de mercado', 'Valor da firma']
        
        print("\n--- Extracted Values ---")
        for key in keys_to_check:
            val = final_dict.get(key)
            print(f"{key}: {val} (Cleaned: {clean_percentage(val) if 'Marg' in key or 'ROE' in key or 'ROIC' in key else clean_integer(val)})")
            
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    get_stock_data("PETR4")
    get_stock_data("VALE3")
