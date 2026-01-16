import fundamentus as fd
import pandas as pd
import requests
import os
from supabase import create_client, Client
from dotenv import load_dotenv
from datetime import datetime
import time
import io
import random
import sys
import argparse

# Load environment variables
load_dotenv()

SUPABASE_URL = os.getenv("SUPABASE_URL")
SUPABASE_KEY = os.getenv("SUPABASE_KEY")

if not SUPABASE_URL or not SUPABASE_KEY:
    print("Error: SUPABASE_URL and/or SUPABASE_KEY is missing. Check your .env file locally or GitHub Secrets in Actions.")
    exit(1)

# Initialize Supabase client
supabase: Client = create_client(SUPABASE_URL, SUPABASE_KEY)

def clean_percentage(value):
    """Convert percentage string (e.g., '27,1%') or numeric to float (0.271)"""
    if pd.isna(value) or value == '-' or value == '':
        return None
    
    if isinstance(value, (int, float)):
        val = float(value)
    else:
        # If it's a string from the site, it's always in '27,1%' format
        clean_val = str(value).replace('%', '').replace(',', '.').strip()
        try:
            val = float(clean_val)
        except ValueError:
            return None
            
    # Always normalize: Fundamentus gives 27.1 for 27.1%
    # We want 0.271. 
    # Exception: if it's already a very small decimal, maybe it was processed.
    # But usually, it's safer to divide by 100 if > 0.00001
    return val / 100.0

def clean_integer(value):
    """Convert currency/number string or numeric to integer"""
    if pd.isna(value) or value == '-' or value == '':
        return None
    if isinstance(value, (int, float)):
        return int(value)
    
    # If it's a string like '1.234.567,00' or '1234.56'
    # Remove dots only if they look like thousand separators
    clean_val = str(value).strip()
    if ',' in clean_val and '.' in clean_val:
        clean_val = clean_val.replace('.', '').replace(',', '.')
    elif ',' in clean_val:
        clean_val = clean_val.replace(',', '.')
        
    try:
        return int(float(clean_val))
    except ValueError:
        return None

def clean_currency(value):
    """Convert currency/number string or numeric to float"""
    if pd.isna(value) or value == '-' or value == '':
        return None
    if isinstance(value, (int, float)):
        return float(value)
    
    clean_val = str(value).strip()
    if ',' in clean_val and '.' in clean_val:
        clean_val = clean_val.replace('.', '').replace(',', '.')
    elif ',' in clean_val:
        clean_val = clean_val.replace(',', '.')

    try:
        res = float(clean_val)
        # print(f"DEBUG: clean_currency({value}) -> {res}")
        return res
    except ValueError:
        return None

def parse_date(value):
    """Parse date string (DD/MM/YYYY) to ISO format (YYYY-MM-DD)"""
    if pd.isna(value) or value == '-':
        return None
    if isinstance(value, datetime):
        return value.strftime('%Y-%m-%d')
        
    try:
        return datetime.strptime(str(value), '%d/%m/%Y').strftime('%Y-%m-%d')
    except ValueError:
        return None

def get_stock_data(stock_code):
    """Fetches data using custom requests to bypass GitHub Actions blocks."""
    url = f"https://www.fundamentus.com.br/detalhes.php?papel={stock_code}"
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language': 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
        'Referer': 'https://www.fundamentus.com.br/detalhes.php',
    }
    
    try:
        response = requests.get(url, headers=headers, timeout=15)
        if response.status_code != 200:
            print(f"FAILED: HTTP {response.status_code} for {stock_code}")
            return None
        
        # Use StringIO to avoid the FutureWarning and ensure compat
        df_list = pd.read_html(io.StringIO(response.text), decimal=",", thousands='.')
        
        if not df_list or len(df_list) < 5:
            print(f"FAILED: Only {len(df_list) if df_list else 0} tables found for {stock_code}.")
            return None
        
        if not df_list or len(df_list) < 3:
            print(f"FAILED: Only {len(df_list) if df_list else 0} tables found for {stock_code}.")
            return None
        
        # Detect Type: Table 0 column 0 usually contains "Papel" or "FII"
        first_table = df_list[0]
        first_cell = str(first_table.iloc[0, 0]).strip()
        is_fii = "FII" in first_cell
        
        final_dict = {}
        
        if is_fii:
            final_dict['_categoria'] = 'fii'
            # FII Tables are very specific (Table 2 is a hybrid)
            
            # 1. Basic Info (Table 0 and Table 1)
            for i in [0, 1]:
                if i < len(df_list):
                    df = df_list[i]
                    d1 = df[[0, 1]].dropna().set_index(0)[1].to_dict()
                    final_dict.update(d1)
                    if df.shape[1] >= 4:
                        d2 = df[[2, 3]].dropna().set_index(2)[3].to_dict()
                        final_dict.update(d2)

            # 2. Table 2: Oscilações (0-1), Indicadores (2-3/4-5), Resultados (2-3/4-5)
            if len(df_list) > 2:
                df2 = df_list[2]
                for _, row in df2.iterrows():
                    # Oscilações (Cols 0-1)
                    lab0, val0 = str(row[0]).strip(), row[1]
                    if lab0 and lab0 != 'nan' and lab0 != 'Oscilações':
                        final_dict[lab0] = val0
                    
                    # Indicators/Results (Cols 2-3 and 4-5)
                    # Col 2 is label, Col 3 is 12m result or indicator
                    # Col 4 is label, Col 5 is 3m result or indicator
                    lab2, val2 = str(row[2]).strip(), row[3]
                    lab4, val4 = str(row[4]).strip(), row[5]
                    
                    if lab2 and lab2 != 'nan' and lab2 not in ['Indicadores', 'Resultado', 'Últimos 12 meses']:
                        # Indicators are usually in rows 1-3
                        # Results are in rows 6-9
                        # We can use a suffix to distinguish if it's in the results block
                        if _ > 5:
                            final_dict[f"{lab2}_12m"] = val2
                        else:
                            final_dict[lab2] = val2
                            
                    if lab4 and lab4 != 'nan' and lab4 not in ['Indicadores', 'Resultado', 'Últimos 3 meses']:
                        if _ > 5:
                            final_dict[f"{lab4}_3m"] = val4
                        else:
                            final_dict[lab4] = val4

            # 3. Table 3: Balanço (Label/Value)
            if len(df_list) > 3:
                df3 = df_list[3]
                # Same check as Table 0/1
                # ...
            
        else:
            final_dict['_categoria'] = 'stocks'
            # Stock Tables: 0, 2, 3 are Label/Value tables, 4 is Results
            for i in [0, 2, 3, 4]:
                if i < len(df_list):
                    df = df_list[i]
                    d1 = df[[0, 1]].dropna().set_index(0)[1].to_dict()
                    final_dict.update(d1)
                    if df.shape[1] >= 4:
                        d2 = df[[2, 3]].dropna().set_index(2)[3].to_dict()
                        final_dict.update(d2)
            
        # Clean labels (remove ?)
        final_dict = {str(k).replace('?', '').strip(): v for k, v in final_dict.items()}
        return final_dict

    except Exception as e:
        print(f"Error fetching {stock_code}: {e}")
        return None

def map_to_supabase(raw_dict):
    """Maps raw labels to database columns (based on import_fundamentals.py mapping)."""
    # Key mapping based on import_fundamentals.py logic
    # Note: raw labels from fundamentus often have spaces or leading chars
    
    # We create a lookup that normalizes keys (remove spaces, symbols like ?)
    # Some environments show '?' before labels from fundamentus
    clean_dict = {str(k).replace('?', '').strip(): v for k, v in raw_dict.items()}
    categoria = raw_dict.get('_categoria', 'stocks')
    
    try:
        # Common identification
        record = {
            'papel': clean_dict.get('Papel') or clean_dict.get('FII'),
            'tipo': clean_dict.get('Tipo'),
            'empresa': clean_dict.get('Empresa') or clean_dict.get('Nome'),
            'setor': clean_dict.get('Setor') or clean_dict.get('Segmento'),
            'subsetor': clean_dict.get('Subsetor'),
            'categoria': categoria,
            
            'cotacao': clean_currency(clean_dict.get('Cotação')),
            'data_ultima_cotacao': parse_date(clean_dict.get('Data últ cot')),
            'min_52_semanas': clean_currency(clean_dict.get('Min 52 sem')),
            'max_52_semanas': clean_currency(clean_dict.get('Max 52 sem')),
            'volume_medio_2m': clean_integer(clean_dict.get('Vol $ méd (2m)')),
            
            'osc_dia': clean_percentage(clean_dict.get('Dia')),
            'osc_mes': clean_percentage(clean_dict.get('Mês')),
            'osc_30_dias': clean_percentage(clean_dict.get('30 dias')),
            'osc_12_meses': clean_percentage(clean_dict.get('12 meses')),
            'osc_2025': clean_percentage(clean_dict.get('2025')),
            'osc_2024': clean_percentage(clean_dict.get('2024')),
            'osc_2023': clean_percentage(clean_dict.get('2023')),
            'osc_2022': clean_percentage(clean_dict.get('2022')),
            'osc_2021': clean_percentage(clean_dict.get('2021')),
            'osc_2020': clean_percentage(clean_dict.get('2020')),
            
            'updated_at': datetime.utcnow().isoformat()
        }

        if categoria == 'fii':
            # FII Specific Data
            record.update({
                'mandato': clean_dict.get('Mandato'),
                'segmento': clean_dict.get('Segmento'),
                'gestao': clean_dict.get('Gestão'),
                'ffo_yield': clean_percentage(clean_dict.get('FFO Yield')),
                'ffo_cota': clean_currency(clean_dict.get('FFO/Cota')),
                'vp_cota': clean_currency(clean_dict.get('VP/Cota')),
                'dividendo_cota': clean_currency(clean_dict.get('Dividendo/cota')),
                'p_vp': clean_currency(clean_dict.get('P/VP')),
                'div_yield': clean_percentage(clean_dict.get('Div. Yield')),
                'valor_mercado': clean_integer(clean_dict.get('Valor de mercado')),
                'patrimonio_liquido': clean_integer(clean_dict.get('Patrimônio Líquido')),
                'rendimento_12m': clean_integer(clean_dict.get('Rend. Distribuído_12m')), 
                'rendimento_3m': clean_integer(clean_dict.get('Rend. Distribuído_3m')),
                'numero_acoes': clean_integer(clean_dict.get('Nro. Cotas')),
            })
            # Also capture FFO and Receita for display if needed
            record['lucro_liquido_12m'] = clean_integer(clean_dict.get('FFO_12m'))
            record['receita_liquida_12m'] = clean_integer(clean_dict.get('Receita_12m'))
        else:
            # Stock Specific Data
            record.update({
                'valor_mercado': clean_integer(clean_dict.get('Valor de mercado')),
                'valor_firma': clean_integer(clean_dict.get('Valor da firma')),
                'ativo': clean_integer(clean_dict.get('Ativo')),
                'disponibilidades': clean_integer(clean_dict.get('Disponibilidades')),
                'ativo_circulante': clean_integer(clean_dict.get('Ativo Circulante')),
                'divida_bruta': clean_integer(clean_dict.get('Dív. Bruta')),
                'divida_liquida': clean_integer(clean_dict.get('Dív. Líquida')),
                'patrimonio_liquido': clean_integer(clean_dict.get('Patrim. Líq')),
                'data_ultimo_balanco': parse_date(clean_dict.get('Últ balanço processado')),
                'numero_acoes': clean_integer(clean_dict.get('Nro. Ações')),
                
                'receita_liquida_12m': clean_integer(clean_dict.get('Receita Líquida')),
                'ebit_12m': clean_integer(clean_dict.get('EBIT')),
                'lucro_liquido_12m': clean_integer(clean_dict.get('Lucro Líquido')),
                
                'p_l': clean_currency(clean_dict.get('P/L')),
                'p_vp': clean_currency(clean_dict.get('P/VP')),
                'p_ebit': clean_currency(clean_dict.get('P/EBIT')),
                'psr': clean_currency(clean_dict.get('PSR')),
                'p_ativos': clean_currency(clean_dict.get('P/Ativos')),
                'p_cap_giro': clean_currency(clean_dict.get('P/Cap. Giro')),
                'p_ativ_circ_liq': clean_currency(clean_dict.get('P/Ativ Circ Liq')),
                'div_yield': clean_percentage(clean_dict.get('Div. Yield')),
                'ev_ebitda': clean_currency(clean_dict.get('EV / EBITDA')),
                'ev_ebit': clean_currency(clean_dict.get('EV / EBIT')),
                
                'lpa': clean_currency(clean_dict.get('LPA')),
                'vpa': clean_currency(clean_dict.get('VPA')),
                'marg_bruta': clean_percentage(clean_dict.get('Marg. Bruta')),
                'marg_ebit': clean_percentage(clean_dict.get('Marg. EBIT')),
                'marg_liquida': clean_percentage(clean_dict.get('Marg. Líquida')),
                'ebit_ativo': clean_percentage(clean_dict.get('EBIT / Ativo')),
                'roic': clean_percentage(clean_dict.get('ROIC')),
                'roe': clean_percentage(clean_dict.get('ROE')),
                'liquidez_corrente': clean_currency(clean_dict.get('Liquidez Corr')),
                'div_br_patrim': clean_currency(clean_dict.get('Div Br/ Patrim')),
                'giro_ativos': clean_currency(clean_dict.get('Giro Ativos')),
                'cres_rec_5a': clean_percentage(clean_dict.get('Cres. Rec (5a)')),
            })
            
            # Handle duplicate labels for 3m results if present
            record['receita_liquida_3m'] = clean_integer(clean_dict.get('Receita Líquida (3m)'))
            record['ebit_3m'] = clean_integer(clean_dict.get('EBIT (3m)'))
            record['lucro_liquido_3m'] = clean_integer(clean_dict.get('Lucro Líquido (3m)'))

        return record
    except Exception as e:
        print(f"Mapping error: {e}")
        return None

def main():
    parser = argparse.ArgumentParser(description="Update Supabase with stock fundamental data.")
    parser.add_argument("--ticker", type=str, help="Update a specific ticker instead of all.")
    args = parser.parse_args()

    print("Starting update script...")
    
    if args.ticker:
        tickers = [args.ticker.upper()]
        print(f"Mode: Single ticker ({tickers[0]})")
    else:
        # Load all tickers from CSV
        try:
            tickers_df = pd.read_csv('tickers_ibra.csv')
            if tickers_df.columns[1] == '0':
                tickers = tickers_df.iloc[:, 1].tolist()
            else:
                tickers = tickers_df.iloc[:, 1].tolist()
            print(f"Mode: Bulk update ({len(tickers)} tickers)")
        except Exception as e:
            print(f"Error loading tickers: {e}")
            return

    records = []
    success_count = 0
    fail_count = 0

    for i, ticker in enumerate(tickers):
        if not args.ticker:
            print(f"[{i+1}/{len(tickers)}] ", end="")
        
        print(f"Processing {ticker}...", end=" ", flush=True)
        raw_data = get_stock_data(ticker)
        if raw_data:
            record = map_to_supabase(raw_data)
            if record and record['papel']:
                records.append(record)
                print("OK")
                success_count += 1
            else:
                print("MAPPING FAILED")
                fail_count += 1
        else:
            print("FETCH FAILED")
            fail_count += 1
        
        # Small delay between tickers if doing bulk update
        if not args.ticker and i < len(tickers) - 1:
            time.sleep(random.uniform(2, 5))
        
        # Upsert in batches (or immediately if single ticker)
        if len(records) >= 20 or (args.ticker and records):
            batch_size = len(records)
            print(f"Upserting {batch_size} record(s) to Supabase...", end=" ", flush=True)
            try:
                supabase.table('stock_fundamentals').upsert(records, on_conflict='papel').execute()
                records = []
                print("Done")
            except Exception as e:
                print(f"Error: {e}")

    print(f"\nUpdate finished! Success: {success_count}, Failed: {fail_count}")

if __name__ == "__main__":
    main()
