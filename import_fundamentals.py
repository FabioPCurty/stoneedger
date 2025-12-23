import os
import pandas as pd
import numpy as np
from supabase import create_client, Client
from dotenv import load_dotenv
from datetime import datetime

# Load environment variables
load_dotenv()

SUPABASE_URL = os.getenv("SUPABASE_URL")
SUPABASE_KEY = os.getenv("SUPABASE_KEY")

if not SUPABASE_URL or not SUPABASE_KEY:
    print("Error: SUPABASE_URL and SUPABASE_KEY must be set in .env file")
    exit(1)

# Initialize Supabase client
supabase: Client = create_client(SUPABASE_URL, SUPABASE_KEY)

def clean_percentage(value):
    """Convert percentage string (e.g., '27,1%') to float (0.271)"""
    if pd.isna(value) or value == '-':
        return None
    if isinstance(value, (int, float)):
        return float(value)
    
    # Remove % and replace comma with dot
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
    
    # Remove dots (thousand separators) and replace comma with dot
    clean_val = str(value).replace('.', '').replace(',', '.').strip()
    try:
        # Convert to float first to handle cases like "1000.0", then to int
        return int(float(clean_val))
    except ValueError:
        return None

def clean_currency(value):
    """Convert currency/number string or int to float"""
    if pd.isna(value) or value == '-':
        return None
    if isinstance(value, (int, float)):
        return float(value)
    
    # Remove dots (thousand separators) and replace comma with dot
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

def import_data(file_path='saida_single.xlsx'):
    print(f"Reading Excel file: {file_path}")
    try:
        df = pd.read_excel(file_path, header=0) 
    except Exception as e:
        print(f"Error reading Excel: {e}")
        return

    print(f"Found {len(df)} rows. Processing...")
    
    records = []
    for _, row in df.iterrows():
        try:
            record = {
                # Identification
                'papel': row['Papel'],
                'tipo': row['Tipo'],
                'empresa': row['Empresa'],
                'setor': row['Setor'],
                'subsetor': row['Subsetor'],
                
                # Market Value
                'valor_mercado': clean_integer(row['Valor de mercado']),
                'valor_firma': clean_integer(row['Valor da firma']),
                'cotacao': clean_currency(row['Cotação']),
                'data_ultima_cotacao': parse_date(row['Data últ cot']),
                'min_52_semanas': clean_currency(row['Min 52 sem']),
                'max_52_semanas': clean_currency(row['Max 52 sem']),
                'volume_medio_2m': clean_integer(row['Vol $ méd (2m)']),
                
                # Oscillations
                'osc_dia': clean_percentage(row['Dia']),
                'osc_mes': clean_percentage(row['Mês']),
                'osc_30_dias': clean_percentage(row['30 dias']),
                'osc_12_meses': clean_percentage(row['12 meses']),
                'osc_2025': clean_percentage(row['2025']),
                'osc_2024': clean_percentage(row['2024']),
                'osc_2023': clean_percentage(row['2023']),
                'osc_2022': clean_percentage(row['2022']),
                'osc_2021': clean_percentage(row['2021']),
                'osc_2020': clean_percentage(row['2020']),
                
                # Balance Sheet
                'ativo': clean_integer(row['Ativo']),
                'disponibilidades': clean_integer(row['Disponibilidades']),
                'ativo_circulante': clean_integer(row['Ativo Circulante']),
                'divida_bruta': clean_integer(row['Dív. Bruta']),
                'divida_liquida': clean_integer(row['Dív. Líquida']),
                'patrimonio_liquido': clean_integer(row['Patrim. Líq']),
                'data_ultimo_balanco': parse_date(row['Últ balanço processado']),
                'numero_acoes': clean_integer(row['Nro. Ações']),
                
                # Financial Results (12m)
                'receita_liquida_12m': clean_integer(row['Receita Líquida']),
                'ebit_12m': clean_integer(row['EBIT']),
                'lucro_liquido_12m': clean_integer(row['Lucro Líquido']),
                
                # Financial Results (3m)
                'receita_liquida_3m': clean_integer(row['Receita Líquida.1']),
                'ebit_3m': clean_integer(row['EBIT.1']),
                'lucro_liquido_3m': clean_integer(row['Lucro Líquido.1']),
                
                # Valuation Multiples
                'p_l': clean_currency(row['P/L']),
                'p_vp': clean_currency(row['P/VP']),
                'p_ebit': clean_currency(row['P/EBIT']),
                'psr': clean_currency(row['PSR']),
                'p_ativos': clean_currency(row['P/Ativos']),
                'p_cap_giro': clean_currency(row['P/Cap. Giro']),
                'p_ativ_circ_liq': clean_currency(row['P/Ativ Circ Liq']),
                'div_yield': clean_percentage(row['Div. Yield']),
                'ev_ebitda': clean_currency(row['EV / EBITDA']),
                'ev_ebit': clean_currency(row['EV / EBIT']),
                
                # Performance Indicators
                'lpa': clean_currency(row['LPA']),
                'vpa': clean_currency(row['VPA']),
                'marg_bruta': clean_percentage(row['Marg. Bruta']),
                'marg_ebit': clean_percentage(row['Marg. EBIT']),
                'marg_liquida': clean_percentage(row['Marg. Líquida']),
                'ebit_ativo': clean_percentage(row['EBIT / Ativo']),
                'roic': clean_percentage(row['ROIC']),
                'roe': clean_percentage(row['ROE']),
                'liquidez_corrente': clean_currency(row['Liquidez Corr']),
                'div_br_patrim': clean_currency(row['Div Br/ Patrim']),
                'giro_ativos': clean_currency(row['Giro Ativos']),
                'cres_rec_5a': clean_percentage(row['Cres. Rec (5a)']),
                
                'updated_at': datetime.utcnow().isoformat()
            }
            
            # Remove None values to let DB defaults handle them (or store as NULL)
            # record = {k: v for k, v in record.items() if v is not None}
            
            records.append(record)
            
        except Exception as e:
            print(f"Error processing row {row.get('Papel', 'Unknown')}: {e}")

    if records:
        print(f"Upserting {len(records)} records to Supabase...")
        try:
            data, count = supabase.table('stock_fundamentals').upsert(records, on_conflict='papel').execute()
            print("Import completed successfully!")
        except Exception as e:
            print(f"Error inserting to Supabase: {e}")
    else:
        print("No records to import.")

if __name__ == "__main__":
    import_data()
