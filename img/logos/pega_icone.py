import openpyxl
import pandas as pd
from brapi import Brapi
import os

# Definir a variável de ambiente com o token da API
os.environ["BRAPI_API_KEY"] = "nT3gsPnG5mG2oYdPfmb1fL"
# Obter o token da API de uma variável de ambiente
api_key = os.getenv("BRAPI_API_KEY")
if not api_key:
    raise ValueError("A variável de ambiente 'BRAPI_API_KEY' não está definida.")

client = Brapi(api_key=api_key)
# Buscar cotações - Type hints completos!
quote = client.quote.retrieve(tickers="PETR4")
print(quote.results[0].regular_market_price)


