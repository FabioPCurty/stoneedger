import fundamentus as fd
import pandas as pd
import os
from supabase import create_client, Client
from dotenv import load_dotenv
from datetime import datetime
import time
import io

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
    """Convert percentage string (e.g., '27,1%') to float (0.271)"""
    if pd.isna(value) or value == '-':
        return None
    if isinstance(value, (int, float)):
        return float(value)
    
    clean_val = str(value).replace('%', '').replace(',', '.').strip()
    try:
        return float(clean_val) / 100
    except ValueError:
        return None

def clean_integer(value):
    """Convert currency/number string to integer"""
    if pd.isna(value) or value == '-':
        return None
    if isinstance(value, int):
        return value
    if isinstance(value, float):
        return int(value)
    
    clean_val = str(value).replace('.', '').replace(',', '.').strip()
    try:
        return int(float(clean_val))
    except ValueError:
        return None

def clean_currency(value):
    """Convert currency/number string or int to float"""
    if pd.isna(value) or value == '-':
        return None
    if isinstance(value, (int, float)):
        return float(value)
    
    clean_val = str(value).replace('.', '').replace(',', '.').strip()
    try:
        return float(clean_val)
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
    """Fetches and transforms data for a single ticker using user's custom logic."""
    try:
        df_list = fd.get_detalhes_raw(stock_code)
        if not df_list or len(df_list) < 5:
            print(f"FAILED: Only {len(df_list) if df_list else 0} tables found for {stock_code}. Potential block or format change.")
            return None
        
        work_data = pd.concat(df_list)
        
        # Ensure we have the minimum columns needed (at least 0 and 1)
        if 0 not in work_data.columns or 1 not in work_data.columns:
            return None

        # Part 1 (based on dados_fundamentus.py)
        work_data1 = work_data[[0, 1]].dropna()
        work_data1.columns = ['texto', 'numeros']
        work_data1 = work_data1.rename_axis('index').reset_index()
        
        try:
            work_data1 = work_data1.drop(labels=[18, 22], axis=0, errors='ignore')
        except: pass

        # Part 2
        work_data2 = work_data[[2, 3]].copy()
        work_data2.columns = ['texto', 'numeros']
        work_data2 = work_data2.rename_axis('index').reset_index()
        try:
            work_data2 = work_data2.drop(labels=[7, 19, 23], axis=0, errors='ignore')
        except: pass

        # Part 3
        work_data3 = work_data[[4, 5]].dropna()
        work_data3.columns = ['texto', 'numeros']
        work_data3 = work_data3.rename_axis('index').reset_index()
        try:
            work_data3 = work_data3.drop(labels=0, axis=0, errors='ignore')
        except: pass

        resultado_concat = pd.concat([work_data1, work_data2, work_data3], ignore_index=True)
        resultado_concat['texto'] = resultado_concat['texto'].str.replace('?', '', regex=False)
        
        # Transpose content
        final_row = resultado_concat.set_index('texto')['numeros'].to_dict()
        return final_row

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
    
    try:
        record = {
            'papel': clean_dict.get('Papel'),
            'tipo': clean_dict.get('Tipo'),
            'empresa': clean_dict.get('Empresa'),
            'setor': clean_dict.get('Setor'),
            'subsetor': clean_dict.get('Subsetor'),
            
            'valor_mercado': clean_integer(clean_dict.get('Valor de mercado')),
            'valor_firma': clean_integer(clean_dict.get('Valor da firma')),
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
            
            'updated_at': datetime.utcnow().isoformat()
        }
        
        # Handle duplicate labels for 3m results if present
        # In the Excel version they were 'Receita Líquida.1', etc.
        # From details_raw, they might be different. Let's look for common variations.
        record['receita_liquida_3m'] = clean_integer(clean_dict.get('Receita Líquida (3m)'))
        record['ebit_3m'] = clean_integer(clean_dict.get('EBIT (3m)'))
        record['lucro_liquido_3m'] = clean_integer(clean_dict.get('Lucro Líquido (3m)'))

        return record
    except Exception as e:
        print(f"Mapping error: {e}")
        return None

def main():
    print("Starting consolidated update...")
    
    # Load tickers
    try:
        tickers_df = pd.read_csv('tickers_ibra.csv')
        # Expecting format similar to what was seen: index,Ticker
        # The file content showed: line 1: ,0, line 2: 0,AALR3
        # Let's adjust to skip the header if it's weird or identify the ticker column.
        if tickers_df.columns[1] == '0': # matches "0,AALR3" pattern
            tickers = tickers_df.iloc[:, 1].tolist()
        else:
            tickers = tickers_df.iloc[:, 1].tolist()
            
        print(f"Loaded {len(tickers)} tickers.")
    except Exception as e:
        print(f"Error loading tickers: {e}")
        return

    records = []
    success_count = 0
    fail_count = 0

    # Loop throughout tickers
    for i, ticker in enumerate(tickers):
        print(f"[{i+1}/{len(tickers)}] Processing {ticker}...", end=" ", flush=True)
        raw_data = get_stock_data(ticker)
        if raw_data:
            record = map_to_supabase(raw_data)
            if record and record['papel']:
                records.append(record)
                print("OK")
                success_count += 1
            else:
                print("MAPPING FAILED (check labels)")
                fail_count += 1
        else:
            print("FETCH FAILED")
            fail_count += 1
        
        # small delay to avoid rate limit
        time.sleep(1)
        
        # Upsert in batches of 20
        if len(records) >= 20:
            print(f"Upserting batch of {len(records)} records...")
            try:
                supabase.table('stock_fundamentals').upsert(records, on_conflict='papel').execute()
                records = []
            except Exception as e:
                print(f"Batch upsert error: {e}")

    # Last batch
    if records:
        print(f"Upserting final batch of {len(records)} records...")
        try:
            supabase.table('stock_fundamentals').upsert(records, on_conflict='papel').execute()
        except Exception as e:
            print(f"Final batch upsert error: {e}")

    print(f"\nConsolidated update finished! Success: {success_count}, Failed: {fail_count}")

if __name__ == "__main__":
    main()
